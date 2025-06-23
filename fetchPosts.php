<?php
require('connect.php');

$sql = "
    SELECT posts.*, ngouser.FullName, ngouser.profile_pic, ngouser.email
    FROM posts
    JOIN ngouser ON ngouser.UserId = posts.UserId
    ORDER BY posts.created_at DESC
";

$result = $conn->query($sql);

$posts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'agencyName' => $row['FullName'],
            'NGOprofile_pic' => !empty($row['profile_pic']) ? $row['profile_pic'] : 'profile.jpeg',
            'post' => $row['media_path'],
            'caption' => $row['caption'],
            'date' => $row['created_at'],
            'email' => $row['email'],
            'user_id' => $row['UserId'],
        ];
    }
}
?>
