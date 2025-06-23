<?php
require('connect.php');
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');
$result = $conn->query("SELECT * FROM vicrequest ORDER BY CreationDate ASC");
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
    <a href="ngoinfo.html"><button class="nav-btn">Info</button></a>
    <a href="ngopermohonan.php"><button class="nav-btn active">Permohonan</button></a>
  </div>
  <a href="ngopost.php"><button class="post-btn">Post</button></a>
  <a href="editprofile.php" class="profile-link">
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

      $urgencyClass = match($urgency) {
        'non-urgent' => 'urgency-low',
        'moderate'   => 'urgency-medium',
        'urgent'     => 'urgency-high',
        default      => 'urgency-unknown'
      };

      $requests[] = [
        'id' => "map-$reqId",
        'lat' => $row['Latitude'],
        'lon' => $row['Longitude'],
        'type' => $row['RequestType'],
        'urgency' => $urgencyDisplay,
      ];
  ?>
  <div class="permohonan-card <?= $urgencyClass ?> <?= $row['Status'] === 'completed' ? 'completed' : '' ?>">
    <div class="card-header">
      <span>🕒 <?= date("H:i d/m/y", strtotime($row['CreationDate'])) ?></span>
      <span class="status <?= $row['Status'] ?>"><?= ucfirst($row['Status']) ?></span>
      <h3><?= htmlspecialchars($row['RequestType']) ?></h3>
      <p class="urgency-badge <?= $urgencyClass ?>">Urgency: <?= htmlspecialchars($urgencyDisplay) ?></p>
      <p>Location: Lat: <?= htmlspecialchars($row['Latitude']) ?>, Lon: <?= htmlspecialchars($row['Longitude']) ?></p>
    </div>

    <div class="card-body">
      <div class="map-box">
        <div id="map-<?= $reqId ?>" style="width: 100%; height: 250px; border-radius: 10px;"></div>
      </div>
    </div>

    <?php if ($row['Status'] === 'Pending'): ?>
      <form method="POST" action="acceptTask.php">
         <input type="hidden" name="request_id" value="<?= $row['ReqId'] ?>">
         <button class="accept-btn">Terima Tugas</button>
      </form>
    <?php else: ?>
      <button class="accept-btn" disabled>✅ Telah Diterima</button>
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
  const requests = <?= json_encode($requests) ?>;

  function initAllMaps() {
    requests.forEach(req => {
      const latLng = { lat: parseFloat(req.lat), lng: parseFloat(req.lon) };
      const map = new google.maps.Map(document.getElementById(req.id), {
        center: latLng,
        zoom: 14
      });

      new google.maps.Marker({
        map: map,
        position: latLng,
        title: req.type + " - " + req.urgency
      });
    });
  }

  window.addEventListener("load", initAllMaps);
</script>
</body>
</html>