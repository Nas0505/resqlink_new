<?php
session_start();
if(isset($_SESSION['email']))
{
$_SESSION = array();
session_destroy();
header("refresh:3; url=index.html");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ResQLink - Logout</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleMain.css" type="text/css">
<head>
<body>
    <header>
        <h1>ResQLink</h1>
    </header>
    
    <div class="box2">
        <h2>Anda telah berjaya log keluar</h2>
        <p>Sila tunggu sebentar, anda akan diarahkan ke halaman utama.</p>
</div>

    <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>

    <script>
        setTimeout(function() {
            window.location.href = "index.html";
        }, 3000);
    </script>
</body>
</html>
