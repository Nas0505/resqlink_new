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

    <section class="box1">
        <h2>About Us</h2>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rerum nulla quam ducimus ad unde vel porro excepturi facere, magnam quis aspernatur eius iste alias labore cupiditate, itaque quaerat quod. Provident!
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil soluta beatae consectetur, ullam vitae, vel expedita modi aliquid ipsum facere deserunt eos vero hic aliquam fuga dolorem adipisci, repellendus enim.
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil soluta beatae consectetur, ullam vitae, vel expedita modi aliquid ipsum facere deserunt eos vero hic aliquam fuga dolorem adipisci, repellendus enim.
        </p>
    </section>

    <section class="box2">
        <h2>NGO di bawah kami</h2>
        <div id="ngo">
            <p>UTeM</p>
            <p>located in Melaka</p>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quaerat commodi deserunt distinctio explicabo laboriosam accusantium quos quibusdam voluptates quisquam est vero nulla blanditiis maxime maiores, quidem molestias reiciendis vel aliquam.</p>
        </div>
        <div id="ngo">
            <p>UTeM</p>
            <p>located in Melaka</p>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quaerat commodi deserunt distinctio explicabo laboriosam accusantium quos quibusdam voluptates quisquam est vero nulla blanditiis maxime maiores, quidem molestias reiciendis vel aliquam.</p>
        </div>
        <div id="ngo">
            <p>UTeM</p>
            <p>located in Melaka</p>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quaerat commodi deserunt distinctio explicabo laboriosam accusantium quos quibusdam voluptates quisquam est vero nulla blanditiis maxime maiores, quidem molestias reiciendis vel aliquam.</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>
</body>