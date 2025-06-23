<?php
require('connect.php');
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

if (!isset($_SESSION['email'])) {
    die("Session expired. Please login again.");
}

$email = $_SESSION['email'];
$notification = [];

// Step 1: Get user ID
$getUserStmt = $conn->prepare("SELECT UserId FROM users WHERE Email = ?");
$getUserStmt->bind_param("s", $email);
$getUserStmt->execute();
$userResult = $getUserStmt->get_result();

if ($userResult->num_rows !== 1) {
    die("User not found.");
}

$userId = $userResult->fetch_assoc()['UserId'];
$getUserStmt->close();

// Step 2: Get all notifications for this user with request details
$notifQuery = "SELECT n.*, v.Status as CurrentStatus, v.ConfirmCompletion 
              FROM notification n
              LEFT JOIN vicrequest v ON n.ReqId = v.ReqId
              WHERE n.UserId = ? 
              ORDER BY n.UpdateTime DESC";
$notifStmt = $conn->prepare($notifQuery);
$notifStmt->bind_param("s", $userId);
$notifStmt->execute();
$notifResult = $notifStmt->get_result();

while ($row = $notifResult->fetch_assoc()) {
    // Get the actual status from vicrequest table since notification only has Unread/Read
    $currentStatus = $row['CurrentStatus'] ?? 'Unknown';
    
    $notification[] = [
        'notificationId' => $row['NotificationId'],
        'ReqId' => $row['ReqId'],
        'statusMessage' => $currentStatus, // Use actual status from vicrequest
        'timestamp' => $row['UpdateTime'],
        'status' => strtolower($currentStatus), // Convert to lowercase for comparison
        'ConfirmCompletion' => $row['ConfirmCompletion'] ?? 'no',
        'isRead' => $row['StatusMessage'] === 'Read' // Track if notification is read
    ];
}

$notifStmt->close();
?>