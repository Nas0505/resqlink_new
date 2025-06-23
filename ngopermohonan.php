<?php
require('connect.php');
session_start();

if (!isset($_SESSION['UserId'])) {
    header("Location: loginNGO.php");
    exit();
}

date_default_timezone_set('Asia/Kuala_Lumpur');

// Safely get the area from session
$ngo_area = $conn->real_escape_string($_SESSION['AreaOfOperations'] ?? '');

// Get all requests filtered by NGO's area
$sql = "SELECT * FROM vicrequest 
        JOIN victimuser ON vicrequest.UserId = victimuser.UserId
        ORDER BY 
            FIELD(vicrequest.Status, 'Pending', 'In Progress', 'Completed'),
            FIELD(vicrequest.UrgencyLvl, 'Urgent', 'Medium', 'Non-urgent'),
            vicrequest.CreationDate ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$requests = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RESQLINK Dashboard</title>
  <link rel="stylesheet" href="ngostyle.css" type="text/css" />
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGsJgXUsxu9MAkuqXwU9G4S70wTlgPB7Y&libraries=maps,marker"></script>
</head>
<body>
<header class="top-bar">
  <a href="ngomain.php"><div class="logo">RESQLINK🌐</div></a>
  <div class="nav">
    <a href="ngomain.php"><button class="nav-btn">Utama</button></a>
    <a href="ngoinfo.php"><button class="nav-btn">Info</button></a>
    <a href="ngopermohonan.php"><button class="nav-btn active">Permohonan</button></a>
  </div>
  <a href="ngopost.php"><button class="post-btn">Post</button></a>
  <a href="editprofilengo.php" class="profile-link">
    <div class="profile">
      👤 <?= $_SESSION['OrganizationName'] ?? 'NGO' ?><br>
      <span><?= $_SESSION['RegistrationNum'] ?? '' ?>, <?= $_SESSION['AreaOfOperations'] ?? '' ?></span>
    </div>
  </a>
</header>

<section class="permohonan-section">
  <h2>Permohonan</h2>
  <hr>

  <?php 
  if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()):
      $reqId = $row['ReqId'];
      $urgency = strtolower(trim($row['UrgencyLvl'] ?? ''));
      $urgencyDisplay = $row['UrgencyLvl'] ?? 'Unknown';

      $urgencyClass = match($urgencyDisplay) {
          'Non-Urgent' => 'urgency-low',
          'Moderate'     => 'urgency-moderate',
          'Urgent'     => 'urgency-high',
          default       => 'urgency-unknown'
      };

      $requests[] = [
        'id' => "map-$reqId",
        'lat' => $row['Latitude'],
        'lon' => $row['Longitude'],
        'type' => $row['RequestType'],
        'urgency' => $urgencyDisplay,
      ];
  ?>
  <div class="permohonan-card <?= $urgencyClass ?> <?= $row['Status'] === 'Completed' ? 'completed' : '' ?>">
    <div class="card-header">
      <span>🕒 <?= date("H:i d/m/y", strtotime($row['CreationDate'])) ?></span>
      <span class="status <?= strtolower($row['Status']) ?>"><?= ucfirst($row['Status']) ?></span>
    </div>
    <div class="card-content">
      <h3><?= htmlspecialchars($row['RequestType']) ?></h3>
      <div class="urgency-display <?= $urgencyClass ?>">
        Urgency: <?= htmlspecialchars($urgencyDisplay) ?>
      </div>
      <p>Location: Lat: <?= htmlspecialchars($row['Latitude']) ?>, Lon: <?= htmlspecialchars($row['Longitude']) ?></p>
    </div>
    <div class="card-body">
      <div class="map-box">
        <div id="map-<?= $reqId ?>" 
          class="map-container" 
          data-lat="<?= htmlspecialchars($row['Latitude']) ?>" 
          data-lon="<?= htmlspecialchars($row['Longitude']) ?>" 
          data-type="<?= htmlspecialchars($row['RequestType']) ?>" 
          data-urgency="<?= htmlspecialchars($urgencyDisplay) ?>"
          style="width: 100%; height: 250px; border-radius: 10px;">
        </div>
      </div>
      <div class="request-type-list">
        <strong>Jenis Permohonan:</strong><br>
        <?php 
          $types = explode(',', $row['RequestType']); 
          foreach ($types as $type): ?>
            <span class="request-type-badge"><?= htmlspecialchars(trim($type)) ?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if ($row['Status'] === 'Pending'): ?>
      <form method="POST" action="acceptTask.php">
         <input type="hidden" name="request_id" value="<?= $row['ReqId'] ?>">
         <button class="accept-btn">Terima Tugas</button>
      </form>
    <?php elseif ($row['Status'] === 'In Progress'): ?>
      <form method="POST" action="updatedTask.php">
         <input type="hidden" name="request_id" value="<?= $row['ReqId'] ?>">
         <button class="accept-btn">✔ Tandai Selesai</button>
      </form>
    <?php elseif ($row['Status'] === 'Completed'): ?>
      <p class="completed-status">✅ Tugas telah diselesaikan</p>
    <?php endif; ?>
  </div>
  <?php 
    endwhile;
  } else {
    echo '<p class="no-requests">Tiada permohonan terkini.</p>';
  }
  ?>
</section>

<footer class="footer">Contacts</footer>

<script>
  function initAllMaps() {
    const maps = document.querySelectorAll('.map-container');

    maps.forEach(mapDiv => {
      const lat = parseFloat(mapDiv.dataset.lat);
      const lon = parseFloat(mapDiv.dataset.lon);
      const type = mapDiv.dataset.type;
      const urgency = mapDiv.dataset.urgency;

      const latLng = { lat: lat, lng: lon };

      const map = new google.maps.Map(mapDiv, {
        center: latLng,
        zoom: 14
      });

      new google.maps.Marker({
        map: map,
        position: latLng,
        title: type + " - " + urgency
      });
    });
  }

  window.addEventListener("load", initAllMaps);
</script>

</body>
</html>