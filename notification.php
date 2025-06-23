<?php
session_start();
require('connect.php');
require('fetchProfile.php'); 
require('notificationProcess.php');

?>


<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESQLINK - Main</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleMain.css" type="text/css">
</head>

<body>
    <header>
        <div>
            <h1>RESQLINK</h1>
        </div>
        <nav>
            <a href="victims.php">Utama</a>
            <a href="infoLogin.php">Info</a>
            <a href="contactLogin.php">Hubungi Kami</a>
            <a href="helpRequestForm.php">Mohon Bantuan</a>
            <a href="notification.php">Notifikasi</a>
        </nav>
         <div class="user-info">
            <a href= "profile.php"><img src="<?php echo htmlspecialchars($profile_pic); ?>"  alt="User Icon" class="profilepic"></a>
            <div class="user-text"><?php echo htmlspecialchars($full_name); ?><br> 
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>                 
    </header>

        <div class="notification">
            <h3>Notifikasi</h3>
        <?php if (!empty($notification)): ?>
            <?php foreach ($notification as $noti): ?>
                <div class="box1">
                    <h5><?php echo htmlspecialchars($timestamp("d M Y", strtotime($noti['timestamp']))); ?></h5>
                    <p>Your Request is <?php echo htmlspecialchars($noti['statusMessage']); ?></p>
                    
                    <?php if ($noti['status'] == 'pending') {
                        echo '<p class="pending">Masih mencari. Kami mohon kesabaran anda</p>';
                    } elseif ($noti['status'] == 'in progress') {
                        echo '<p class="accepted">Sudah diterima. Bantuan anda dalam perjalanan</p>';
                    } elseif ($noti['status'] == 'rejected') {
                        echo '<p class="rejected">Maaf, tiada agensi di kawasan anda.</p>';
                    } elseif ($noti['status'] == 'completed') {
                        echo '<p class="completed">Bantuan telah berjaya diselesaikan. sila sahkan.</p>';
                        echo '<form action="notificationProcess.php" method="POST">
                                <input type="hidden" name="request_id" value="' . htmlspecialchars($noti['ReqId']) . '">
                                <button type="submit" name="confirm_completion">Sahkan</button>
                              </form>';
                    } ?>
                    </div>


                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tiada pesanan buat masa ini.</p>
        <?php endif; ?>
    </section>
        </div>
</body>
</html> 