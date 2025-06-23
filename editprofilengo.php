<?php
session_start();
require('connect.php');

if (!isset($_SESSION['email'])) {
    header("Location: loginNGO.php");
    exit();
}

$email = $_SESSION['email'];
$userId = null;

// Get UserId from email (users table)
$stmtUser = $conn->prepare("SELECT UserId FROM users WHERE Email = ?");
if (!$stmtUser) die("Prepare failed: " . $conn->error);
$stmtUser->bind_param("s", $email);
$stmtUser->execute();
$resUser = $stmtUser->get_result();

if ($resUser && $resUser->num_rows === 1) {
    $row = $resUser->fetch_assoc();
    $userId = $row['UserId'];
} else {
    die("User not found.");
}
$stmtUser->close();

// Initial values
$organization_name = "";
$area = "";
$profile_pic = "profile.jpeg";

// Get NGO details from ngouser table
$stmtNgo = $conn->prepare("SELECT OrganizationName, AreasOfOperations FROM ngouser WHERE UserId = ?");
if (!$stmtNgo) die("Prepare failed: " . $conn->error);
$stmtNgo->bind_param("s", $userId);
$stmtNgo->execute();
$resNgo = $stmtNgo->get_result();

if ($resNgo && $resNgo->num_rows === 1) {
    $ngoData = $resNgo->fetch_assoc();
    $organization_name = $ngoData['OrganizationName'];
    $area = $ngoData['AreasOfOperations'];
}
$stmtNgo->close();

// Get profile picture from users table
$stmtPic = $conn->prepare("SELECT Profile_pic FROM users WHERE UserId = ?");
if (!$stmtPic) die("Prepare failed: " . $conn->error);
$stmtPic->bind_param("s", $userId);
$stmtPic->execute();
$resPic = $stmtPic->get_result();

if ($resPic && $resPic->num_rows === 1) {
    $picData = $resPic->fetch_assoc();
    if (!empty($picData['Profile_pic'])) {
        $profile_pic = $picData['Profile_pic'];
    }
}
$stmtPic->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['organization_name']);
    $new_area = trim($_POST['area']);
    $uploadDir = 'upload/';

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

    // Update profile picture if uploaded
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES["profile_pic"]["name"]);
        $file_path = $uploadDir . uniqid() . "_" . $file_name;
        if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $file_path)) {
            die("Failed to upload file.");
        }

        // Update picture in users table
        $stmtUpdatePic = $conn->prepare("UPDATE users SET Profile_pic = ? WHERE UserId = ?");
        if (!$stmtUpdatePic) die("Prepare failed: " . $conn->error);
        $stmtUpdatePic->bind_param("ss", $file_path, $userId);
        $stmtUpdatePic->execute();
        $stmtUpdatePic->close();
    }

    // Update org name and area in ngouser
    $stmtUpdate = $conn->prepare("UPDATE ngouser SET OrganizationName = ?, AreasOfOperations = ? WHERE UserId = ?");
    if (!$stmtUpdate) die("Prepare failed: " . $conn->error);
    $stmtUpdate->bind_param("sss", $new_name, $new_area, $userId);
    $stmtUpdate->execute();
    $stmtUpdate->close();
    $_SESSION['OrganizationName'] = $new_name;
    $_SESSION['AreaOfOperations'] = $new_area;
    header("Location: ngomain.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit NGO Profile</title>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif;
    }
    body {
      background-color: #f2f0f5; display: flex; justify-content: center; padding: 40px 20px;
    }
    .container {
      background: #fff; padding: 35px 40px; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      width: 100%; max-width: 550px; border: 1px solid #e0e0e0;
    }
    h1 {
      text-align: center; margin-bottom: 30px; color: #7c4dcb; font-size: 26px;
    }
    label {
      display: block; margin-bottom: 6px; font-weight: 600; color: #333;
    }
    input[type="text"], input[type="file"] {
      width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 10px; font-size: 15px; background-color: #f9f9fb;
    }
    .profile-preview {
      margin-bottom: 20px; text-align: center;
    }
    .profile-preview img {
      width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 2px solid #ccc;
    }
    button {
      background-color: #7c4dcb; color: white; padding: 12px 24px; font-weight: bold; border: none; border-radius: 10px; cursor: pointer; font-size: 15px;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Edit NGO Profile</h1>
  <form method="POST" enctype="multipart/form-data">
    <label for="organization_name">Organization Name:</label>
    <input type="text" name="organization_name" id="organization_name" value="<?= htmlspecialchars($organization_name) ?>" required>

    <label for="area">Area / Location:</label>
    <input type="text" name="area" id="area" value="<?= htmlspecialchars($area) ?>" required>

    <label for="profile_pic">Profile Picture:</label>
    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" />

    <div class="profile-preview">
      <img src="<?= htmlspecialchars($profile_pic) ?>" alt="Current Profile Picture" />
    </div>

    <button type="submit">Update</button>
  </form>
</div>
</body>
</html>
