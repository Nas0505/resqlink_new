<?php

session_start();

include('connect.php');
include('loginAdmin.html');

if (isset($_POST['clear'])) {
    // Reset the form data
    $_POST['nama'] = '';
    $_POST['role'] = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_email'])) {
    $emailToDelete = $_POST['delete_email'];

    $stmt = $conn->prepare("SELECT UserId FROM users WHERE Email = ?");
    $stmt->bind_param("s", $emailToDelete);
    $stmt->execute();
    $stmt->bind_result($userId);
    $stmt->fetch();
    $stmt->close();

    if ($userId) {
        // Delete from child tables first
        $conn->query("DELETE FROM victimuser WHERE UserId = '$userId'");
        $conn->query("DELETE FROM ngouser WHERE UserId = '$userId'");
        $conn->query("DELETE FROM adminuser WHERE UserId = '$userId'");

        // Delete from users table
        $stmt = $conn->prepare("DELETE FROM users WHERE UserId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
}

$tambahanArr = [];

$tambahanArr[] = "users.VerificationStatus = 'Approve'";

if (!empty($_POST['nama'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $tambahanArr[] = "users.FullName LIKE '%$nama%'";
}

if (!empty($_POST['role'])) {
    $role = $conn->real_escape_string($_POST['role']);
    $tambahanArr[] = "users.Role = '$role'";
}

$tambahan = "";
if (count($tambahanArr) > 0) {
    $tambahan = "WHERE " . implode(" AND ", $tambahanArr);
}
$sql = "SELECT users.UserId AS Uid, users.FullName, users.Email, users.Phone, users.Role
FROM users
LEFT JOIN adminuser ON adminuser.UserId = users.UserId 
LEFT JOIN victimuser ON victimuser.UserId = users.UserId 
LEFT JOIN ngouser ON ngouser.UserId = users.UserId 
$tambahan
ORDER BY users.Role, users.FullName";
 
$result = $conn->query($sql);

echo '<h2>All Users</h2>';

echo '<form method="POST" action="">';
?>
    Nama: <input type="text" name="nama" value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
    Role: 
    <select name="role">
        <option value="">-- All Roles --</option>
        <option value="Admin"  <?php if(isset($_POST['role']) && $_POST['role'] == "Admin") echo 'selected'; ?>>Admin</option>
        <option value="NGO"    <?php if(isset($_POST['role']) && $_POST['role'] == "NGO") echo 'selected'; ?>>NGO</option>
        <option value="Victim" <?php if(isset($_POST['role']) && $_POST['role'] == "Victim") echo 'selected'; ?>>Victim</option>
    </select>
   <input type="submit" name="search" value="Cari">
   <input type="submit" name="clear" value="Clear">

</form>
<?php

echo '<table width = "100%" border="1" id="usersTable">
    <thead>
        <tr>
            <th>User Id</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>';

    while ($row = $result->fetch_assoc()) {
    echo '<tr>
        <td>' . $row['Uid'] . '</td>
        <td>' . $row['FullName'] . '</td>
        <td>' . $row['Email'] . '</td>
        <td>' . $row['Phone'] . '</td>
        <td>' . $row['Role'] . '</td>
        <td>
            <form method="post" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this user?\');">
                <input type="hidden" name="delete_email" value="' . $row['Email'] . '">
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Users</title>

    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #b7dba7;
    margin: 20px;
    color: #333;
}

h2 {
    text-align: center;
    color: #254222;
    margin-bottom: 20px;
}

form {
    margin-bottom: 20px;
    text-align: center;
}

input[type="text"], select {
    padding: 8px;
    margin-right: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

input[type="submit"], button {
    padding: 8px 12px;
    background-color: #2f4252;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

input[type="submit"]:hover, button:hover {
    background-color: #0a0a0a;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

table th, table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #254222;
}

thead {
    background-color: #486e43;
    color: #f3f7f2;
}

tbody {
    background-color: #6fa168;
    color: #f3f7f2;
}

button[type="submit"] {
    background-color: #912816;
}

button[type="submit"]:hover {
    background-color: #692418;
}
    </style>
</head>
<body>
</html>