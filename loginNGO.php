<?php
session_start();
if (isset($_SESSION['RegistrationNum'])) {
    $_SESSION = array();
    session_destroy();
    echo "<meta http-equiv=\"refresh\" content=\"3;URL=index.php\">";
}
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESQLINK - NGO Log In</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginRegister.css" type="text/css">
</head>

<body>
    <header>
        <div>
            <button><a href="loginVictim.php">User</a></button>
            <button class="active"><a href="loginNGO.php">NGO</a></button>
        </div>
        <div>
            <h1><a href="index.php">RESQLINK</a></h1>
        </div>
    </header>

    <div id="container">
        <div id="form">
            <h1>NGO Log In</h1>
            <p class="center"><em>Access your organization dashboard to help those in need.</em></p>
            <form action="loginNGOProcess.php" method="POST">
                <table>
                    <tr>
                        <th>MyGovID:</th>
                        <td><input type="text" id="RegistrationNum" name="RegistrationNum" required></td>
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
                <p class="center"><b>Not registered? <a href="ngoregister.php">Register your NGO now</a></b></p>
            </div>
        </div>
    </div>
</body>
</html>
