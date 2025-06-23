<?php
require('connect.php');

// Defaults
$full_name = "Guest";
$email = "";
$area = "";
$profile_pic = "profile.jpeg";
$phone = "";

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

   $stmt = $conn->prepare("
    SELECT users.UserId, users.FullName AS full_name, victimuser.PhoneNum AS phone, victimuser.Location AS area, users.Profile_pic AS profile_pic
    FROM users
    JOIN victimuser ON users.UserId = victimuser.UserId
    WHERE users.Email = ?
");

    if (!$stmt) die("Prepare failed: " . $conn->error);

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['UserId'];
        $full_name = $user['full_name'];
        $area = $user['area'];
        $phone= $user['phone'];
        $profile_pic = !empty($user['profile_pic']) ? $user['profile_pic'] : "profile.jpeg";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_full_name = trim($_POST['full_name']);
    $new_area = trim($_POST['location']);
    $new_phone = trim($_POST['phone']);

    $targetPath = $profile_pic;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'upload/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
        $file_name = basename($_FILES["profile_pic"]["name"]);
        $targetPath = $uploadDir . uniqid() . "_" . $file_name;
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetPath);
    }

    $stmt1 = $conn->prepare("
        UPDATE users
        SET FullName = ?, Profile_pic = ?
        WHERE Email = ?
    ");
    if (!$stmt1) die("Prepare failed (users): " . $conn->error);
    $stmt1->bind_param("sss", $new_full_name, $targetPath, $email);
    $stmt1->execute();
    $stmt1->close();

    
    $stmt2 = $conn->prepare("
    UPDATE victimuser
    SET Location = ?, PhoneNum = ?
    WHERE UserId = ?
");
if (!$stmt2) die("Prepare failed (victimuser): " . $conn->error);

$stmt2->bind_param("ssi", $new_area, $new_phone, $user_id); // correct
$stmt2->execute();
$stmt2->close();

    header("Location: profile.php");
    exit();
}
?>
