<?php
session_start();
include('connect.php');
include('loginAdmin.html');

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_daerah'])) {
    $daerahToDelete = trim($_POST['delete_daerah']);

    if (file_exists('flood_data.json')) {
        $floodData = json_decode(file_get_contents('flood_data.json'), true);
        if (isset($floodData[$daerahToDelete])) {
            unset($floodData[$daerahToDelete]);
            file_put_contents('flood_data.json', json_encode($floodData));
        }
    }

    echo "<script>alert('Data daerah berjaya dipadam.'); window.location='post.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_flood'])) {
    $daerah = trim($_POST['daerah']);
    $jumlah = intval($_POST['jumlah']);

    if (!empty($daerah)) {
        $floodData = [];

        if (file_exists('flood_data.json')) {
            $floodData = json_decode(file_get_contents('flood_data.json'), true);
        }

        $floodData[$daerah] = $jumlah;

        file_put_contents('flood_data.json', json_encode($floodData));
        echo "<script>alert('Data banjir berjaya disimpan!'); window.location='post.php';</script>";
        exit;
    }
}

$floodData = [];
if (file_exists('flood_data.json')) {
    $floodData = json_decode(file_get_contents('flood_data.json'), true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pengumuman'])) {
    $cleaned = [];

    foreach ($_POST['pengumuman'] as $item) {
        $text = trim($item);
        if (!empty($text)) {
            $cleaned[] = $text;
        }
    }

    if (!empty($cleaned)) {
        file_put_contents('announcement.txt', implode(' | ', $cleaned));
    }

    echo "<script>alert('Pengumuman berjaya dikemaskini!'); window.location.href='post.php';</script>";
    exit();
}
?>

<!DOCTYPE html>

<?php


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

   <h2>Kemaskini Statistik Banjir</h2>
<form method="POST" action="post.php">
    <div class="input-wrapper">
        <label>Nama daerah</label><br>
        <input type="text" name="daerah" placeholder="Contoh: Jeli" required>
    </div>
    <div class="input-wrapper">
        <label>Jumlah Mangsa</label><br>
        <input type="number" name="jumlah" placeholder="Contoh: 150" required>
    </div>
    <input type="submit" name="save_flood" value="Simpan Statistik">
</form>

<h2>Senarai daerah & Jumlah Mangsa</h2>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>daerah</th>
            <th>Jumlah Mangsa</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($floodData as $daerah => $jumlah): ?>
        <tr>
            <td><?= htmlspecialchars($daerah) ?></td>
            <td><?= number_format($jumlah) ?></td>
            <td>
                <form method="POST" action="post.php" style="display:inline;">
                    <input type="hidden" name="delete_daerah" value="<?= htmlspecialchars($daerah) ?>">
                    <input type="submit" value="Padam" onclick="return confirm('Padam data untuk <?= htmlspecialchars($daerah) ?>?')">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
