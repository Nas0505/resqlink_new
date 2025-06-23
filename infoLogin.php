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
    <title>RESQLINK - Info</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleMain.css" type="text/css">
    <script>
    function toggleMenu() {
    document.getElementById("navLinks").classList.toggle("show");
    }
    </script>
</head>

<body>
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
        <h2>Tentang Kami</h2>
        <p>ResQLink merupakan sebuah sistem berasaskan web yang dibangunkan bagi menyelaras bantuan bencana banjir antara mangsa dan pertubuhan bukan kerajaan (NGO) secara lebih teratur dan efisien. Dalam situasi bencana, ramai mangsa terpaksa menggunakan media sosial seperti Facebook atau TikTok untuk meminta bantuan kerana tiada platform khusus yang dapat menghubungkan mereka dengan penyelamat.
            Melalui ResQLink, mangsa banjir boleh menghantar permintaan bantuan secara terus dengan menyertakan lokasi semasa dan keperluan seperti makanan, air bersih atau bantuan pembersihan. NGO pula boleh melihat permintaan ini dalam masa nyata dan menghantar bantuan serta menyusun tugasan sukarelawan dengan lebih sistematik.
            ResQLink bertujuan menjadi pusat sehenti yang memudahkan komunikasi, pengesanan mangsa, serta pengagihan bantuan secara telus dan berkesan.
        </p>
    </section>

    <section class="box2">
        <h2>NGO di bawah kami</h2>
        <div id="ngo">
            <p>UTeM</p>
        </div>
        <div id="ngo">
            <p>MyCARE (Humanitarian Care Malaysia)</p>
        </div>
        <div id="ngo">
            <p>Yayasan Kebajikan Negara (YKN)</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>
</body>