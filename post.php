<?php
session_start();
include('connect.php');
include('loginAdmin.html');
?>

<!DOCTYPE html>

<?php
session_start();
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pengumuman'])) {
    $cleaned = [];

    foreach ($_POST['pengumuman'] as $item) {
        $text = trim($item);
        if (!empty($text)) {
            $cleaned[] = $text;
        }
    }

    if (!empty($cleaned)) {
        file_put_contents('announcement.txt', implode(' | ', $cleaned));  // Save to file
    }

    echo "<script>alert('Pengumuman berjaya dikemaskini!'); window.location.href='post.php';</script>";
    exit();
}

include('loginAdmin.html');
?>

<html>
<head>
    <title>Pengumuman Form</title>
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
            background-color: #6fa168;
            padding: 20px 30px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        label {
            color: #f3f7f2;
        }

        .input-wrapper {
            margin-bottom: 15px;
        }

        input[type="text"] {
            padding: 10px;
            width: 500px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            margin-top: 15px;
        }

        .add-button {
            background-color: #525e36;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .add-button:hover {
            background-color: #2980b9;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #2f4252;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>POSTS</h2>
    <form method="POST" action="post.php" id="pengumumanForm">
        <div class="input-wrapper">
            <label>Pengumuman 1</label><br>
            <input type="text" name="pengumuman[]">
        </div>
        
        <button type="button" class="add-button" onclick="addInput()">+ Add Another</button><br>

        <input type="submit" value="Submit">
    </form>

    <script>
        let count = 1;

        function addInput() {
            count++;
            const wrapper = document.createElement("div");
            wrapper.className = "input-wrapper";

            const label = document.createElement("label");
            label.textContent = "Pengumuman " + count;

            const input = document.createElement("input");
            input.type = "text";
            input.name = "pengumuman[]";

            wrapper.appendChild(label);
            wrapper.appendChild(document.createElement("br"));
            wrapper.appendChild(input);

            const form = document.getElementById("pengumumanForm");
            form.insertBefore(wrapper, form.querySelector(".add-button"));
        }
    </script>

    <form method="POST" action="index.php">  <!-- Submit to index.php -->
        <table>
        <tr>
            <td>Daerah</td>
            <td>Jumlah Mangsa</td>
        </tr>
        <tr><div>
            <td>Jeli<td>
            <td><input name="total1" type="text"><td>
        </tr></div>  
        <tr><div>
            <td>Pasir Mas<td>
            <td><input name="total2" type="text"><td>
        </tr></div>  
        <tr><div>
            <td>Pasir Puteh<td>
            <td><input name="total3" type="text"><td>
        </tr></div>  
        </table>
        <input type="submit" value="Submit">
    
    </form>
</body>
</html>
