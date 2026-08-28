<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php';

//  SETTINGS & INPUTS
$items_per_page = 8;
$current_page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$filter_month   = isset($_GET['month']) ? $_GET['month'] : ''; // expected format: YYYY-MM
$search_query   = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter  = isset($_GET['status']) ? $_GET['status'] : [];

$offset = max(0, ($current_page - 1) * $items_per_page);
$today_date = date('Y-m-d');
$now_time = date('H:i:s');

// Safe month parsing helper -> returns DateTime set to the 1st of that month
function self_safe_month($month_str, $fallback_month_str) {
    if (!empty($month_str) && preg_match('/^\d{4}-\d{2}$/', $month_str)) {
        try {
            return new DateTime($month_str . '-01');
        } catch (Exception $e) {
            // fall through
        }
    }
    return new DateTime($fallback_month_str . '-01');
}

// 2. BUILD THE SQL (Monthly Focus: overlap with the whole selected month)
$where_clauses = ["1=1"];
$params = [];
$types = "";

// Kunin ang piniling buwan (o ngayong buwan kung wala)
$current_month_fallback = date('Y-m');
$anchor = self_safe_month($filter_month, $current_month_fallback);

$month_start = $anchor->format('Y-m-01');
$month_end   = $anchor->format('Y-m-t'); // 't' = last day of month

// Overlap test: booking overlaps ANY day within the selected month
$where_clauses[] = "b.start_date <= ? AND b.end_date >= ?";
array_push($params, $month_end, $month_start);
$types .= "ss";

// Handle Search Query (Fullname only)
if (!empty($search_query)) {
    $where_clauses[] = "b.fullname LIKE ?";
    $params[] = "%" . $search_query . "%";
    $types .= "s";
}

$display_month_label = strtoupper($anchor->format('F Y'));
$where_sql = implode(" AND ", $where_clauses);

// FETCH MATCHING DATA FOR THE MONTH

// --- Connection sanity check ---
if (!isset($conn2) || !($conn2 instanceof mysqli)) {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn2 = $conn; // fallback alias
    } else {
        die("DB connection not found. Check config.php for the correct connection variable name.|||");
    }
}

$query_sql = "SELECT b.*, r.room_name, r.image
              FROM booking b
              JOIN rooms r ON b.room_id = r.room_id
              WHERE $where_sql
              ORDER BY b.start_date ASC, b.start_time ASC";

$stmt_data = $conn2->prepare($query_sql);

if (!$stmt_data) {
    die("SQL Prepare failed: " . htmlspecialchars($conn2->error) . "|||");
}

if (!empty($types)) {
    $stmt_data->bind_param($types, ...$params);
}

if (!$stmt_data->execute()) {
    die("SQL Execute failed: " . htmlspecialchars($stmt_data->error) . "|||");
}

$all_results = $stmt_data->get_result();

$filtered_rooms = [];

//  THE CALCULATION LOOP & STATUS FILTERING
while ($row = $all_results->fetch_assoc()) {
    $row_start = $row['start_time'];
    $row_end = $row['end_time'];
    $row_start_date = $row['start_date'];
    $row_end_date = $row['end_date'];
    $row_status = $row['status'];
    $user_added = $row['user_id_added'];
    $current_label = "Unknown";

    // LOGIC CALCULATION (based on actual "today", same as daily view)
    if ($row_status === "Cancelled") {
        $current_label = "Cancelled";
    }
    elseif ($row_end_date < $today_date || ($row_end_date === $today_date && $now_time > $row_end)) {
        $current_label = "Done";
    }
    elseif (
        ($row_start_date < $today_date || ($row_start_date === $today_date && $now_time >= $row_start))
        &&
        ($row_end_date > $today_date || ($row_end_date === $today_date && $now_time <= $row_end))
    ) {
        $current_label = "On Going";
    }
    else {
        $current_label = "Upcoming";
    }

    // Apply Checkbox Status Filter if any statuses are selected via AJAX
    if (!empty($status_filter) && is_array($status_filter)) {
        if (!in_array($current_label, $status_filter)) {
            continue;
        }
    }

    // Badge Colors
    $badge_color = 'bg-primary';
    if ($current_label === "On Going") $badge_color = 'bg-light text-success border border-success border-2';
    if ($current_label === "Upcoming") $badge_color = 'bg-light text-secondary border border-secondary border-2';
    if ($current_label === "Cancelled") $badge_color = 'bg-light text-danger border border-danger border-2';
    if ($current_label === "Done") $badge_color = 'bg-light text-info border border-info border-2';

    $row['calc_label'] = $current_label;
    $row['dynamic_badge'] = $badge_color;
    $filtered_rooms[] = $row;
}

