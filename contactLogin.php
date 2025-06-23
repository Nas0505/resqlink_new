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
    <title>RESQLINK - Contact Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleMain.css" type="text/css">
    <script>
    function toggleMenu() {
    document.getElementById("navLinks").classList.toggle("show");
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

    <section class="box1">
        <h2>Contact Us</h2>
        <div class="contactList">
            <img src="location_on.png">
            <p>No. 45, Jalan Hang Tuah Taman Bunga Raya 75300 Melaka Malaysia</p>
        </div>
        <div class="contactList">
            <img src="mail.png">
            <p>resqlink@gmail.com</p>
        </div>
        <div class="contactList">
            <img src="Phone.png">
            <p>+6012 345 6789</p>
        </div>
    </section>

    <section class="box2">
        <h2>Message</h2>
        <textarea>Type out your message here...</textarea>
    </section>

        <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>
</body>