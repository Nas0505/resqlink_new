<?php
session_start();
require('connect.php');
require('fetchProfile.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Profile</title>
    <link rel="stylesheet" href="profileStyle.css">
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
    <a href="victims.php">Utama</a>
    <a href="infoLogin.php">Info</a>
    <a href="contactLogin.php">Hubungi Kami</a>
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
  <div class="info-item">
    <i class="fa fa-phone"></i> <?php echo htmlspecialchars($phone); ?>
    <div class="info-label">Phone Number</div>
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

    <select name="location" id="area">
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
    <input type="file" name="profile_pic" accept="image/*">
     <input type="num" name="phone" value="<?php echo $phone?>">
     <button type="submit">Save</button>
  </form>
</div>

        <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>
</body>
</html>