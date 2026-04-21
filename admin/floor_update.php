<?php
include 'floors.php';
    $floor_id = htmlspecialchars($_GET['floor_id'] ?? '');

    if ($floor_id) {
        $get = $conn2->prepare("SELECT * FROM `floors` WHERE `floor_id` = ?");
        $get->bind_param("i", $floor_id);
        $get->execute();
        $result_data = $get->get_result();

        if ($result_data->num_rows > 0) {
            $row_get = $result_data->fetch_assoc();
            $floor_name = htmlspecialchars($row_get['floor_name'] ?? '');
            $rooms_number = htmlspecialchars($row_get['rooms_number'] ?? '');
        }
    }

?>


<main >
    <section class="floor_update_section" >
       <form action="../functions.php" method="POST">
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                    <div class="modal-header border-0">
                        <p class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Update Floors</p>
                        <button type="button" onclick="location.href='floors.php'"   class="btn btn-close  me-2 "  data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="floor_id" value="<?php echo htmlspecialchars($floor_id);  ?>" readonly>
                        <div class="mb-3">
                            <label for="floor_name" class="form-label">Floor Name</label>
                            <input type="text" class="form-control" id="floor_name" name="floor_name" value="<?php echo htmlspecialchars($floor_name); ?>" placeholder="Enter Floor Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="number_of_rooms" class="form-label">Number of Rooms</label>
                            <input type="text" class="form-control" name="number_of_rooms" value="<?php echo htmlspecialchars($rooms_number); ?>" placeholder="Enter Number of Rooms" id="number_of_rooms" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                      <button type="submit" name="floors_update" class="btn btn_update"> Update</button>
                    </div>
                    </div>
                </div>
            </div>
       </form>
    </section>
</main>
