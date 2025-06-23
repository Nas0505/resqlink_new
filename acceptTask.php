<?php
require('connect.php');
session_start();

if (isset($_POST['request_id'])) {
    $requestId = intval($_POST['request_id']);

    $sql = "UPDATE vicrequest SET Status = 'completed', CompletionDate = NOW() WHERE ReqId = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $requestId);

    if ($stmt->execute()) {
        header("Location: ngopermohonan.php");
        exit();
    } else {
        echo "Error updating task: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
