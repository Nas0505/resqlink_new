<?php
require('connect.php');

$nm = $_POST['full_name'];
$eml = $_POST['email'];
$ic = $_POST['id']; 
$pass = $_POST['password'];
$phone = $_POST['phone'];
$confirm_pass = $_POST['confirm_password'];
$location = $_POST['location']; 

if (empty($nm) || empty($eml) || empty($ic) || empty($pass) || empty($phone) || empty($location)) {
    echo "Error: All fields are required.";
    //echo header("refresh:3; url=registerVictim.html");
    exit();
}

if ($pass !== $confirm_pass) {
    exit();
    //echo header("refresh:3; url=registerVictim.html");
}

else {
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    $sql1 = "INSERT INTO users (UserId, FullName, email, password, Phone)
            VALUES ('$ic','$nm', '$eml', '$hashedPassword', '$phone')";

    $sql2 = "INSERT INTO victimuser (UserId, Location)
            VALUES ('$ic', '$location')";

    if ($conn->query($sql1)==TRUE && $conn->query($sql2)==TRUE){
        echo "New record created successfully";
        echo header("refresh:3; url=loginVictim.php");
    } 
    else{
        echo "Error: ". $conn->error;
    }
}

$conn->close();
?>