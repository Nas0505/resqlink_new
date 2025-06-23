<?php
require('connect.php');
session_start();

if (!isset($_POST['notification_id'])) {
    echo "Invalid request.";
    exit();
}

$notificationId = $_POST['notification_id'];

// Update notification status to 'Read'
$sql = "UPDATE notification SET StatusMessage = 'Read' WHERE NotificationId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $notificationId);

if ($stmt->execute()) {
    // Return success (for AJAX calls)
    //echo "success";
    header("Location: notification.php");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>