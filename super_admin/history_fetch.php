<?php
include '../config.php';

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_param = "%$search%";



$sql = "SELECT b.*, r.room_name, flo.floor_name 
        FROM `booking` b
        INNER JOIN `rooms` r ON b.room_id = r.room_id
        LEFT JOIN `floors` flo ON r.floor_id = flo.floor_id 
        WHERE (
            r.room_name LIKE ? 
            OR b.fullname LIKE ? 
            OR b.meeting_title LIKE ? 
            OR b.start_date LIKE ? 
            OR b.end_date LIKE ?   
        )
        AND b.end_date < ?
        ORDER BY b.start_date DESC, b.start_time DESC
        LIMIT ? OFFSET ?";

$stmt = $conn2->prepare($sql);
$stmt->bind_param("ssssssii", $search_param, $search_param, $search_param, $search_param, $search_param, $datetoday, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $status_class = ($row['status'] == 'Occupied') ? 'bg-primary' : (($row['status'] == 'Done') ? 'bg-success' : 'bg-danger');
        
        echo "<tr>
                <td><small>{$row['start_date']} to {$row['end_date']}</small></td>
                <td><small>" . date('h:i A', strtotime($row['start_time'] ?? '')) . " to " . date('h:i A', strtotime($row['end_time'] ?? '')) . "</small></td>
                <td>" . htmlspecialchars($row['meeting_title']) . "</td>
                <td>" . htmlspecialchars($row['fullname']) . "</td>
                <td>{$row['room_name']}</td>
                <td>{$row['floor_name']}</td>
                <td>" . ($row['cancelled_at'] ? htmlspecialchars($row['cancelled_at']) : 'Not cancelled') . "</td>
                <td><span class='badge $status_class'>{$row['status']}</span></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8' class='text-center py-4 text-muted'>No results found.</td></tr>";
}

echo "|||"; // AJAX Splitter


$count_sql = "SELECT COUNT(*) as total FROM `booking` b 
              INNER JOIN `rooms` r ON b.room_id = r.room_id 
              WHERE (r.room_name LIKE ? OR b.fullname LIKE ? OR b.meeting_title LIKE ? OR b.start_date LIKE ? OR b.end_date LIKE ?) 
              AND b.end_date < ?";

$c_stmt = $conn2->prepare($count_sql);
$c_stmt->bind_param("ssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $datetoday);
$c_stmt->execute();
$total_rows = $c_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);


if ($total_pages > 1) {
    // Previous Button
    if ($page > 1) {
        $prev_page = $page - 1;
        echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='$prev_page'>Previous</a></li>";
    }


    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $page) ? ' active' : '';
        echo "<li class='page-item$active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
    }


    if ($page < $total_pages) {
        $next_page = $page + 1;
        echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='$next_page'>Next</a></li>";
    }
}
?>