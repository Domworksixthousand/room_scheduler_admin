

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Walkthrough</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
  </head>
  <body class="body_color">

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
   include '../../mysidebar/Sidebar_Portal.php';
?>

   <header>
      <div class="top_bar_nav">
        <div class="container d-flex justify-content-between">
          <img src="../assets/images/FsLogoW.png" class="fs-icon">
        <div class="w-100 text-end">
        <button id="BtnBurger" class="btn btn-ghost ">
          ☰
        </button>
        </div>
        </div>
      </div>
    </header>
   


  <div class="main_content">
    s<?php include 'fab.php'; ?>
    <section class="walkthrough_admin_section " >
      <div class="container">
        <h2 class="text-header"> Walkthrough</h2>
        <p class="text-secondary mb-4">A quick guide to every section of the admin panel.</p>

        <div class="mb-4">
          <button type="button" class="wg-start-btn" onclick="AdminWalkthrough.start()">
            <i class='bx bx-play-circle'></i> Start Step-by-Step Tour
          </button>
        </div>

        <div class="wg-card">
          <h4><i class='bx bx-menu'></i> Menu</h4>
          <p>The floating navigation bar at the bottom of the screen. Use it to jump between sections.</p>
          <ul>
            <li>Quickly switch between Dashboard, Floors, Rooms, History, and Reservations.</li>
            <li>Tap <strong>Walkthrough</strong> anytime to come back to this guide.</li>
          </ul>
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
            <li>Click the <i class="bx bx-info-circle"></i> info icon on a room card to open its full details and booking calendar.</li>
            <li>In the Calendar tab, select any current or future date to go straight to the Add Reservation form. Past dates can't be selected.</li>
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
            <li>Filter the Reservation By weekly, Daily and Montly Reservations.</li>
            <li>There are 3 icons: <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> for **Info** (to view room details), <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x-icon lucide-calendar-x"><path d="M8 2v3"/><path d="M16 2v3"/><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="m14 13-4 4"/><path d="m10 13 4 4"/></svg> for **Cancellation**, and <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up-icon lucide-calendar-arrow-up"><path d="m14 17 4-4 4 4"/><path d="M16 2v3"/><path d="M18 21v-8"/><path d="M21 10.343V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h9"/><path d="M3 9h18"/><path d="M8 2v3"/></svg> for **Rescheduling or Updating** reservations. Please note that only the user who originally made the booking is allowed to cancel or update it.</li>
          </ul>
        </div>

      </div>
    </section>
  </div>
  

  <script src="../assets/js/box_icons.js"></script>
  <script src="../assets/js/script.js"></script>
  <script src="../assets/js/boostrap.js"></script>
  <script src="../assets/js/walkthrough_tour.js?v=9"></script>

  <?php
    include '../alert.php';
  ?>
</body>
</html>
