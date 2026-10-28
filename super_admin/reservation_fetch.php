<?php
include '../config.php';

// 1. SETTINGS & INPUTS
$items_per_page = 8;
$current_page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$filter_date    = isset($_GET['date']) ? $_GET['date'] : '';
$search_query   = isset($_GET['search']) ? trim($_GET['search']) : '';
$selected_statuses = isset($_GET['status']) ? $_GET['status'] : [];

$offset = max(0, ($current_page - 1) * $items_per_page);
$today_date = date('Y-m-d');
$now_time = date('H:i:s');

// Safe date parsing helper
function self_safe_date($date_str, $fallback_str) {
    if (!empty($date_str) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_str)) {
        try {
            return new DateTime($date_str);
        } catch (Exception $e) {
            // fall through
        }
    }
    return new DateTime($fallback_str);
}

// 2. BUILD THE SQL (Weekly Focus)
$where_clauses = ["1=1"];
$params = [];
$types = "";

// Handle search query for Fullname
if (!empty($search_query)) {
    $where_clauses[] = "b.fullname LIKE ?";
    $params[] = "%" . $search_query . "%";
    $types .= "s";
}

// Handle ISO week or fallback to anchor week containing the date/today
if (!empty($filter_date) && preg_match('/^(\d{4})-W(\d{2})$/', $filter_date, $m)) {
    $week_start = new DateTime();
    $week_start->setISODate((int)$m[1], (int)$m[2]);
} else {
    $anchor = self_safe_date($filter_date, $today_date);
    $dow = (int)$anchor->format('N'); // 1=Mon ... 7=Sun
    $week_start = clone $anchor;
    $week_start->modify('-' . ($dow - 1) . ' days');
}

$week_end = clone $week_start;
$week_end->modify('+6 days');

$range_start = $week_start->format('Y-m-d');
$range_end   = $week_end->format('Y-m-d');

$where_clauses[] = "b.start_date BETWEEN ? AND ?";
array_push($params, $range_start, $range_end);
$types .= "ss";

$display_date_label = $week_start->format('M d') . ' - ' . $week_end->format('M d, Y');
$where_sql = implode(" AND ", $where_clauses);

// 3. FETCH MATCHING DATA FOR THE WEEK
$query_sql = "SELECT b.*, r.room_name, r.image 
              FROM booking b 
              JOIN rooms r ON b.room_id = r.room_id 
              WHERE $where_sql 
              ORDER BY b.start_date ASC, b.start_time ASC";

$stmt_data = $conn2->prepare($query_sql);
if (!empty($types)) {
    $stmt_data->bind_param($types, ...$params);
}
$stmt_data->execute();
$all_results = $stmt_data->get_result();

$filtered_rooms = []; 

// 4. THE CALCULATION & STATUS FILTERING LOOP
while ($row = $all_results->fetch_assoc()) {
    $row_start = $row['start_time'];
    $row_end = $row['end_time'];
    $row_date = $row['start_date'];
    $row_status = $row['status']; 
    $current_label = "Unknown";

    // LOGIC CALCULATION
    if ($row_status === "Cancelled") {
        $current_label = "Cancelled";
    } 
    elseif ($row_date < $today_date || ($row_date === $today_date && $now_time > $row_end)) {
        $current_label = "Done";
    } 
    elseif ($row_date === $today_date && $now_time >= $row_start && $now_time <= $row_end) {
        $current_label = "On Going";
    } 
    elseif ($row_date > $today_date || ($row_date === $today_date && $now_time < $row_start)) {
        $current_label = "Upcoming";
    }

    // Apply Checkbox Status Filter if any statuses are selected
    if (!empty($selected_statuses) && !in_array($current_label, $selected_statuses)) {
        continue; // Skip items that don't match selected checkboxes
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

// 5. MANUAL PAGINATION
$total_rows = count($filtered_rooms);
$total_pages = ceil($total_rows / $items_per_page);
$display_data = array_slice($filtered_rooms, $offset, $items_per_page);

// 6. OUTPUT UI
echo "<p class='fw-bold mt-2'>RESERVATIONS FOR (" . strtoupper($display_date_label) . ")</p>";

if ($total_rows > 0) {

    foreach ($display_data as $row) {
        $room_name = htmlspecialchars($row['room_name']);
        $user_name_short = (strlen($row['fullname']) > 22 ? substr($row['fullname'], 0, 22) . '...' : $row['fullname']);

      echo '<div class="col-xl-3 col-lg-4  col-md-6 col-sm-12 mb-3">
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
                        <a href="room_info.php?room_id='.$row['room_id'].'&location_back=reservations.php"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Room Info" class="btn btn_view w-100 d-flex align-items-center justify-content-center ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                       
                        </a>';
                        
                        // Show cancel button if Upcoming and owned by the current user
                        if ($row['calc_label'] == 'Upcoming') {
                            if ($_SESSION['User_Id'] == $row['user_id_added']) {
                                echo '<a href="reservation_cancel.php?booking_id='.$row['booking_id'].'&location_back=reservations.php" class="btn btn_cancelled w-100 d-flex align-items-center justify-content-center gap-1"data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancel Reservation"  >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x-icon lucide-calendar-x"><path d="M8 2v3"/><path d="M16 2v3"/><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="m14 13-4 4"/><path d="m10 13 4 4"/></svg>
                                    
                                </a>
                                <a href="update_reservation.php?booking_id='.$row['booking_id'].'&location_back=reservations.php" class="btn btn_resched  w-100 d-flex align-items-center justify-content-center gap-1"data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reschedule Reservation / Update"  >
                                   <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up-icon lucide-calendar-arrow-up"><path d="m14 17 4-4 4 4"/><path d="M16 2v3"/><path d="M18 21v-8"/><path d="M21 10.343V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h9"/><path d="M3 9h18"/><path d="M8 2v3"/></svg>
                                    
                                </a>';
                            }
                        }

echo '      </div>
                </div>
              </div>';
    }
 
} else {
    echo "<div class='col-12 text-center py-5'><i class='bx bx-calendar-x text-muted' style='font-size: 3rem;'></i><p class='mt-2 text-secondary'>No reservations found for this week.</p></div>";
}

echo "|||"; 

// Pagination
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