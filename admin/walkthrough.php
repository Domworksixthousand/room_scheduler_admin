

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
  </head>
  <body>

<?php
  include '../config.php';
  // go to current user page role
  if (isset($role)) {
      if ($role === "1") {
          echo "<script>location.href='../super_admin/index.php';</script>";
          exit;
      } elseif ($role  > "2") {
          echo "<script>location.href='../user/index.php';</script>";
          exit;
      }
  } else {
      echo "<script>location.href='../index.php';</script>";
      exit;
  }
?>

    <?php include 'fab.php'; ?>

    <section class="walkthrough_admin_section" style="margin-top: 100px;">
      <div class="container">
        <h2 class="text_header">Admin Walkthrough</h2>
        <p class="text-secondary mb-4">A quick guide to every section of the admin panel.</p>

        <div class="mb-4">
          <button type="button" class="wg-start-btn" onclick="AdminWalkthrough.start()">
            <i class='bx bx-play-circle'></i> Start Step-by-Step Tour
          </button>
        </div>

        <div class="wg-card">
          <h4><i class='bx bx-grid-alt'></i> Dashboard</h4>
          <p>Your landing page after logging in. Gives you an at-a-glance overview of the whole system.</p>
          <ul>
            <li><strong>Rooms</strong> card — total number of rooms registered.</li>
            <li><strong>Available / Partially Occupied / Fully Occupied Today</strong> — room status for today, click any card to jump to a filtered room list.</li>
            <li><strong>Ongoing / Upcoming / Done / Cancelled Booked Today</strong> — today's reservation counts, click any card to jump to the matching reservation list.</li>
            <li><strong>Calendar</strong> — every booking plotted by date; click an event to see its full details.</li>
          </ul>
        </div>

        <div class="wg-card">
          <h4><i class='bx bx-building'></i> Floors</h4>
          <p>Manage the building floors that rooms belong to.</p>
          <ul>
            <li>Add a new floor with the "Add Floor" button.</li>
            <li>Search floors by name using the search bar.</li>
            <li>Edit or deactivate an existing floor from the list.</li>
          </ul>
        </div>

        <div class="wg-card">
          <h4><i class="bx bx-door-open"></i> Rooms</h4>
          <p>Manage meeting rooms, their details, and images.</p>
          <ul>
            <li>Add a new room, assign it to a floor, and upload a photo.</li>
            <li>Use the filter options to view Available, Partially Occupied, or Fully Occupied rooms.</li>
            <li>Click a room to view or edit its details.</li>
          </ul>
        </div>

        <div class="wg-card">
          <h4><i class="bx bx-history"></i> History</h4>
          <p>Browse past bookings and completed reservations for reporting or auditing.</p>
          <ul>
            <li>Search by room, requester, or date.</li>
            <li>Review completed or cancelled bookings.</li>
          </ul>
        </div>

        <div class="wg-card">
          <h4><i class='bx bx-calendar'></i> Reservations</h4>
          <p>Manage all room reservations from a single place.</p>
          <ul>
            <li>Filter reservations by status: ongoing, upcoming, done, or cancelled.</li>
            <li>Add a new reservation on behalf of a requester.</li>
            <li>Review reservation details, including requester and time range.</li>
          </ul>
        </div>

      </div>
    </section>

  <script src="../assets/js/box_icons.js"></script>
  <script src="../assets/js/script.js"></script>
  <script src="../assets/js/boostrap.js"></script>
  <script src="../assets/js/walkthrough_tour.js"></script>;

  <?php
    include '../alert.php';
  ?>
</body>
</html>
