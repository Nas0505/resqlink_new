<?php
session_start();
require('connect.php');
require('fetchProfile.php'); 
require('fetchPosts.php');
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESQLINK - Main</title>
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

    <section class="banner">
        <section class="banner-slider">
            <div class="slides">
              <img src="uploads/banner.png" class="banner" alt="Slide 1" />
              <img src="uploads/2.jpg" class="banner" alt="Slide 2" />
              <img src="uploads/1.jpg" class="banner" alt="Slide 3" />
            </div>
          </section>
    </section>

    <div class="announcement-bar">
        <div class="announcement-label">Pengumuman</div>
        <div class="announcement-text">
          <marquee behavior="scroll" direction="left">announce announce announce announce</marquee>
        </div>
      </div>


      <div class="stats-container">
        <div class="status">
        <div class="status-bar">
            <span><?php echo htmlspecialchars($area); ?></span>
            <span>Aras Air: WASPADA</span>
        </div>
        <div class="request">
            <p>Permohonan anda:</p>
            <p>Tiada Permohonan dibuat</p>
        </div>
        </div>

        <section class="flood-stats">
            <h2>Banjir di Malaysia</h2>
            <p>Kemaskini pada 12/4/2025</p>
            <table>
                <thead>
                    <tr>
                        <th>Negeri</th>
                        <th>Jumlah mangsa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Johor</td>
                        <td>2,000</td>
                    </tr>
                    <tr>
                        <td>Selangor</td>
                        <td>1,200</td>
                    </tr>
                    <tr>
                        <td>Kelantan</td>
                        <td>800</td>
                    </tr>
                    <tr>
                        <td>Pahang</td>
                        <td>1,500</td>
                    </tr>
                    <tr>
                        <td>Perak</td>
                        <td>600</td>
                    </tr>
                    <tr>
                        <td>Terengganu</td>
                        <td>400</td>
                    </tr>
                    <tr>
                        <td>Kedah</td>
                        <td>300</td>
                    </tr>
                    <tr>
                        <td>Perlis</td>
                        <td>200</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>

    <section class="message-box">
        <h3>Pesanan</h3>
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="agency-info">
                        <img src="<?php echo htmlspecialchars($post['NGOprofile_pic']); ?>" class="profilepic" alt="NGO Icon">
                        <h5><?php echo htmlspecialchars(date("d M Y", strtotime($post['date']))); ?></h5>
                        <span><?php echo htmlspecialchars($post['agencyName']); ?></span>
                    </div>
                    <?php if (strpos($post['post'], '.mp4') !== false): ?>
                        <video src="<?php echo htmlspecialchars($post['post']); ?>" controls></video>
                    <?php else: ?>
                        <img src="<?php echo htmlspecialchars($post['post']); ?>" id="infographic" alt="Infographic">
                    <?php endif; ?>
                    <p><?php echo htmlspecialchars($post['caption']); ?></p>
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tiada pesanan buat masa ini.</p>
            <?php endif; ?>
</section>

    <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>
</body>
</html>
