<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RESQLINK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ngostyle.css" type="text/css" />
</head>
<body>
  <header class="top-bar">
    <a href="ngomain.php"><div class="logo">RESQLINK🌐</div></a>
    <div class="nav">
      <a href="ngomain.php"><button class="nav-btn ">Utama</button></a>
      <a href="ngoinfo.html"><button class="nav-btn">Info</button></a>
      <a href="ngopermohonan.php"><button class="nav-btn">Permohonan</button></a>
    </div>
    <a href="ngopost.php"><button class="post-btn">Post</button></a>
    <a href="editprofile.php" class="profile-link">
  <div class="profile">
    👤 <?php echo $_SESSION['ngo_name']; ?><br>
  </div>
</a>

  </header>

  <section class="post-section">
    <h2>Post</h2>
    <div class="post-container">
      <form action="uploadNgo.php" method="POST" enctype="multipart/form-data" class="post-container">
  <div class="upload-box">
    <label for="upload" class="upload-icon">＋</label>
    <input type="file" id="upload" name="media" accept="image/*,video/*" required>
    <p>Sertakan gambar atau video</p>
    <div id="previewContainer"></div>
  </div>

  <div class="caption-box">
    <label for="caption">Caption</label>
    <textarea id="caption" name="caption" placeholder="Type your caption here ..." required></textarea>
    <button type="submit" class="post-btn">Post</button>
  </div>
</form>
  </section>

  <footer class="footer">
    Contacts
  </footer>

  <script>
    const uploadInput = document.getElementById('upload');
    const previewContainer = document.getElementById('previewContainer');

    uploadInput.addEventListener('change', function () {
      const file = this.files[0];
      previewContainer.innerHTML = '';

      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          let preview;
          if (file.type.startsWith('image')) {
            preview = document.createElement('img');
          } else if (file.type.startsWith('video')) {
            preview = document.createElement('video');
            preview.controls = true;
          }

          preview.src = e.target.result;
          preview.classList.add('upload-preview');
          previewContainer.appendChild(preview);
        };
        reader.readAsDataURL(file);
      }
    });

    function submitPost() {
      const caption = document.getElementById('caption').value;
      const file = uploadInput.files[0];
      if (!caption && !file) {
        alert("Please add an image/video or a caption.");
        return;
      }

      alert("Post submitted!\nCaption: " + caption);
    }
  </script>

</body>
</html>
