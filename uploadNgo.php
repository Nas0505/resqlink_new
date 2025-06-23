<?php
session_start();
require('connect.php');

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Ensure correct session
if (empty($_SESSION['UserId'])) {
  die("Unauthorized access.");
}

$UserId = $_SESSION['UserId']; // <-- CORRECT value to insert
$caption = $_POST['caption'] ?? '';

if (!isset($_FILES['media']) || $_FILES['media']['error'] !== UPLOAD_ERR_OK) {
  die("File upload failed.");
}

$media = $_FILES['media'];
$mediaName = uniqid() . '_' . basename($media['name']);
$targetDir = "uploads/";
$targetFile = $targetDir . $mediaName;

if (!is_dir($targetDir)) {
  mkdir($targetDir, 0777, true);
}

if (move_uploaded_file($media['tmp_name'], $targetFile)) {
  $stmt = $conn->prepare("INSERT INTO posts (UserId, caption, media_path) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $UserId, $caption, $targetFile);
  if ($stmt->execute()) {
    header("Location: ngomain.php?upload=success");
    exit();
  } else {
    echo "Database error: " . $stmt->error;
  }
} else {
  echo "Failed to move uploaded file.";
}
?>
