<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resqlink";
$conn = new mysqli($servername, $username, $password, $dbname);
session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ngoSuccess = $userSuccess = "";
$ngoError = $userError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type'])) {
    if ($_POST['form_type'] == "ngo") {
        $OrganizationName = $_POST['OrganizationName'];
        $RegistrationNum = $_POST['RegistrationNum'];
        $password = $_POST['password'];
        $repeat_password = $_POST['repeat_password'];
        $AreaofOperation = $_POST['AreaofOperation'];

        $target_dir = "uploads/";
        $VerificationDoc = $_FILES['VerificationDoc']['name'];

        if ($password !== $repeat_password) {
            $ngoError = "❌ Passwords do not match!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if (!move_uploaded_file($_FILES['VerificationDoc']['tmp_name'], $target_dir . $VerificationDoc)) {
                $ngoError = "❌ Failed to upload verification document.";
            } else {
                $generatedUserId = uniqid("U", true); // generates something like U648cd00f3ab34.12345678
                $stmt1 = $conn->prepare("INSERT INTO users (UserId, Name, Password, Email, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt1->bind_param("ssssss", $generatedUserId, $OrganizationName, $hashed_password, $email, $phone, $role);

                if ($stmt1 === false) {
                    $ngoError = "❌ Failed to prepare user insertion: " . $conn->error;
                } else {
                    $email = "";
                    $phone = "";
                    $role = "NGO";
                    $stmt1->bind_param("ssssss", $generatedUserId, $OrganizationName, $hashed_password, $email, $phone, $role);


                    if ($stmt1->execute()) {
                        $newUserId = $stmt1->insert_id;

                        $stmt2 = $conn->prepare("INSERT INTO ngouser (UserId, OrganizationName, RegistrationNum, VerificationStatus, VerificationDoc, AreasOfOperations) VALUES (?, ?, ?, 'Pending', ?, ?)");
                        if ($stmt2 === false) {
                            $ngoError = "❌ Failed to prepare NGO detail insertion: " . $conn->error;
                        } else {
                            $stmt2->bind_param("sssss", $generatedUserId, $OrganizationName, $RegistrationNum, $VerificationDoc, $AreaofOperation);

                            if ($stmt2->execute()) {
                                header("Location: loginNGO.php");
                                exit();
                            } else {
                                $ngoError = "❌ Failed to insert NGO details: " . $stmt2->error;
                            }

                            $stmt2->close();
                        }
                    } else {
                        $ngoError = "❌ Failed to insert user: " . $stmt1->error;
                    }

                    $stmt1->close();
                }
            }
        }
    }
}
//tambah user registration
$conn->close();
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
    .message { text-align: center; font-weight: bold; margin: 10px 0; }
    .success { color: green; }
    .error { color: red; }
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
        <?php if ($ngoSuccess) echo "<div class='message success'>$ngoSuccess</div>"; ?>
        <?php if ($ngoError) echo "<div class='message error'>$ngoError</div>"; ?>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="form_type" value="ngo">
          <div class="form-group">
            <label>Organization Name</label>
            <input type="text" name="OrganizationName" required>
          </div>
          <div class="form-group">
            <label>Company Registration Number (MyGovID)</label>
            <input type="text" name="RegistrationNum" required>
          </div>
        
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
          </div>
          <div class="form-group">
            <label>Repeat Password</label>
            <input type="password" name="repeat_password" required>
          </div>
          <div class="form-group">
            <label>Verification Document</label>
            <input type="file" name="VerificationDoc" required>
          </div>
          <div class="form-group">
            <label>Area Of Operations</label>
            <input type="text" name="AreaofOperation" required>
          </div>
          <button type="submit" class="submit">Register</button>
        </form>
      </div>
<!--User Form-->

<script>
  function showForm(type) {
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.getElementById('ngo-form').classList.add('hidden');

    if (type === 'ngo') {
      document.querySelector('.tab-button:nth-child(1)').classList.add('active');
      document.getElementById('ngo-form').classList.remove('hidden');
    } else {
      document.querySelector('.tab-button:nth-child(2)').classList.add('active');
    }
  }
</script>

</body>
</html>