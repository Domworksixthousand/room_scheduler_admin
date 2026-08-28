<?php
$check_active_status = $conn2->prepare("
    SELECT r.room_id, r.status, r.serial_number, r.tablet_status, h.room_id, h.date_saved, h.time_expired, h.`update`
    FROM `rooms` AS r
    LEFT JOIN `heartbeat` AS h ON r.room_id = h.room_id
");

if ($check_active_status) {
    $check_active_status->execute();
    $result_status = $check_active_status->get_result();

    if ($result_status->num_rows > 0) {
        while ($row_status = $result_status->fetch_assoc()) {

            // Isasama natin ang r.tablet_status === 'Active' sa ating kondisyon
            if (
                $row_status['tablet_status'] === 'Active' && 
                !empty($row_status['date_saved']) &&
                !empty($row_status['time_expired']) &&
                $row_status['date_saved'] <= $datetoday &&
                $row_status['time_expired'] < $timetoday2 
            ) {
                $new_status = "Inactive";

                $update_status = $conn2->prepare("UPDATE `rooms` SET `tablet_status` = ? WHERE `serial_number` = ?");

                if ($update_status) {
                    $update_status->bind_param("ss", $new_status, $row_status['serial_number']);
                    $update_status->execute();
                    $update_status->close();
                }
            }
        }
    }
    $check_active_status->close();
}