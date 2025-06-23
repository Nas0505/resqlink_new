<?php
session_start();
include('connect.php');
include('loginAdmin.html');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $email = $_POST['approve_email'] ?? $_POST['disapprove_email'] ?? null;
    $action = $_POST['action'];
    $status = '';

    // Determine the status
    if ($action === 'approve_victim' || $action === 'approve_ngo') {
        $status = 'Approve';
    } elseif ($action === 'disapprove_victim' || $action === 'disapprove_ngo') {
        $status = 'Disapprove';
    }

    // If action was invalid, skip
    if ($email && $status !== '') {
        // Update status
        $stmt = $conn->prepare("UPDATE users SET VerificationStatus = ? WHERE Email = ?");
        $stmt->bind_param("ss", $status, $email);
        $stmt->execute();
        $stmt->close();

        // Get userId and role
        $stmt = $conn->prepare("SELECT UserId, Role FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($userId, $role);
        $stmt->fetch();
        $stmt->close();

        if ($status === 'Approve') {
            if ($role === 'Victim') {
                $conn->query("INSERT IGNORE INTO victimuser (UserId) VALUES ($userId)");
            } elseif ($role === 'NGO') {
                $conn->query("INSERT IGNORE INTO ngouser (UserId) VALUES ('$userId')");
            }
        }

        // Redirect to refresh
        header("Location: dashboard.php");
        exit();
    }
}


$totalVictims = $conn->query("SELECT COUNT(*) AS count FROM users WHERE Role = 'Victim' AND VerificationStatus = 'Approve'")->fetch_assoc()['count'];
$totalNGOs = $conn->query("SELECT COUNT(*) AS count FROM users WHERE Role = 'NGO' AND VerificationStatus = 'Approve'")->fetch_assoc()['count'];
$totalVolunteers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE Role = 'Volunteer'")->fetch_assoc()['count'];
$totalAdmins = $conn->query("SELECT COUNT(*) AS count FROM users WHERE Role = 'Admin'")->fetch_assoc()['count'];


$totalRequests = $conn->query("SELECT COUNT(*) AS count FROM vicrequest")->fetch_assoc()['count'];
$approvedNGOs = $conn->query("SELECT COUNT(*) AS count FROM users WHERE Role = 'NGO' AND VerificationStatus = 'Approve'")->fetch_assoc()['count'];
$activeTasks = $conn->query("SELECT COUNT(*) AS count FROM task WHERE CompletionStatus = 'Completed'")->fetch_assoc()['count'];

$recentRequests = $conn->query("SELECT * FROM vicrequest ORDER BY UrgencyLvl");
$newVictims = $conn->query("SELECT * FROM users WHERE Role = 'Victim' AND VerificationStatus = 'Pending' ORDER BY UserId");
$newNGOs = $conn->query("SELECT * FROM users WHERE Role = 'NGO' AND VerificationStatus = 'Pending' ORDER BY UserId");

$pendingRequests = $conn->query("SELECT COUNT(*) AS count FROM vicrequest WHERE Status = 'Pending'")->fetch_assoc()['count'];

echo'<h3>Recent Requests</h3>';
echo '<table width = "100%" border="1" id="usersTable">
    <thead>
        <tr>
            <th>Request Id</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';
while ($req = $recentRequests->fetch_assoc()) {
    echo '<tr>
        <td>' . htmlspecialchars($req['ReqId']) . '</td>
        <td>' . htmlspecialchars($req['CreationDate']) . '</td>
        <td>' . htmlspecialchars($req['Status']) . '</td>
    </tr>';
}
echo '</tbody></table>';

