<?php
require('connect.php');
session_start();
$email = $_SESSION['email'];
$lat = $_POST['latitude'];
$lon = $_POST['longitude'];
$urg = $_POST['urgency'];
$catArray = isset($_POST['category']) ? $_POST['category'] : [];
$cat = implode(", ", $catArray);

$email = $_SESSION['email'];
$getIdStmt = $conn->prepare("SELECT UserId FROM users WHERE Email = ?");
$getIdStmt->bind_param("s", $email);
$getIdStmt->execute();
$result = $getIdStmt->get_result();
$userId = null;

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $userId = $row['UserId'];
}
$getIdStmt->close();



if (empty($_POST['category'])) {
    die("Error: Please select at least one category.");
}

if ($lat === "" || $lon === "") {
    die("Error: Latitude and Longitude cannot be empty.");
}

if ($urg === "") {
    die("Error: Urgency level cannot be empty.");
}

else {
    $sql = "INSERT INTO vicrequest (UserId, Latitude, Longitude, RequestType, UrgencyLvl)
        VALUES ('$userId', '$lat', '$lon', '$cat', '$urg')";

    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";

    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ResQLink - Logout</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleMain.css">
<head>
<body>
    <header>
        <h1>ResQLink</h1>
    </header>
    
    <div class="box2">
        <h2>Permintaan berjaya direkodkan</h2>
        <a href = "victims.php" id = "logout">Kembali ke halaman utama</a>
    </div>

    <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>

</body>
</html>
