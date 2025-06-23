<?php
session_start();
$conn = new mysqli("localhost", "root", "", "resqlink");

$mygovid = $_POST['RegistrationNum'];
$password = $_POST['password'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    $_SESSION['RegistrationNum'] = $user['RegistrationNum'];

    if (password_verify($password, $user['Password'])) {
        $_SESSION['RegistrationNum'] = $user['RegistrationNum'];
    $_SESSION['ngo_name'] = $user['Name'];
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