//  MANUAL PAGINATION
$total_rows = count($filtered_rooms);
$total_pages = ceil($total_rows / $items_per_page);
$display_data = array_slice($filtered_rooms, $offset, $items_per_page);

//  OUTPUT UI CARDS
echo "<p class='fw-bold mt-2'>RESERVATIONS FOR (" . $display_month_label . ")</p>";

if ($total_rows > 0) {

    foreach ($display_data as $row) {
        $room_name = htmlspecialchars($row['room_name']);
        $user_name_short = (strlen($row['fullname']) > 22 ? substr($row['fullname'], 0, 22) . '...' : $row['fullname']);

          echo '<div class="col-xl-3 col-lg-4   col-md-6 col-sm-12 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="overflow-hidden position-relative" >
                        <!-- Image -->
                        <img src="../assets/uploads/'.$row['image'].'" class="card-img-top " loading="lazy" >
                        
                        <!-- Gradient Overlay  -->
                        <div class="overlay-gradient position-absolute bottom-0 start-0 w-100 h-50" >
                        </div>

                        <!-- Status Badge  -->
                        <span class="badge position-absolute rounded-pill top-0 end-0 m-2 '.$row['dynamic_badge'].' p-2">
                            '.$row['calc_label'].'
                        </span>
                        
                        <!-- Room Name -->
                        <h6 class="card-title position-absolute fw-bold mb-1 bottom-0 start-0 m-2 text-white" >
                            '.$room_name.'
                        </h6>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <p class="meeting_title">'.$row['meeting_title'].'</p>

                        <p class="small mb-1 text-secondary d-flex align-items-center gap-2">
                            <i class="bx bx-calendar fs-5"></i> '.date('M d, Y', strtotime($row['start_date'])).'
                        </p>
                        <p class="small mb-1 text-secondary d-flex align-items-center gap-2">
                            <i class="bx bx-time-five fs-5"></i> '.date('h:i A', strtotime($row['start_time'])).' - '.date('h:i A', strtotime($row['end_time'])).'
                        </p>
                        <p class="small mb-1 text-secondary d-flex align-items-center gap-2">
                            <i class="bx bx-user-circle fs-5"></i> '.htmlspecialchars($user_name_short).'
                        </p>
                    </div>
                    <div class="action_butt mt-auto  gap-2  ">
                        <a href="room_info.php?room_id='.$row['room_id'].'&location_back=reservation_montly.php"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Room Info" class="btn btn_view w-100 d-flex align-items-center justify-content-center ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                       
                        </a>';
                        
                        // Show cancel button if Upcoming and owned by the current user
                        if ($row['calc_label'] == 'Upcoming') {
                            if ($_SESSION['User_Id'] == $row['user_id_added']) {
                                echo '<a href="reservation_cancel.php?booking_id='.$row['booking_id'].'&location_back=reservation_montly.php" class="btn btn_cancelled w-100 d-flex align-items-center justify-content-center gap-1"data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancel Reservation"  >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x-icon lucide-calendar-x"><path d="M8 2v3"/><path d="M16 2v3"/><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="m14 13-4 4"/><path d="m10 13 4 4"/></svg>
                                  
                                </a>
                                <a href="update_reservation.php?booking_id='.$row['booking_id'].'&location_back=reservation_montly.php" class="btn btn_resched  w-100 d-flex align-items-center justify-content-center gap-1"data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reschedule Reservation / Update"  >
                                   <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up-icon lucide-calendar-arrow-up"><path d="m14 17 4-4 4 4"/><path d="M16 2v3"/><path d="M18 21v-8"/><path d="M21 10.343V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h9"/><path d="M3 9h18"/><path d="M8 2v3"/></svg>
                                  
                                </a>';
                            }
                        }

echo '          </div>
                </div>
              </div>';
    }

} else {
    echo "<div class='col-12 text-center py-5'><i class='bx bx-calendar-x text-muted' style='font-size: 3rem;'></i><p class='mt-2 text-secondary'>No reservations found for this month.</p></div>";
}

echo "|||";

//  OUTPUT PAGINATION NAV
if ($total_pages > 1) {
    echo '<ul class="pagination pagination-sm mb-0">';
    $prev_disabled = ($current_page <= 1) ? 'disabled' : '';
    $prev_val = max(1, $current_page - 1);
    echo "<li class='page-item $prev_disabled'><a class='page-link page-link-ajax' href='#' data-page='$prev_val'>Previous</a></li>";

    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $current_page) ? 'active' : '';
        echo "<li class='page-item $active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
    }

    $next_disabled = ($current_page >= $total_pages) ? 'disabled' : '';
    $next_val = min($total_pages, $current_page + 1);
    echo "<li class='page-item $next_disabled'><a class='page-link page-link-ajax' href='#' data-page='$next_val'>Next</a></li>";
    echo '</ul>';
}

?>

<script src="../assets/js/script.js"></script>