<?php
require('connect.php');
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');

if (!isset($_POST['request_id'])) {
    echo "Invalid request.";
    exit();
}

$requestId = $_POST['request_id'];

// Start transaction
$conn->begin_transaction();

try {
    // Only update if current status is 'In Progress' - also set CompletionDate
    $sql = "UPDATE vicrequest SET Status = 'Completed', CompletionDate = NOW() WHERE ReqId = ? AND Status = 'In Progress'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $requestId);
    
    if (!$stmt->execute()) {
        throw new Exception("Error updating task status");
    }
    
    // Check if any row was actually updated
    if ($stmt->affected_rows > 0) {
        // Get the UserId for this request to create notification
        $getUserSql = "SELECT UserId FROM vicrequest WHERE ReqId = ?";
        $getUserStmt = $conn->prepare($getUserSql);
        $getUserStmt->bind_param("s", $requestId);
        $getUserStmt->execute();
        $userResult = $getUserStmt->get_result();
        
        if ($userResult->num_rows > 0) {
            $userId = $userResult->fetch_assoc()['UserId'];
            
            // Create notification - using 'Unread' as StatusMessage since it's an ENUM
            $notiId = uniqid("NOTI");
            $timestamp = date("Y-m-d H:i:s");
            
            $insertNotificationSql = "INSERT INTO notification (NotificationId, UserId, ReqId, StatusMessage, UpdateTime) VALUES (?, ?, ?, 'Unread', ?)";
            $insertStmt = $conn->prepare($insertNotificationSql);
            $insertStmt->bind_param("ssss", $notiId, $userId, $requestId, $timestamp);
            
            if (!$insertStmt->execute()) {
                throw new Exception("Error creating notification");
            }
            
            $insertStmt->close();
        }
        
        $getUserStmt->close();
    }
    
    $stmt->close();
    
    // Commit transaction
    $conn->commit();
    
    header("Location: ngopermohonan.php");
    exit();
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>