<?php
require('connect.php');

$sql = "
    SELECT posts.*, ngouser.OrganizationName, users.profile_pic, users.Email
    FROM posts
    JOIN users ON posts.UserId = users.UserId
    JOIN ngouser ON users.UserId = ngouser.UserId
    WHERE users.Role = 'NGO'
    ORDER BY posts.created_at DESC
";

$result = $conn->query($sql);
$posts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'agencyName' => $row['OrganizationName'],
            'NGOprofile_pic' => !empty($row['profile_pic']) ? $row['profile_pic'] : 'profile.jpeg',
            'post' => $row['media_path'],
            'caption' => $row['caption'],
            'date' => $row['created_at'],
            'email' => $row['Email'],
            'user_id' => $row['UserId'],
        ];
    }
}
?>
