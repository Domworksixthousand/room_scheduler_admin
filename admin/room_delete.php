<?php
include 'rooms.php';
    $room_id = htmlspecialchars($_GET['room_id'] ?? '');

    if ($room_id) {
        $get = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_id` = ?");
        $get->bind_param("s", $room_id);
        $get->execute();
        $result_data = $get->get_result();

        if ($result_data->num_rows > 0) {
            $row_get = $result_data->fetch_assoc();
            $room_name = htmlspecialchars($row_get['room_name'] ?? '');
        }
    }

?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_id); ?>">
    <input type="hidden" name="delete_room" value="1">
</form>

<script>
// Auto-show alert on page load
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "warning",
        title: "Important!",
        text: "Are you sure to Delete <?php echo htmlspecialchars($room_name); ?>, All Data will Be Deleted?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
   
            document.getElementById('assignForm').submit();
        } else {
  
            location.href = 'rooms.php';
        }
    });
});
</script>