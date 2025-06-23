<?php
session_start();
require('connect.php');


$result = $conn->query("SELECT * FROM vicrequest ORDER BY CreationDate DESC LIMIT 2");
$posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 2");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>RESQLINK Dashboard</title>
  <link rel="stylesheet" href="ngostyle.css" />
</head>
<body>
  <header class="top-bar">
    <a href="ngomain.php"><div class="logo">RESQLINK🌐</div></a>
    <div class="nav">
      <a href="ngomain.php"><button class="nav-btn active">Utama</button></a>
      <a href="ngoinfo.html"><button class="nav-btn">Info</button></a>
      <a href="ngopermohonan.php"><button class="nav-btn">Permohonan</button></a>
    </div>
    <a href="ngopost.php"><button class="post-btn">Post</button></a>
    <a href="editprofile.php" class="profile-link">
      <div class="profile">
        👤 <?= $_SESSION['OrganizationName'] ?? 'NGO' ?><br>
        <span><?= $_SESSION['RegistrationNum'] ?? '' ?>, <?= $_SESSION['AreaOfOperations'] ?? '' ?></span>
      </div>
    </a>
  </header>

  <section class="banner">
    <section class="banner-slider">
      <div class="slides">
        <img src="banner.png" alt="Slide 1" />
        <img src="2.jpg" alt="Slide 2" />
        <img src="1.jpg" alt="Slide 3" />
      </div>
    </section>
    <div class="banner-text">Malaysia prepares for its worst floods in a decade</div>
  </section>

  <section class="flood-info">
    <h4>Banjir di Malaysia</h4>
    <p>Kemaskini pada 12/4/2025</p>
    <div class="flood-table">
      <div class="table-head">
        <span>Negeri</span>
        <span>Jumlah mangsa</span>
      </div>
      <div class="scroll-bar"></div>
    </div>
  </section>

  <section class="permohonan">
    <div class="permohonan-header">
      <h3>Permohonan</h3>
      <a href="ngopermohonan.php"><button class="lihat-btn">Lihat Lagi</button></a>
    </div>

    <?php while($row = $result->fetch_assoc()): 
      $urgency = strtolower(trim($row['UrgencyLvl'] ?? ''));
      $urgencyClass = 'non-urgent';
      if ($urgency === 'urgent') $urgencyClass = 'urgent';
      elseif ($urgency === 'moderate') $urgencyClass = 'moderate';
    ?>
      <div class="card <?= $urgencyClass ?>">
        <div>
          <h4><?= htmlspecialchars($row['RequestType']) ?> [<?= ucfirst($urgency) ?>]</h4>
          <p>Daerah: <?= htmlspecialchars($row['area'] ?? 'Tidak diketahui') ?></p>
          <p><?= date("H:i d/m/Y", strtotime($row['CreationDate'])) ?></p>
        </div>
        <div class="check">✔</div>
      </div>
    <?php endwhile; ?>
  </section>
  <section class="post-section">
  <h2>Post Terkini</h2>
  <div class="post-container">
    <?php while ($post = $posts->fetch_assoc()): ?>
      <div class="post-card">
        <?php if (strpos($post['media_path'], '.mp4') !== false): ?>
          <video controls src="<?= htmlspecialchars($post['media_path']) ?>"></video>
        <?php else: ?>
          <img src="<?= htmlspecialchars($post['media_path']) ?>" alt="Post Media">
        <?php endif; ?>
        <p class="caption"><?= htmlspecialchars($post['caption']) ?></p>
        <small>Dihantar oleh: <?= htmlspecialchars($post['UserId']) ?> pada <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></small>
      </div>
    <?php endwhile; ?>
  </div>
</section>
  <footer class="footer">Contacts</footer>
</body>
</html>
