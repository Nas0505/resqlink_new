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
    <link rel="stylesheet" href="styleMain.css">
    <script>
        function showEditForm() {
            document.getElementById('profile-view').style.display = 'none';
            document.getElementById('edit-form').style.display = 'block';
        }
    </script>
</head>
<body>

<header>
        <div>
            <h1>RESQLINK</h1>
        </div>
        <nav>
            <a href="victims.php">Utama</a>
            <a href="infoLogin.php">Info</a>
            <a href="contactLogin.html">Hubungi Kami</a>
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

    <div class="container">
        <div id="profile-header">
            <div><h2>Profile Information</h2></div>
            <div><button><a href="victims.php"> Kembali </a></button></div>
        </div>
        
        <div id="profile-view">
        <div> <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" class="profilepic"><br></div>
        <h2>Profile Details</h2>
        <div id="profile-details">
        <strong>Name:</strong> <?php echo htmlspecialchars($full_name); ?><br>
        <strong>Email:</strong> <?php echo htmlspecialchars($email); ?><br>
        <strong>Identity Card:</strong> <?php echo htmlspecialchars($user_id); ?><br>
        <strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?><br>
        <strong>Area:</strong> <?php echo htmlspecialchars($area); ?><br>
        </div>
        <button onclick="showEditForm()">Edit</button>
        </div>
    </div>


    <div id="edit-form" class="edit-form">
        <h2>Edit Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Full Name: <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>"></label><br>
            <label>Area: <select name="location" id="area" value="<?php echo htmlspecialchars($area); ?>">
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
                </select></label><br>
            <label>Profile Picture: <input type="file" name="profile_pic" accept="image/*"></label><br>
            <label>Phone: <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>"></label><br>
            <button type="submit">Save</button>
            <a href="customizeProfile.php"><button type="button">Cancel</button></a>
        </form>
    </div>
    </div>

        <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>
</body>
</html>

