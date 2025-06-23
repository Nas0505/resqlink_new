<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('connect.php');
session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ngoSuccess = $userSuccess = "";
$ngoError = $userError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type'])) {
    if ($_POST['form_type'] == "ngo") {
        // Sanitize and validate input
        $OrganizationName = trim(htmlspecialchars($_POST['OrganizationName']));
        $RegistrationNum = trim(htmlspecialchars($_POST['RegistrationNum']));
        $password = $_POST['password'];
        $repeat_password = $_POST['repeat_password'];
        $AreaofOperations = isset($_POST['AreaOfOperations']) ? trim(htmlspecialchars($_POST['AreaOfOperations'])) : '';

        // Basic validation
        if (empty($OrganizationName) || empty($RegistrationNum) || empty($AreaofOperations)) {
            $ngoError = "❌ Please fill in all required fields!";
        } elseif (strlen($password) < 8) {
            $ngoError = "❌ Password must be at least 8 characters long!";
        } elseif ($password !== $repeat_password) {
            $ngoError = "❌ Passwords do not match!";
        } else {
            // File upload handling
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            
            $VerificationDoc = basename($_FILES['VerificationDoc']['name']);
            $target_file = $target_dir . $VerificationDoc;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // File validation
            $allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            if (!in_array($fileType, $allowed_types)) {
                $ngoError = "❌ Only PDF, DOC, JPG, PNG files are allowed";
            } elseif ($_FILES['VerificationDoc']['size'] > 5000000) { // 5MB max
                $ngoError = "❌ File is too large (max 5MB)";
            } else {
                // Check if organization already exists
                $checkStmt = $conn->prepare("SELECT UserId FROM ngouser WHERE OrganizationName = ? OR RegistrationNum = ?");
                $checkStmt->bind_param("ss", $OrganizationName, $RegistrationNum);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                
                if ($checkResult->num_rows > 0) {
                    $ngoError = "❌ Organization or Registration Number already exists!";
                    $checkStmt->close();
                } else {
                    $checkStmt->close();
                    
                    if (move_uploaded_file($_FILES['VerificationDoc']['tmp_name'], $target_file)) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $generatedUserId = uniqid("U", true);
                        
                        // Default values for optional fields
                        $email = "not-provided@example.com";
                        $phone = "000-0000000";
                        $role = "NGO";
                        
                        // Start transaction
                        $conn->begin_transaction();
                        
                        try {
                            // Insert into users table
                            $stmt1 = $conn->prepare("INSERT INTO users (UserId, FullName, Password, Email, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
                            if ($stmt1 === false) {
                                throw new Exception("Prepare failed: " . $conn->error);
                            }
                            
                            $bindResult = $stmt1->bind_param("ssssss", 
                                $generatedUserId, 
                                $OrganizationName, 
                                $hashed_password, 
                                $email, 
                                $phone, 
                                $role
                            );
                            
                            if ($bindResult === false) {
                                throw new Exception("Bind failed: " . $stmt1->error);
                            }
                            
                            if (!$stmt1->execute()) {
                                throw new Exception("Execute failed: " . $stmt1->error);
                            }
                            
                            // Insert into ngouser table
                            $stmt2 = $conn->prepare("INSERT INTO ngouser (UserId, OrganizationName, RegistrationNum, VerificationStatus, VerificationDoc, AreasOfOperations) VALUES (?, ?, ?, 'Pending', ?, ?)");
                            if (!$stmt2) {
                              throw new Exception("Prepare failed (stmt2): " . $conn->error);
                            }
                            
                            $bindResult = $stmt2->bind_param("sssss", 
                                $generatedUserId, 
                                $OrganizationName, 
                                $RegistrationNum, 
                                $VerificationDoc, 
                                $AreaofOperation
                            );
                            
                            if ($bindResult === false) {
                                throw new Exception("Bind failed: " . $stmt2->error);
                            }
                            
                            if (!$stmt2->execute()) {
                                throw new Exception("Execute failed: " . $stmt2->error);
                            }
                            
                            // Commit transaction
                            $conn->commit();
                            
                            // Set success message
                            $_SESSION['registration_success'] = "Registration successful! Please wait for verification.";
                            header("Location: loginNGO.php");
                            exit();
                            
                        } catch (Exception $e) {
                            // Rollback on error
                            $conn->rollback();
                            $ngoError = "❌ Registration failed: " . $e->getMessage();
                            
                            // Delete the uploaded file if database operation failed
                            if (file_exists($target_file)) {
                                unlink($target_file);
                            }
                        } finally {
                          if ($stmt1 instanceof mysqli_stmt) $stmt1->close();
                          if ($stmt2 instanceof mysqli_stmt) $stmt2->close();
                        }

                    } else {
                        $ngoError = "❌ Failed to upload verification document. Error: " . $_FILES['VerificationDoc']['error'];
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>RESQLINK - Registration</title>
  <link rel="stylesheet" href="ngostyle.css" type="text/css" />
  <style>
    .hidden { display: none; }
    .message { 
        text-align: center; 
        font-weight: bold; 
        margin: 10px 0; 
        padding: 10px;
        border-radius: 5px;
    }
    .success { 
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }
    .error { 
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>

<header>
  <h1><a href="index.php">RESQLINK</a></h1>
</header>

<div class="tab-buttons">
  <button class="tab-button active" onclick="showForm('ngo')">NGO</button>
  <button class="tab-button" onclick="showForm('user')">User</button>
</div>

<div class="form-wrapper">
  <div class="form-card">
    <div class="form-left">
      <h2>Welcome to RESQLINK🌐</h2>
      <p>Your one-stop solution for NGO and user registration in times of crisis. Join our community today.</p>
    </div>
    <div class="form-right">
      <h2>Register</h2>
      <p>Please fill in the details below to create your account.</p>

      <!-- NGO Form -->
      <div id="ngo-form">
        <?php 
        if (!empty($ngoError)) {
            echo '<div class="message error">'.$ngoError.'</div>';
        }
        if (isset($_SESSION['registration_success'])) {
            echo '<div class="message success">'.$_SESSION['registration_success'].'</div>';
            unset($_SESSION['registration_success']);
        }
        ?>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="form_type" value="ngo">
          <div class="form-group">
            <label>Organization Name *</label>
            <input type="text" name="OrganizationName" required value="<?php echo isset($_POST['OrganizationName']) ? htmlspecialchars($_POST['OrganizationName']) : ''; ?>">
          </div>
          <div class="form-group">
            <label>Company Registration Number (MyGovID) *</label>
            <input type="text" name="RegistrationNum" required value="<?php echo isset($_POST['RegistrationNum']) ? htmlspecialchars($_POST['RegistrationNum']) : ''; ?>">
          </div>
          <div class="form-group">
            <label>Password (min 8 characters) *</label>
            <input type="password" name="password" required minlength="8">
          </div>
          <div class="form-group">
            <label>Repeat Password *</label>
            <input type="password" name="repeat_password" required minlength="8">
          </div>
          <div class="form-group">
            <label>Verification Document (PDF, DOC, JPG, PNG - max 5MB) *</label>
            <input type="file" name="VerificationDoc" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
          </div>
          <div class="form-group">
            <label>Area Of Operations *</label>
            <select name="AreaOfOperations" id="area">
                    <option> Kota Bharu,Kelantan </option>
                    <option> Tumpat,Kelantan </option>
                    <option> Pasir Mas,Kelantan </option>
                    <option> Pasir Puteh,Kelantan </option>
                    <option> Bachok,Kelantan</option>
                    <option> Machang,Kelantan</option>
                    <option> Jeli,Kelantan</option>
                    <option> Tanah Merah,Kelantan</option>
                    <option> Kuala Krai,Kelantan</option>
                    <option> Gua Musang,Kelantan</option>
                    <option> Lojing,Kelantan</option>
                </select>
          </div>
          <button type="submit" class="submit">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function showForm(type) {
    // Update tab buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
      btn.classList.remove('active');
    });
    document.querySelector(.tab-button:nth-child(${type === 'ngo' ? 1 : 2})).classList.add('active');
    
    // Show/hide forms
    document.getElementById('ngo-form').style.display = type === 'ngo' ? 'block' : 'none';
  }
</script>

</body>
</html>
<?php
$conn->close();
?>