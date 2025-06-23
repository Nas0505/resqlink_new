<?php
require('connect.php');
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nm = $_POST['full_name'];
    $eml = $_POST['email'];
    $ic = $_POST['id']; 
    $pass = $_POST['password'];
    $phone = $_POST['phone'];
    $confirm_pass = $_POST['confirm_password'];
    $location = $_POST['location']; 

    if ($pass !== $confirm_pass) {
        $error_message = "Kata laluan tidak sepadan.";
    } else {
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM users WHERE UserId = '$ic' OR email = '$eml'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error_message = "Akaun atau emel sudah wujud. Sila cuba lagi.";
        } else {
            $sql1 = "INSERT INTO users (UserId, FullName, email, password, Phone, Role)
                     VALUES ('$ic','$nm', '$eml', '$hashedPassword', '$phone', 'Victim')";
            $sql2 = "INSERT INTO victimuser (UserId, Location)
                     VALUES ('$ic', '$location')";

            if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE){
                echo "Pendaftaran berjaya! Anda akan dialihkan ke halaman log masuk...";
                echo header("refresh:3; url=loginVictim.php");
                exit();
            } else {
                $error_message = "Ralat semasa mendaftar: " . $conn->error;
            }
        }
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESQLINK - register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginRegister.css" type="text/css">
</head>

<body>
    <header>
        <div>
            <h1><a href="index.php">RESQLINK</a></h1>
        </div>
    </header>

<div id="container">
    <div id="form" >
    <h2>Sign Up</h2>
    <div id="error-message" style="color: red; margin-bottom: 10px;"><?php echo $error_message ?></div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="signup">
    <table>
        <tr>
            <td> Full Name</td>
            <td> <input type="text" name="full_name" id="full_name" /></td>
        </tr>
        <tr>
            <td> Email</td>
            <td> <input type="email" name="email" id="email" /></td>
        </tr>
        <tr>
            <td> Identity Number</td>
            <td> <input type="number" name="id" id="id" /></td>
        </tr>
        <tr>
            <td>Phone Number</td>
            <td> <input type="tel" name="phone" id="phone" /></td>
        </tr>
        <tr>
            <td> Password</td>
            <td> <input type="password" name="password" id="password" class="password-field"/></td>
        </tr>
        <tr>
            <td> Re-Type Password</td>
            <td> <input type="password" name="confirm_password" id="confirmPassword" class="confirm-password-field"></td>
        </tr>
        
        <tr>
            <td> Area </td>
            <td>
                <select name="location" id="area">
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
                </select>
            </td>
        </tr>
        <tr>
                <td colspan="2" style="height: 20px;"></td>
                </tr>
        <tr>
            <td colspan="3">
                <input type="submit" name="submit" id="submit" value="REGISTER" />
                <input type="reset" name="reset" id="reset" value="CLEAR FORM" />
            </td>
        </tr>
    </table>
</form>

        
    </div>
</div>
<script>
document.getElementById('signup').onsubmit = function(e) {
    var password = document.getElementById('password').value;
    var confirm = document.getElementById('confirmPassword').value;
    var errorBox = document.getElementById('error-message');

    if (password !== confirm) {
        errorBox.textContent = "Passwords do not match!";
        e.preventDefault();
        return false;
    } else {
        errorBox.textContent = ""; // Clear any previous error message
    }
};
</script>
</body>

</html>