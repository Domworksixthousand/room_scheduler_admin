<?php
include '../config.php';



$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$selected_floors = isset($_GET['floors']) ? $_GET['floors'] : [];
$selected_status = isset($_GET['status']) ? $_GET['status'] : [];

$offset = max(0, ($current_page - 1) * $items_per_page);
$search_param = "%" . $search_query . "%";

// nag filter san floor type ini pogi /// ang pogi mo talaga patrick
$where_clauses = ["(rooms.room_name LIKE ? OR rooms.serial_number LIKE ? OR floors.floor_name LIKE ? OR rooms.description LIKE ?)"];
$params = [$search_param, $search_param, $search_param, $search_param];
$types = "ssss";

if (!empty($selected_floors)) {
    $placeholders = implode(',', array_fill(0, count($selected_floors), '?'));
    $where_clauses[] = "rooms.floor_id IN ($placeholders)";
    foreach ($selected_floors as $f_id) {
        $params[] = (int)$f_id;
        $types .= "i";
    }
}

$where_sql = implode(" AND ", $where_clauses);
echo " <p class='fw-bold mt-2'>ROOM STATUS TODAY (".date('M d, Y').")</p>";
// PAG NAG MATCH ANG data hali sa filter na where clause
$data_sql = "SELECT rooms.*, floors.floor_name FROM `rooms` 
             LEFT JOIN `floors` ON rooms.floor_id = floors.floor_id 
             WHERE $where_sql";

$stmt_data = $conn2->prepare($data_sql);
$stmt_data->bind_param($types, ...$params);
$stmt_data->execute();
$all_results = $stmt_data->get_result();

$filtered_rooms = [];
$today = date('Y-m-d');
$current_time = date('H:i:s');
$status_filter = 'Occupied';



$today = date('Y-m-d');
$current_time = date('H:i:s');

while ($row = $all_results->fetch_assoc()) {
    $id = $row['room_id'];
    $is_currently_occupied = false;
    $total_booked_minutes = 0;

    //kuhaon an booking niyan
    $status_sql = "SELECT start_time, end_time FROM `booking` 
                   WHERE room_id = ? AND start_date = ? AND `status` = 'Occupied'";
    $stmt_status = $conn2->prepare($status_sql);
    $stmt_status->bind_param("ss", $id, $today);
    $stmt_status->execute();
    $res_status = $stmt_status->get_result();

    while ($b = $res_status->fetch_assoc()) {
        $start = $b['start_time'];
        $end = $b['end_time'];

        // ang end time is 11:00 pm
        $comparison_end = ($end == '00:00:00') ? '11:00:00' : $end;

        // check kun occipied na an oras pogi
        if ($current_time >= $start && $current_time <= $comparison_end) {
            $is_currently_occupied = true;
        }

        // calculate an minute pogi
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        
        // Handle overlap sa midnight para sa minutes calculation
        if ($end_ts <= $start_ts) {
            $end_ts += 86400; // Magdagdag ng 24 hours sa seconds
        }
        
        $total_booked_minutes += ($end_ts - $start_ts) / 60;
    }
    $stmt_status->close();


    // Magiging Fully Occupied kung:
    // - May gumagamit sa mismong oras na ito (e.g. 6pm to 12am at ngayon ay 8pm)
    // - O kaya naman ay puno na ang total minutes na in-allow mo (e.g. 540 mins)
    if ($is_currently_occupied || $total_booked_minutes >= 540) {
        $current_label = "Fully Occupied";
    } else if ($total_booked_minutes > 0) {
        $current_label = "Partially Occupied";
    } else {
        $current_label = "Available";
    }

    if (empty($selected_status) || in_array($current_label, $selected_status)) {
        $row['calc_label'] = $current_label;
        $filtered_rooms[] = $row;
    }
}

// Manual Pagination logic
$total_rooms = count($filtered_rooms);
$total_pages = ceil($total_rooms / $items_per_page);
$display_rooms = array_slice($filtered_rooms, $offset, $items_per_page);

// --- OUTPUT UI (Cards) ---
if (!empty($display_rooms)) {
    foreach ($display_rooms as $row) {
        $status_label = $row['calc_label'];
        $badge_class = ($status_label == "Fully Occupied") ? "bg-danger p-2" : (($status_label == "Partially Occupied") ? "bg-warning text-light p-2" : "bg-success p-2");
        $desc = htmlspecialchars($row['description'] ?? '');
        $room_name = htmlspecialchars($row['room_name'] ?? '');
        $short_desc = (strlen($desc) > 25 ? substr($desc, 0, 25) . '...' : $desc);
        $room_name_short = (strlen($room_name) > 22 ? substr($room_name, 0, 22) . '...' : $room_name);
        
        echo '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
    
                <div class="card border-0 shadow-sm h-100">
                    <div class="overflow-hidden position-relative">
                        <img src="../assets/uploads/'.$row['image'].'" class="card-img-top" alt="'.$room_name.'" style="height: 180px; object-fit: cover;">
                        <span class="badge position-absolute top-0 end-0 m-2 '.$badge_class.'">'.$status_label.'</span>
                        <div class="buttton_actions">
                            <a href="room_delete.php?room_id='.$row['room_id'].'"  data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Delete" class="btn btn-danger btn-sm '.($status_label != "Available" ? "d-none" : "").'"><i class="bx bx-trash"></i></a>
                            <a href="room_update.php?room_id='.$row['room_id'].'" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Update"  class="btn btn-primary btn-sm '.($status_label != "Available" ? "d-none" : "").'"><i class="bx bx-edit"></i></a>
                            <a href="room_info.php?room_id='.$row['room_id'].'" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Info" class="btn btn-info btn-sm text-light"><i class="bx bx-info-circle"></i></a>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1">'.$room_name_short.'</h6>
                        <p class="card-text text-secondary small mb-1"><i class="bx bx-star"></i> '.$short_desc.'</p>
                        <p class="card-text small mb-1 text-muted"><i class="bx bx-layer"></i> '.htmlspecialchars($row['floor_name']).'</p>
                        <div class="reservation_bottona mt-3">
                            <a href="reservation_add.php?room_id='.$row['room_id'].'" class="btn btn_reserve btn-sm w-100 '.($status_label == "Fully Occupied" ? "disabled" : "").'"><i class="bx bxs-calendar-check"></i> Book Now</a>
                        </div>
                    </div>
                </div>
              </div>';
    }
} else {
    echo "<div class='col-12 text-center py-5'><i class='bx bx-search-alt text-muted' style='font-size: 3rem;'></i><p class='mt-2 text-secondary'>No rooms found matching your filters.</p></div>";
}

echo "|||"; // Pagination Splitter

// --- CONSISTENT PAGINATION WITH PREV/NEXT ---
if ($total_pages > 1) {
    echo '<nav aria-label="Page navigation"><ul class="pagination pagination-sm mb-0 ">';
    
    // Previous Button
    if ($current_page > 1) {
        $prev_page = $current_page - 1;
        echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='$prev_page'>Previous</a></li>";
    } else {
        echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
    }

    // Page Numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $current_page) ? ' active' : '';
        echo "<li class='page-item$active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
    }

    // Next Button
    if ($current_page < $total_pages) {
        $next_page = $current_page + 1;
        echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='$next_page'>Next</a></li>";
    } else {
        echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
    }
    
    echo '</ul></nav>';
}
?>