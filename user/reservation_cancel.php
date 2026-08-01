<?php
include 'reservations.php';

$booking_id = $_GET['booking_id'] ?? '';


if ($booking_id) {
    $get = $conn2->prepare("SELECT `meeting_title` FROM `booking` WHERE `booking_id` = ?");
    $get->bind_param("s", $booking_id);
    $get->execute();
    $result_data = $get->get_result();

    if ($row_get = $result_data->fetch_assoc()) {
        $meeting_title = $row_get['meeting_title'];
    } else {
        header("Location: reservations.php");
        exit;
    }
}
?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
    <input type="hidden" name="cancel_booking" value="1">
    <input type="hidden" name="cancelled_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
</form>

<script>
window.addEventListener('DOMContentLoaded', function() {
    // 2. Safely encode the name for JS
    const meetingTitle = <?php echo json_encode($meeting_title); ?>;
    
    CoolAlert.show({
        icon: "warning",
        title: "Confirm Cancellation",
        text: "Are you sure you want to cancel " + meetingTitle + "?",
        confirmButtonText: "Yes, Cancel it",
        showCancelButton: true,
        cancelButtonText: "No, Go Back"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('assignForm').submit();
        } else {
            location.href = 'reservations.php';
        }
    });
});
</script>