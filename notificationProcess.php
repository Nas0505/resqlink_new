<?php
require('connect.php');

$email = $_SESSION['email'];
$pendingRequests = [];
$inProgressRequests = [];
$completedRequests = [];
$rejectedRequests = [];
$allRequests = [];

$sql = "SELECT * FROM vicrequest JOIN users ON vicrequest.UserId = users.UserId WHERE email = '$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
    $status = strtolower($row['Status']);
    $entry = [
        'requestId' => $row['ReqId'],
        'userId' => $row['UserId'],
        'latitude' => $row['Latitude'],
        'longitude' => $row['Longitude'],
        'requestType' => $row['RequestType'],
        'urgencyLevel' => $row['UrgencyLvl'],
        'status' => $row['Status'],
        'date' => $row['CreationDate']
    ];

    $allRequests[] = $entry;

    // categorize by status
    switch ($status) {
        case 'pending': $pendingRequests[] = $entry; break;
        case 'in progress': $inProgressRequests[] = $entry; break;
        case 'completed': $completedRequests[] = $entry; break;
        case 'rejected': $rejectedRequests[] = $entry; break;
    }

    // Create notification only if not already existing
    $statusMessage = $row['Status'];
    $updateTime = date("Y-m-d H:i:s");

    $checkSql = "SELECT * FROM notification WHERE ReqId = '{$row['ReqId']}' AND StatusMessage = '{$statusMessage}'";
    $checkResult = $conn->query($checkSql);
    
    if ($checkResult->num_rows == 0) {
        $sql2 = "INSERT INTO notification (UserId, ReqId, StatusMessage, UpdateTime) 
                 VALUES ('{$row['UserId']}', '{$row['ReqId']}', '{$statusMessage}', '{$updateTime}')";
        $conn->query($sql2);
    }
}
}
?>
