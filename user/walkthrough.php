
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Walkthrough</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/css/admin_sidebar.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
    <script src="../assets/js/jquery.js"></script>
  </head>
  <body>

<?php
  include '../config.php';
  // go to current user page role
    if($role === "1"){
        echo "<script>location.href='../super_admin/index.php';</script>";
    }elseif($role === "2"){
        echo "<script>location.href='../admin/index.php';</script>";
    }
?>

    <div class="main_content">
      <?php include 'fab.php'; ?>

      <section class="walkthrough_admin_section" style="margin-top: 100px;">
        <div class="container">
          <h2 class="text_header">Walkthrough</h2>
          <p class="text-secondary mb-4">A quick guide to every section of your room booking portal.</p>

          <div class="mb-4">
            <button type="button" class="wg-start-btn" onclick="AdminWalkthrough.start()">
              <i class='bx bx-play-circle'></i> Start Step-by-Step Tour
            </button>
          </div>

          <div class="wg-card">
            <h4><i class="bx bx-door-open"></i> Rooms</h4>
            <p>Your landing page. Browse all meeting rooms.</p>
            <ul>
              <li>Search rooms by name, serial number, or floor.</li>
              <li>Use the filter options to view Available, Partially Occupied, or Fully Occupied rooms.</li>
              <li>Click a room to view its details and book it.</li>
            </ul>
          </div>

          <div class="wg-card">
            <h4><i class='bx bx-calendar'></i> Reservations</h4>
            <p>View and manage your own reservations.</p>
            <ul>
              <li>Filter your reservations by ongoing, upcoming, done, or cancelled.</li>
              <li>Search by floor name or date.</li>
              <li>Cancel a reservation you no longer need.</li>
            </ul>
          </div>

          <div class="wg-card">
            <h4><i class='bx bx-calendar-plus'></i> Book a Room</h4>
            <p>Reserve a room for a meeting.</p>
            <ul>
              <li>Open a room's details, then click "Add Reservation".</li>
              <li>Pick a date and time range and fill in the meeting details.</li>
              <li>Save to submit your reservation.</li>
            </ul>
          </div>

        </div>
      </section>
    </div>

  <script src="../assets/js/box_icons.js"></script>
  <script src="../assets/js/script.js"></script>
  <script src="../assets/js/boostrap.js"></script>
  <script src="../assets/js/walkthrough_tour.js"></script>

  <?php
    include '../alert.php';
  ?>
</body>
</html>
