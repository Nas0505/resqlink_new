<?php
require('connect.php');
require('fetchProfile.php'); 
require('notificationProcess.php');
date_default_timezone_set('Asia/Kuala_Lumpur');
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESQLINK - Main</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleMain.css" type="text/css">
    <style>
        .notification-unread {
            background-color:rgb(114, 149, 175);
            border-left: 4px solidrgb(67, 102, 130);
        }
        .notification-read {
            background-color:rgb(138, 164, 185);
            color: rgb(7, 34, 55);
            opacity: 0.8;
        }
        .mark-read-btn {
            background: #4caf50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 5px;
        }

        #confirm-btn{
            background:rgb(88, 124, 158);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
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
        </div>        
    </header>

    <div class="notification">
        <h3>Notifikasi</h3>
        <?php if (!empty($notification)): ?>
            <?php foreach ($notification as $noti): ?>
                <div class="box1 <?= $noti['isRead'] ? 'notification-read' : 'notification-unread' ?>">
                    <h5><?php echo date("d M Y, H:i", strtotime($noti['timestamp'])); ?></h5>
                    <p>Request ID: <strong><?php echo htmlspecialchars($noti['ReqId']); ?></strong></p>
                    <p>Status: <strong><?php echo htmlspecialchars($noti['statusMessage']); ?></strong></p>

                    <?php if ($noti['status'] == 'pending'): ?>
                        <p class="pending">Masih mencari. Kami mohon kesabaran anda</p>
                    <?php elseif ($noti['status'] == 'in progress'): ?>
                        <p class="accepted">Sudah diterima. Bantuan anda dalam perjalanan</p>
                    <?php elseif ($noti['status'] == 'rejected'): ?>
                        <p class="rejected">Maaf, tiada agensi di kawasan anda.</p>
                    <?php elseif ($noti['status'] == 'completed'): ?>
                        <p class="completed">Bantuan telah berjaya diselesaikan. Sila sahkan.</p>

                        <?php if ($noti['ConfirmCompletion'] !== 'yes'): ?>
                            <form action="confirmCompletion.php" method="POST" style="display: inline;">
                                <input type="hidden" name="request_id" value="<?= htmlspecialchars($noti['ReqId']) ?>">
                                <button type="submit" name="confirm_completion" value="1" id="confirm-btn">Sahkan</button>
                            </form>
                        <?php else: ?>
                            <p class="confirmed">Telah disahkan - Terima kasih!</p>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (!$noti['isRead']): ?>
                        <form action="markNotificationRead.php" method="POST" style="display: inline;">
                            <input type="hidden" name="notification_id" value="<?= htmlspecialchars($noti['notificationId']) ?>">
                            <button type="submit" class="mark-read-btn">Tandai Sudah Baca</button>
                        </form>
                    <?php else: ?>
                        <small style="color: #rgb(142, 161, 180);;">✓ Sudah dibaca</small>
                    <?php endif; ?>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tiada pesanan buat masa ini.</p>
        <?php endif; ?>
    </div>

    <script>
    // Auto-refresh notifications every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
    </script>
</body>
</html>