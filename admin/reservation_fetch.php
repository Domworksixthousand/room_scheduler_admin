<?php
include '../config.php';

// 1. SETTINGS & PAGINATION
$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$selected_floors = isset($_GET['floors']) ? $_GET['floors'] : [];
$selected_status = isset($_GET['status']) ? $_GET['status'] : [];

$offset = max(0, ($current_page - 1) * $items_per_page);
$search_param = "%" . $search_query . "%";

// 2. BUILD THE DYNAMIC SQL (Base: Rooms)
$where_clauses = ["(r.room_name LIKE ? OR r.description LIKE ? OR f.floor_name LIKE ?)"];
$params = [$search_param, $search_param, $search_param];
$types = "sss";

if (!empty($selected_floors)) {
    $placeholders = implode(',', array_fill(0, count($selected_floors), '?'));
    $where_clauses[] = "r.floor_id IN ($placeholders)";
    foreach ($selected_floors as $f_id) {
        $params[] = (int)$f_id;
        $types .= "i";
    }
}

$where_sql = implode(" AND ", $where_clauses);

// Fetch rooms to calculate real-time status
$sql = "SELECT r.*, f.floor_name 
        FROM `rooms` r
        LEFT JOIN `floors` f ON r.floor_id = f.floor_id 
        WHERE $where_sql";

$stmt = $conn2->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$all_rooms = $stmt->get_result();

$filtered_data = [];
$today = date('Y-m-d');
$current_time = date('H:i:s');

// 3. CALCULATION LOGIC (Per Room)
while ($row = $all_rooms->fetch_assoc()) {
    $room_id = $row['room_id'];
    $total_booked_minutes = 0; 
    $is_currently_occupied = false;
    $latest_booking_user = "No booking today";

    // Kunin ang lahat ng active bookings para sa kwartong ito ngayong araw
    $status_sql = "SELECT b.start_time, b.end_time, b.fullname, b.status 
                   FROM `booking` b 
                   WHERE b.room_id = ? AND b.start_date <= ? AND b.end_date >= ? AND b.status = 'Occupied'";
    
    $stmt_status = $conn2->prepare($status_sql);
    $stmt_status->bind_param("sss", $room_id, $today, $today);
    $stmt_status->execute();
    $res_status = $stmt_status->get_result();

    while ($b = $res_status->fetch_assoc()) {
        // Check kung occupied sa oras na ito
        if ($current_time >= $b['start_time'] && $current_time <= $b['end_time']) {
            $is_currently_occupied = true;
            $latest_booking_user = $b['fullname'];
        }
        
        // Calculate total minutes
        $start = strtotime($b['start_time']);
        $end = strtotime($b['end_time']);
        if ($end > $start) {
            $total_booked_minutes += ($end - $start) / 60;
        }
    }
    $stmt_status->close();

    // Determine Label
    if ($is_currently_occupied || $total_booked_minutes >= 540) {
        $current_label = "Fully Occupied";
    } else if ($total_booked_minutes > 0) {
        $current_label = "Partially Occupied";
    } else {
        $current_label = "Available";
    }

    // 4. FILTER BY CALCULATED STATUS
    if (empty($selected_status) || in_array($current_label, $selected_status)) {
        $row['calc_label'] = $current_label;
        $row['current_user'] = $latest_booking_user;
        $filtered_data[] = $row;
    }
}

// Manual Pagination
$total_rows = count($filtered_data);
$total_pages = ceil($total_rows / $items_per_page);
$display_data = array_slice($filtered_data, $offset, $items_per_page);

// 5. OUTPUT UI
echo " <p class='fw-bold mt-2'>RESERVATION STATUS TODAY (".date('M d, Y').")</p>";

if (!empty($display_data)) {
    foreach ($display_data as $row) {
        $status_label = $row['calc_label'];
        
        // Badge Logic
        if ($status_label == "Fully Occupied") {
            $badge_class = "bg-danger";
        } elseif ($status_label == "Partially Occupied") {
            $badge_class = "bg-warning text-dark";
        } else {
            $badge_class = "bg-success";
        }

        $room_name = htmlspecialchars($row['room_name']);
        $room_name_short = (strlen($room_name) > 22 ? substr($room_name, 0, 22) . '...' : $room_name);

        echo '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="overflow-hidden position-relative">
                        <img src="../assets/uploads/'.$row['image'].'" class="card-img-top" alt="'.$room_name.'" style="height: 180px; object-fit: cover;">
                        <span class="badge position-absolute top-0 end-0 m-2 '.$badge_class.' p-2">'.$status_label.'</span>
                        <div class="buttton_actions">
                             <a href="room_info.php?room_id='.$row['room_id'].'" data-bs-toggle="tooltip" title="Info" class="btn btn-info btn-sm text-light"><i class="bx bx-info-circle"></i></a>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1">'.$room_name_short.'</h6>
                        <p class="card-text text-secondary small mb-1">
                            <i class="bx bx-user"></i> '.htmlspecialchars($row['current_user']).'
                        </p>
                        <p class="card-text small mb-1 text-muted">
                            <i class="bx bx-layer"></i> '.htmlspecialchars($row['floor_name']).'
                        </p>
                        <p class="card-text small mb-1 text-muted">
                            <i class="bx bx-time"></i> Total: '.$total_booked_minutes.' mins booked
                        </p>
                        <div class="mt-3">
                             <a href="reservation_add.php?room_id='.$row['room_id'].'" class="btn btn-primary btn-sm w-100 '.($status_label == "Fully Occupied" ? "disabled" : "").'">
                                <i class="bx bx-plus-circle"></i> Book Room
                             </a>
                        </div>
                    </div>
                </div>
              </div>';
    }
} else {
    echo "<div class='col-12 text-center py-5'><i class='bx bx-search-alt text-muted' style='font-size: 3rem;'></i><p class='mt-2 text-secondary'>No data found.</p></div>";
}

echo "|||"; // Splitter

// 6. PAGINATION UI
if ($total_pages > 1) {
    echo '<nav aria-label="Page navigation"><ul class="pagination pagination-sm mb-0">';
    if ($current_page > 1) {
        $prev = $current_page - 1;
        echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='$prev'>Previous</a></li>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $current_page) ? ' active' : '';
        echo "<li class='page-item$active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
    }
    if ($current_page < $total_pages) {
        $next = $current_page + 1;
        echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='$next'>Next</a></li>";
    }
    echo '</ul></nav>';
}
?>