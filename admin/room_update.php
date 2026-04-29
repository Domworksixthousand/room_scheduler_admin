<?php
    include 'rooms.php'; 
    if(isset($_GET['room_id'])){
        $room_id = htmlspecialchars($_GET['room_id'] ?? '');
    }

    $get_rooms = $conn2->prepare("SELECT * FROM `rooms`  LEFT JOIN `floors` ON rooms.floor_id = floors.floor_id WHERE `room_id` = ?");
    $get_rooms->bind_param("s",$room_id);
    $get_rooms->execute();
    $result_rooms = $get_rooms->get_result();
    if($result_rooms->num_rows>0){
    while($row_get = mysqli_fetch_assoc($result_rooms)){
        $room_name = htmlspecialchars($row_get['room_name'] ?? '');
        $floor_name  = htmlspecialchars($row_get['floor_name'] ?? '');
        $serial_number = htmlspecialchars($row_get['serial_number'] ?? '');
        $floor_id = htmlspecialchars($row_get['floor_id'] ?? '');
        $capacity = htmlspecialchars($row_get['capacity'] ?? '');
        $description = htmlspecialchars($row_get['description'] ?? '');
        }
    }


?>

<main >
    <section class="room_update_section" >
       <form action="../functions.php" method="POST">
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between">
                        <p class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Update Room</p>
                        <a href="rooms.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_id); ?>" readonly>
                        <div class="inner_body shadow-lg">
                            <div class="mb-0 row">
                                <div class="col-lg-6  col-md-6 col-sm-12 mb-3">
                                    <label for="room_name" class="form-label">Room Name</label>
                                    <input type="text" class="form-control uppercase_function" name="room_name" value="<?php echo htmlspecialchars($room_name); ?>" placeholder="Enter Floor Name"  required>
                                </div>
                                <div class="col-lg-6  col-md-6 col-sm-12 mb-3">
                                    <label for="serial_numnber" class="form-label">Serial Number</label>
                                    <input type="text" class="form-control" name="serial_number" value="<?php echo htmlspecialchars($serial_number); ?>" placeholder="Enter Serial Number "  required>
                                </div>
                            </div>
                            <div class="mb-0 row">
                                <div class="col-lg-6  col-md-6 col-sm-12 mb-3">
                                    <label for="floor" class="form-label">Floor</label>
                                    <select name="floor" id="floor" class="form-control">
                                        <option value="<?php echo  htmlspecialchars($floor_id) ?? 'Select Floor'; ?>"><?php echo  htmlspecialchars($floor_name) ?? 'Select Floor'; ?></option>
                                        <?php
                                            $floor_get = $conn2->prepare("SELECT * FROM `floors`");
                                            $floor_get->execute();
                                            $get_floor = $floor_get->get_result();
                                            if($get_floor->num_rows>0){
                                                while($row_floor = $get_floor->fetch_assoc()){
                                                    $floor_name = htmlspecialchars($row_floor['floor_name'] ?? '');
                                                    $floor_id = htmlspecialchars($row_floor['floor_id'] ?? '');
                                                    
                                                    echo "<option value='$floor_id'>$floor_name</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-6  col-md-6 col-sm-12 mb-3">
                                    <label for="capacity" class="form-label">Capacity</label>
                                    <input type="text" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($capacity); ?>" placeholder="Enter Capacity"    required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description / Amenities</label>
                                <textarea name="description" class="form-control" id="description" placeholder="Type Your Text Here.."><?php echo htmlspecialchars($description); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                      <button type="submit" name="room_update" class="btn btn_update"> Update <i class='bx bx-edit '></i></button>
                    </div>
                    </div>
                </div>
            </div>
       </form>
    </section>
</main>
