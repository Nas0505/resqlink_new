<?php
session_start();
require('connect.php');

$full_name = "";
$email = "";
$area = "";
$profile_pic = "profile.jpeg";

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT full_name, area, profile_pic FROM victims WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $full_name = $user['full_name'];
        $area = $user['area'];
        if (!empty($user['profile_pic'])) {
            $profile_pic = $user['profile_pic'];
        }
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_full_name = trim($_POST['full_name']);
    $new_area = trim($_POST['area']);

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'upload/';
        if(!is_dir($uploadDir)) mkdir($uploadDir,0775,true);
        $file_name = basename($_FILES["profile_pic"]["name"]);
        $uploadDir = $uploadDir . uniqid() . "_" . $file_name;
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $uploadDir);
        $stmt = $conn->prepare("UPDATE victims SET full_name = ?, area = ?, profile_pic = ? WHERE email = ?");
        $stmt->bind_param("ssss", $new_full_name, $new_area, $uploadDir, $email);
    } else {
        $stmt = $conn->prepare("UPDATE victims SET full_name = ?, area = ? WHERE email = ?");
        $stmt->bind_param("sss", $new_full_name, $new_area, $email);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile Details</title>
<link rel="stylesheet" href="profile.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script>
function toggleMenu() {
  document.getElementById("navLinks").classList.toggle("show");
}
function showEditForm() {
  document.getElementById('edit-form').style.display = 'block';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="closeEditForm()"></div>');
}
function closeEditForm() {
  document.getElementById('edit-form').style.display = 'none';
  document.querySelector('.modal-overlay').remove();
}
</script>
</head>
<body>

<header>
  <div class="header-left">
    <div class="burger" onclick="toggleMenu()">☰</div>
    <h1>RESQLINK</h1>
  </div>
  <nav class="nav-links" id="navLinks">
    <a href="index.html">Utama</a>
    <a href="info.html">Info</a>
    <a href="contact.html">Hubungi Kami</a>
    <a href="helpRequestForm.php">Mohon Bantuan</a>
    <a href="notification.php">Notifikasi</a>
  </nav>
  <div class="user-info">
    <a href="profile.php">
      <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="User Icon" class="profilepic">
    </a>
    <div class="user-text">
      <?php echo htmlspecialchars($full_name); ?><br>
      <a href="logout.php" id="logout">Log Out</a>
    </div>
  </div>
</header>

<div class="profile-background">
  <div class="profile-container">
    <h2>Profile Details</h2>
    <div class="profile-image">
      <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
   <div class="profile-info">
  <div class="info-item">
    <i class="fa fa-user"></i> <?php echo htmlspecialchars($full_name); ?>
    <div class="info-label">Name</div>
  </div>
  <div class="info-item">
    <i class="fa fa-envelope"></i> <?php echo htmlspecialchars($email); ?>
    <div class="info-label">Email</div>
  </div>
  <div class="info-item">
    <i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($area); ?>
    <div class="info-label">Area</div>
  </div>
</div>

    <button class="edit-btn" onclick="showEditForm()">Edit Profile</button>
  </div>
</div>

<div id="edit-form" class="edit-form">
  <button onclick="closeEditForm()" style="float:right;background:none;border:none;font-size:20px;cursor:pointer;">&times;</button>
  <h2>Edit Profile</h2>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="full_name" 
  value="<?php echo isset($full_name) && $full_name != '' ? htmlspecialchars($full_name) : ''; ?>"
  placeholder="Full Name" required>

    <input type="text" name="area" value="<?php echo htmlspecialchars($area); ?>" placeholder="Area" required>
    <input type="file" name="profile_pic" accept="image/*">
    <button type="submit">Save</button>
  </form>
</div>


</body>
</html>

