<?php
session_start();
include('connect.php');

$passwordErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $input_password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($input_password, $user['Password'])) {
            $_SESSION['email'] = $user['Email']; 
            $_SESSION['role'] = $user['Role'];

            if ($user['Role'] === 'Admin') {
                header("Location: loginAdmin.php");
                exit();
            } else if ($user['Role'] === 'Victim') {
                header("Location: victims.php");
                exit();
            }
        } else {
            $passwordErr = "Kata laluan salah atau email tidak dijumpai.";
        }
    } else {
        $passwordErr = "Kata laluan salah atau email tidak dijumpai.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESQLINK - Log In</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginRegister.css" type="text/css">
</head>

<body>
    <header>
        <div>
            <button><a href="loginVictim.php">User</a></button>
            <button><a href="loginNGO.php">NGO</a></button>
        </div>
        <div>
            <h1><a href="index.php">RESQLINK</a></h1>
        </div>
    </header>

<div id="container">
        <div id="form" >
                <h1>Log In</h1>
                <div id="error-message" style="color: red; display: none; margin-bottom: 10px;"><?php $error_message ?></div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <table>
                <tr>
                <th>Email:</th>
                <td><input type="email" id="email" name="email" required></td>
                </tr>
                <tr>
                <th>Password:</th>
                <td><input type="password" id="password" name="password" required></td>
                </tr>
                <tr>
                <td colspan="2" style="height: 15px;"></td>
                </tr>
                <tr>
                <td colspan="2">
                    <input type="submit" value="Login" name="submit">
                    <input type="reset" value="Clear" name="reset">
                </td>
                </tr>
            </table>
            </form>
            <div id="register">
                <p class="center"><b>New user? <a href="registerVictim.php">Sign-up now..</a></b></p>
            </div>
            
        </div>
</div>
    <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>

</body>

</html>