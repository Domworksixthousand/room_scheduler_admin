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
            <td>
                <p class='small mb-1 text-center'>
                     " . date('M d, Y', strtotime($row['start_date'])) . "
                </p>
            </td>
            <td><small>" . date('h:i A', strtotime($row['start_time'] ?? '')) . " to " . date('h:i A', strtotime($row['end_time'] ?? '')) . "</small></td>
            <td>" . htmlspecialchars($row['meeting_title']) . "</td>
            <td>" . htmlspecialchars($row['fullname']) . "</td>
            <td>" . htmlspecialchars($row['room_name']) . "</td>
            <td>" . htmlspecialchars($row['floor_name']) . "</td>
            <td>" . ($row['cancelled_at'] ? htmlspecialchars($row['cancelled_at']) : 'Not cancelled') . "</td>
            <td><span class='badge $status_class'>" . htmlspecialchars($row['status']) . "</span></td>
        </tr>";
    }
} else {
      echo "<tr >
                <td colspan='8' class='text-center py-4 '>
                    <div class=' d-flex flex-column text-muted justify-content-center align-items-center'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='35' height='35'  viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-folder-clock-icon lucide-folder-clock'><path d='M16 14v2.2l1.6 1'/><path d='M7 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2'/><circle cx='16' cy='16' r='6'/></svg>
                        No results found.
                    </div>
                </td>
            </tr>";
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
    echo '<ul class="pagination pagination-sm mb-0">';
    
    // Previous Link
    $prev_disabled = ($page <= 1) ? 'disabled' : '';
    $prev_val = max(1, $page - 1);
    echo "<li class='page-item $prev_disabled'><a class='page-link page-link-ajax' href='#' data-page='$prev_val'>Previous</a></li>";

    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        echo "<li class='page-item $active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
    }

    // Next Link
    $next_disabled = ($page >= $total_pages) ? 'disabled' : '';
    $next_val = min($total_pages, $page + 1);
    echo "<li class='page-item $next_disabled'><a class='page-link page-link-ajax' href='#' data-page='$next_val'>Next</a></li>";
    
    echo '</ul>';
}
?>