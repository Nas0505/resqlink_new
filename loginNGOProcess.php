<?php
session_start();
require('connect.php');

$mygovid = $_POST['RegistrationNum'];
$password = $_POST['password'];

$sql = "SELECT users.*, ngouser.RegistrationNum, ngouser.OrganizationName, ngouser.VerificationStatus 
        FROM users 
        INNER JOIN ngouser ON users.UserId = ngouser.UserId 
        WHERE ngouser.RegistrationNum = ? AND users.Role = 'NGO'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mygovid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['Password'])) {
        $_SESSION['UserId'] = $user['UserId']; // ✅ Needed for uploads
        $_SESSION['RegistrationNum'] = $user['RegistrationNum'];
        $_SESSION['ngo_name'] = $user['OrganizationName'];
        $_SESSION['verification'] = $user['VerificationStatus'];
        $_SESSION['OrganizationName'] = $user['OrganizationName'];

        header("Location: ngomain.php");
        exit;
    } else {
        echo "❌ Incorrect password.";
    }
} else {
    echo "❌ MyGovID not registered.";
}
?>
