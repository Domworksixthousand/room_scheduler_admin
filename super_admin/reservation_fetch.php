<?php
include '../config.php';

// 1. SETTINGS & INPUTS
$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$selected_status = isset($_GET['status']) ? (array)$_GET['status'] : [];
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';

$offset = max(0, ($current_page - 1) * $items_per_page);
$today_date = date('Y-m-d');
$now_time = date('H:i:s');

// 2. BUILD THE SQL
$where_clauses = ["1=1"]; 
$params = [];
$types = "";

if (!empty($filter_date)) {
    $where_clauses[] = "b.start_date = ?";
    $params[] = $filter_date;
    $types .= "s";
} else if (empty($search_query)) {
    // Default view: Pakita ang mga bookings na active ngayong araw pataas
    $where_clauses[] = "b.start_date = ?";
    array_push($params, $today_date);
    $types .= "s";
}

if (!empty($search_query)) {
    $search_param = "%" . $search_query . "%";
    $where_clauses[] = "(r.room_name LIKE ? OR b.fullname LIKE ?)";
    array_push($params, $search_param, $search_param);
    $types .= "ss";
}

$where_sql = implode(" AND ", $where_clauses);

// 3. FETCH ALL MATCHING DATA
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

// 4. THE CALCULATION LOOP (Isang loop lang dapat)
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

    // Badge Colors
    $badge_color = 'bg-primary';
    if ($current_label === "On Going") $badge_color = 'bg-glasmorphism border border-success  border-2 ';
    if ($current_label === "Upcoming") $badge_color = 'bg-glasmorphism border border-secondary  border-2 ';
    if ($current_label === "Cancelled") $badge_color = 'bg-glasmorphism border border-danger  border-2 ';
   if ($current_label === "Done") $badge_color = 'bg-glasmorphism border border-info  border-2 ';

    // Filtering by Status (kung may pinili sa dropdown/filter)
    if (empty($selected_status) || in_array($current_label, $selected_status)) {
        $row['calc_label'] = $current_label;
        $row['dynamic_badge'] = $badge_color;
        $filtered_rooms[] = $row;
    }
}

// 5. MANUAL PAGINATION
/*
  <p class="small mb-1 text-secondary d-flex align-items-center gap-2">
                            <i class="bx bx-calendar fs-5"></i> '. date('M d, Y', strtotime($row['start_date'])) .'
                        </p>

*/
$total_rows = count($filtered_rooms);
$total_pages = ceil($total_rows / $items_per_page);
$display_data = array_slice($filtered_rooms, $offset, $items_per_page);

// 6. OUTPUT UI
$display_date_label = !empty($filter_date) ? date('M d, Y', strtotime($filter_date)) : date('M d, Y');
echo "<p class='fw-bold mt-2'>RESERVATIONS FOR (" . strtoupper($display_date_label) . ")</p>";

if ($total_rows > 0) {
    echo '<div class="row">';
    foreach ($display_data as $row) {
        $room_name = htmlspecialchars($row['room_name']);
        $user_name_short = (strlen($row['fullname']) > 22 ? substr($row['fullname'], 0, 22) . '...' : $row['fullname']);

        echo '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="overflow-hidden position-relative">
                        <img src="../assets/uploads/'.$row['image'].'" class="card-img-top" style="height: 160px; object-fit: cover;">
                        <span class="badge position-absolute rounded-pill top-0 end-0 m-2 '.$row['dynamic_badge'].' p-2">'.$row['calc_label'].'</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1 ">'.$room_name.'</h6>
                      
                        <p class="small mb-1 text-secondary d-flex align-items-center gap-2">
                            <i class="bx bx-time-five fs-5"></i> '.date('h:i A', strtotime($row['start_time'])).' - '.date('h:i A', strtotime($row['end_time'])).'
                        </p>
                        <p class="small mb-1 text-secondary d-flex align-items-center gap-2">
                            <i class="bx bx-user-circle fs-5"></i> '.htmlspecialchars($user_name_short).'
                        </p>
                    </div>
                    <div class="action_butt mt-auto d-flex">
                        <a href="room_info.php?room_id='.$row['room_id'].'&location_back=reservations.php" class="btn btn_view w-100 d-flex align-items-center  justify-content-center gap-1">
                            <i class="bx bx-info-circle"></i> Info
                        </a>';
        
                    // Button cancel pakita lang kung Upcoming 
                    if ($row['calc_label'] == 'Upcoming') {
                        echo '<a href="reservation_cancel.php?booking_id=' .$row['booking_id']. '" class="btn btn_cancelled w-100 d-flex align-items-center justify-content-center gap-1">
                                <i class="bx bx-trash"></i> Cancel
                            </a>';
                    }

                    echo '      </div>
                </div>
              </div>';  
    }
    echo '</div>';
} else {
    echo "<div class='col-12 text-center py-5'><i class='bx bx-calendar-x text-muted' style='font-size: 3rem;'></i><p class='mt-2 text-secondary'>No reservations found.</p></div>";
}
echo "|||"; 
// Pagination remains the same
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