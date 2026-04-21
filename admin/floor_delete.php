<?php
include 'floors.php';
    $floor_id = htmlspecialchars($_GET['floor_id'] ?? '');

    if ($floor_id) {
        $get = $conn2->prepare("SELECT * FROM `floors` WHERE `floor_id` = ?");
        $get->bind_param("s", $floor_id);
        $get->execute();
        $result_data = $get->get_result();

        if ($result_data->num_rows > 0) {
            $row_get = $result_data->fetch_assoc();
            $floor_name = htmlspecialchars($row_get['floor_name'] ?? '');
        }
    }

?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="floor_id" value="<?php echo htmlspecialchars($floor_id); ?>">
    <input type="hidden" name="delete_floor" value="1">
</form>

<script>
// Auto-show alert on page load
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "warning",
        title: "Important!",
        text: "Are you sure to Delete <?php echo htmlspecialchars($floor_name); ?>, All Data will Be Deleted?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
   
            document.getElementById('assignForm').submit();
        } else {
  
            location.href = 'floors.php';
        }
    });
});
</script>