<?php
session_start();
require('connect.php');
require('fetchProfile.php'); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ResQLink - Help Request</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styleForm.css" type="text/css" >
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGsJgXUsxu9MAkuqXwU9G4S70wTlgPB7Y&libraries=maps,marker"></script>

</head>
<body>
   <header>
        <div>
            <h1>RESQLINK</h1>
        </div>
        <nav>
            <a href="victims.php">Utama</a>
            <a href="infoLogin.php">Info</a>
            <a href="contactLogin.php">Hubungi Kami</a>
            <a href="helpRequestForm.php">Mohon Bantuan</a>
            <a href="notification.php">Notifikasi</a>
        </nav>

       <div class="user-info">
            <a href= "profile.php"><img src="<?php echo htmlspecialchars($profile_pic); ?>"  alt="User Icon" class="profilepic"></a>
            <div class="user-text"><?php echo htmlspecialchars($full_name); ?><br> 
            <a href="logout.php" class="logout-btn">Logout</a>
            </div> 
        </div>
    </div>
    </header>

  <div class="container">
        <div>
            <h2>Mohon Bantuan</h2>
        </div>
        <div>
            <a href="victims.php" class="back-btn">Kembali</a>
        </div>
    </div>
    <hr class="purple-line">

<div class="content">
  <div class="map-container">
    <h3>Lokasi</h3>
    <button onclick="getLocation()">Lokasi anda</button>

    <div class="map-section" style="height: 400px;">
      <div id="map" style="width: 100%; height: 100%; border-radius: 10px;"></div>
    </div>
  </div>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGsJgXUsxu9MAkuqXwU9G4S70wTlgPB7Y&libraries=maps,marker"></script>

        <script>
          const coordsDisplay = document.getElementById("coords");

          function getLocation() {
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(success, error);
            } else {
              coordsDisplay.innerHTML = "Geolocation is not supported by this browser.";
            }
          }

          function success(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            /* coordsDisplay.innerHTML = "Latitude: " + lat + "<br>Longitude: " + lon; */
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lon;

            initMap(lat, lon); // Call with correct coordinates
          }

          function error() {
            alert("Sorry, no position available.");
          }

          async function initMap(lat, lon) {
            const position = { lat: lat, lng: lon };

            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

            const map = new Map(document.getElementById("map"), {
              zoom: 15,
              center: position,
              mapId: "DEMO_MAP_ID" // Optional custom styling
            });

            const marker = new AdvancedMarkerElement({
              map: map,
              position: position,
            });
          }
        </script>


        <div class="form-section">
          <h3>Category :</h3>
          <form id="signup" name="signup" method="POST" action="helpRequestProcess.php" enctype="multipart/form-data">
          <input type="hidden" id="latitude" name="latitude" value="">
          <input type="hidden" id="longitude" name="longitude" value="">
          <div id="checkbox-group" name="category">
            <label><input type="checkbox" name="category[]" value="Clothes/Mat/Blankets"> Clothes/Mat/Blankets</label><br>
            <label><input type="checkbox" name="category[]" value="Rice"> Rice</label><br>
            <label><input type="checkbox" name="category[]" value="Canned Food"> Canned Food</label><br>
            <label><input type="checkbox" name="category[]" value="Crackers/Bread"> Crackers/Bread</label><br>
            <label><input type="checkbox" name="category[]" value="Clean Water"> Clean Water</label><br>
            <label><input type="checkbox" name="category[]" value="Post-Disaster Cleanups"> Post-Disaster Cleanups</label><br>
          </div>
          <h3> Urgency :</h3> 
          <select name="urgency" id="urgency">
          <option value="non-urgent">Non-Urgent</option>
          <option value="medium">Medium</option>
          <option value="urgent">Urgent</option>
          </select>
          <div id=error-message style="color: red; display: none;">
            Please select at least one category.
          </div>

          <button type="submit" id="submit-btn">Submit</button>
          </form>
      </div>

      <script>
          document.getElementById("signup").addEventListener("submit", function(e) {
          const checkboxes = document.querySelectorAll('input[name="category[]"]');
          let isChecked = false;

          checkboxes.forEach((checkbox) => {
              if (checkbox.checked) {
                  isChecked = true;
              }
          });

          if (!isChecked) {
              e.preventDefault(); // Stop form submission
              document.getelementById("error-message").style.display = "block"; // Show error message
          }
      });
      </script>
</div>
    <footer>
        <p>&copy; 2023 ResQLink. All rights reserved.</p>
    </footer>

</body>
</html>