echo'<h3>New Victim Users</h3>';
echo '<table width = "100%" border="1" id="dashboardTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>';
while ($user = $newVictims->fetch_assoc()) {
    echo '<tr>
        <td>' . htmlspecialchars($user['FullName']) . '</td>
        <td>' . htmlspecialchars($user['Email']) . '</td>
        <td>' . htmlspecialchars($user['Phone']) . '</td>
        <td>
        <form method="post" style="display:inline;">
        <input type="hidden" name="approve_email" value="' . htmlspecialchars($user['Email']) . '">
        <button type="submit" name="action" value="approve_victim">Approve</button>
        </form>
        <form method="post" style="display:inline;">
        <input type="hidden" name="disapprove_email" value="' . htmlspecialchars($user['Email']) . '">
        <button type="submit" name="action" value="disapprove_victim">Disapprove</button>
        </form>
        </td>
    </tr>';
}
echo '</tbody></table>';

echo'<h3>New NGO Users</h3>';
echo '<table width = "100%" border="1" id="dashboardTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>';
while ($user = $newNGOs->fetch_assoc()) {
    echo '<tr>
        <td>' . htmlspecialchars($user['FullName']) . '</td>
        <td>' . htmlspecialchars($user['Email']) . '</td>
        <td>' . htmlspecialchars($user['Phone']) . '</td>
        <td>
        <form method="post" style="display:inline;">
        <input type="hidden" name="approve_email" value="' . htmlspecialchars($user['Email']) . '">
        <button type="submit" name="action" value="approve_ngo">Approve</button>
        </form>
        <form method="post" style="display:inline;">
        <input type="hidden" name="disapprove_email" value="' . htmlspecialchars($user['Email']) . '">
        <button type="submit" name="action" value="disapprove_ngo">Disapprove</button>
        </form>
        </td>
    </tr>';
}
echo '</tbody></table>';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #b7dba7;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    h3 {
        color: #275d38;
        border-bottom: 2px solid #275d38;
        padding-bottom: 5px;
        margin-top: 40px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #6fa168;
        margin-top: 15px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #d3e0d3;
    }

    thead {
        background-color: #486e43;
        color: #f3f7f2;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f7f2;
    }

    tbody tr:hover {
        background-color: #e0f0e3;
    }

    button {
        padding: 6px 12px;
        margin: 2px;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
        border: none;
    }

    button[name="action"][value^="approve"] {
        background-color: #2f4252;
        color: white;
    }

    button[name="action"][value^="approve"]:hover {
        background-color: #45a049;
    }

    button[name="action"][value^="disapprove"] {
        background-color: #912816;
        color: white;
    }

    button[name="action"][value^="disapprove"]:hover {
        background-color: #d32f2f;
    }

    table#usersTable, table#dashboardTable {
        margin-bottom: 40px;
    }

    table td:last-child {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    /* Statistics Table */
    table + table {
        width: auto;
        margin-top: 30px;
        background-color: #eef7ef;
        border: 1px solid #a1c3a1;
        padding: 15px;
    }

    table + table td {
        padding: 10px 20px;
        font-weight: bold;
    }

</style>

</head>
<body>
    <h3>Statistics</h3>
<table>
    <tr>
        <td>Victims:</td>
        <td>:</td>
        <td><?= $totalVictims ?></td>
    </tr>
    <tr>
        <td>NGOs</td>
        <td>:</td>
        <td><?= $totalNGOs ?></td>
    </tr>
    <tr>
        <td>Admins</td>
        <td>:</td>
        <td><?= $totalAdmins ?></td>
    </tr>
    <tr>
        <td>Total Requests</td>
        <td>:</td>
        <td><?= $totalRequests ?></td>
    </tr>
    <tr>
        <td>Approve NGOs</td>
        <td>:</td>
        <td><?= $approvedNGOs ?></td>
    </tr>
    <tr>
        <td>Active Tasks</td>
        <td>:</td>
        <td><?= $activeTasks ?></td>
    </tr>
    <tr>
        <td>Pending Requests</td>
        <td>:</td>
        <td><?= $pendingRequests ?> request(s) pending approval</td>
    </tr>
    <script>
    </script>
</body>
</html>