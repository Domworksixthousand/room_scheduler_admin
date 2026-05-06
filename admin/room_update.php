<?php
    include 'rooms.php'; 
    if(isset($_GET['room_id'])){
        $room_id = htmlspecialchars($_GET['room_id'] ?? '');
    }

    
$img_filename = '';
$session_prefix = '';


if (empty($_SESSION['trash_img'])) {
    $_SESSION['new_img_session'] = "";
}


$has_session_img = !empty($_SESSION['trash_img']) || !empty($_SESSION['new_img_session']);

if ($has_session_img) {

    $trash_val = $_SESSION['trash_img'] ?? '';
    $parts = explode('_', $trash_val);
    $session_prefix = $parts[0] ?? ''; 

  
    if ($session_prefix === ($admin_id ?? '')) {
        $img_filename = !empty($_SESSION['trash_img']) ? $_SESSION['trash_img'] : ($_SESSION['new_img_session'] ?? '');
    }
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
        $image = $row_get['image'] ;
        }
    }

?>

<main >
    <section class="room_update_section" >
       <form action="../functions.php" method="POST" enctype="multipart/form-data">
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
                            <div class="mb-3 ">
                                <label class="form-label fw-bold">Upload Room Image</label>
                                <div id="dropZone" class="upload-box border rounded p-4 mb-2 d-flex justify-content-center align-items-center flex-column" 
                                    style="cursor: pointer; border-style: dashed !important; background:#f0f0f0; min-height: 150px;"
                                    onclick="document.getElementById('imageInput2').click();">
                                  <i class='bx bx-image-add' id="uploadIcon2" 
                                    style="font-size: 3rem; color:#7d7f81; display: <?php echo (!empty($image)) ? 'none' : 'block'; ?>;">
                                    </i>
                                    <img id="previewImage2" 
                                        src="../assets/uploads/<?php echo htmlspecialchars($image); ?>" 
                                        style="max-width: 100%; max-height: 150px; display: <?=  '../assets/uploads/' .  $image  ? 'block' : 'none' ?>; margin: 0 auto;">
                                    
                                    <p class="text-muted small mb-0 mt-2">Click to change image</p>
                            </div>
                            <input type="file" id="imageInput2" accept=".jpg, .jpeg" name="image" style="display: none;">
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
