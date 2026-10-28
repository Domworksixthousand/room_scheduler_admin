
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
      <?php include 'fab.php'; ?>
      <section class="walkthrough_admin_section">
        <div class="container">
          <h2 class="text-header">Walkthrough</h2>
          <p class="text-secondary mb-4">A quick guide to every section of your room booking portal.</p>
          <div class="mb-4">
            <button type="button" class="wg-start-btn" onclick="AdminWalkthrough.start()">
              <i class='bx bx-play-circle'></i> Start Step-by-Step Tour
            </button>
          </div>
          <div class="wg-card">
          <h4><i class='bx bx-menu'></i> Menu</h4>
          <p>The floating navigation bar at the bottom of the screen. Use it to jump between sections.</p>
          <ul>
            <li>Quickly switch between Rooms and Reservation Lists.</li>
            <li>Tap <strong>Walkthrough</strong> anytime to come back to this guide.</li>
          </ul>
        </div>
          <div class="wg-card">
            <h4><i class="bx bx-door-open"></i> Rooms</h4>
            <p>Your landing page. Browse all meeting rooms.</p>
            <ul>
              <li>Search rooms by name, serial number, or floor.</li>
              <li>
                Use the filter options to view Available, Partially Occupied, or Fully Occupied rooms.
                <ul>
                  <li>Available - Completely open with no meetings booked today</li>
                  <li>Partially Occupied - Has meetings booked, but not for the fully Booked</li>
                  <li>Fully Occupied - All time slots booked today</li>
                </ul>
              </li>
              <li>
                Device Status
                <ul>
                  <li>Active - Device is turned on</li>
                  <li>Inactive - Device is powered off</li>
                </ul>
              </li>
              <li>Click a room to view its details and book it.</li>
              <li>Click the <i class="bx bx-info-circle"></i> info icon on a room card to open its full details and booking calendar.</li>
              <li>When Already in Calendar Select any current or future date on the calendar to be directed straight to the reservation page. Previous dates cannot be selected</li>
            </ul>
          </div>
          <div class="wg-card">
            <h4><i class='bx bx-calendar'></i> Reservations</h4>
            <p>View and manage your own reservations.</p>
            <ul>
              <li>Filter your reservations by ongoing, upcoming, done, or cancelled.</li>
              <li>Search by floor name or date.</li>
            <li>There are 3 icons: <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> for **Info** (to view room details), <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x-icon lucide-calendar-x"><path d="M8 2v3"/><path d="M16 2v3"/><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="m14 13-4 4"/><path d="m10 13 4 4"/></svg> for **Cancellation**, and <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up-icon lucide-calendar-arrow-up"><path d="m14 17 4-4 4 4"/><path d="M16 2v3"/><path d="M18 21v-8"/><path d="M21 10.343V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h9"/><path d="M3 9h18"/><path d="M8 2v3"/></svg> for **Rescheduling or Updating** reservations. Please note that only the user who originally made the booking is allowed to cancel or update it.</li>
              <li>Filter the Reservation By weekly, Daily and Montly Reservations.</li>
            </ul>
          </div>
          <div class="wg-card">
            <h4><i class='bx bx-calendar-plus'></i> Book a Room</h4>
            <p>Reserve a room for a meeting.</p>
            <ul>
              <li>Open a room's details, then click a date on the Calendar tab, or click "Book Now" on a room card.</li>
              <li>Clicking a date takes you straight to Add Reservation for that room and date. Dates before today are disabled and can't be selected.</li>
              <li>Pick a time range and fill in the meeting details.</li>
              <li>Save to submit your reservation.</li>
            </ul>
          </div>
        </div>
      </section>
    </div>

  <script src="../assets/js/box_icons.js"></script>
  <script src="../assets/js/script.js"></script>
  <script src="../assets/js/boostrap.js"></script>
  <script src="../assets/js/walkthrough_tour.js?v=9"></script>;

  <?php
    include '../alert.php';
  ?>
</body>
</html>
