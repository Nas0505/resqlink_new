<?php

require('connect.php');
//echo "Session Email: " . ($_SESSION['email'] ?? 'NOT SET');


$full_name = ""; 
$location = "";  
$profile_pic = "profile.jpeg";

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

     $stmt = $conn->prepare("
        SELECT users.FullName AS full_name, victimuser.Location, users.Profile_pic
        FROM users
        JOIN victimuser ON users.UserId = victimuser.UserId
        WHERE users.Email = ?
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $full_name = $user['full_name'] ? $user['full_name'] : "Guest"; 
        $location = $user['Location']   ? $user['Location'] : "Unknown"; 
        $profile_pic = !empty($user['profile_pic']) ? $user['profile_pic'] : "profile.jpeg";
    }

    $stmt->close();
}
?>
