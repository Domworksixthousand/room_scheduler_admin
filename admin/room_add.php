<?php

include 'rooms.php'; 

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


#para sa session kung exist
if($_SESSION['floor']){
    $get_data = $conn2->prepare("SELECT * FROM `floors` WHERE `floor_id` = ?");
    $get_data->bind_param("s",$_SESSION['floor']);
    $get_data->execute();
    $result_get = $get_data->get_result();
    if ($result_get->num_rows > 0) {
        while ($row_floor = mysqli_fetch_assoc($result_get)) { 
            $floor_name_data = htmlspecialchars($row_floor['floor_name'] ?? '');         
        
        }   
    }
}
?>



<main >
    <section class="room_add_section" >
       <form action="../functions.php" method="POST" enctype="multipart/form-data">
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header  border-0 d-flex justify-content-between">
                        <p class="modal-title  fw-bold" id="staticBackdropLabel">Add Room</p>
                        <a href="rooms.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="inner_body shadow-lg">
                            <div class="mb-0 row">
                                <div class="col-lg-6  col-md-6 col-sm-12 mb-3">
                                    <label for="room_name" class="form-label">Room Name</label>
                                    <input type="text"  class="form-control uppercase_function" name="room_name" value="<?php echo $_SESSION['room_name'] ?? ''  ?>" placeholder="Enter Floor Name"  required>
                                </div>
                                <div class="col-lg-6  col-md-6 col-sm-12 mb-3">
                                    <label for="serial_numnber" class="form-label">Serial Number</label>
                                    <input type="text" class="form-control" name="serial_number" value="<?php echo $_SESSION['serial_number'] ?? ''  ?>" placeholder="Enter Serial Number "  required>
                                </div>
                            </div>
                            <div class="mb-0 row">
                                <div class="col-lg-6  col-md-6 col-sm-12 mb-3">
                                    <label for="floor" class="form-label">Floor</label>
                                    <select name="floor" id="floor" class="form-control" required>
                                        <option  value="<?php echo  $_SESSION['floor'] ?? 'Select Floor'; ?>"><?php echo  $floor_name_data ?? 'Select Floor'; ?></option>
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
                                    <input type="text" class="form-control" id="capacity" name="capacity" value="<?php echo $_SESSION['capacity'] ?? ''  ?>" placeholder="Enter Capacity" required>
                                </div>
                            </div>
                           <div class="mb-3 ">
                                <label class="form-label fw-bold">Upload Room Image</label>
                                <div id="dropZone" class="upload-box border rounded p-4 mb-2 d-flex justify-content-center align-items-center flex-column" 
                                    style="cursor: pointer; border-style: dashed !important; background:#f0f0f0; min-height: 150px;"
                                    onclick="document.getElementById('imageInput').click();">
                                    <i class='bx bx-image-add' id="uploadIcon" 
                                    style="font-size: 3rem; color:#7d7f81; display: <?= $has_session_img ? 'none' : 'block' ?>;"></i>

                                    <img id="previewImage" 
                                        src="<?= $has_session_img ? '../assets/trash_images/' . $_SESSION['new_img_session'] : '#' ?>" 
                                        style="max-width: 100%; max-height: 150px; display: <?= $has_session_img ? 'block' : 'none' ?>; margin: 0 auto;">
                                    
                                    <p class="text-muted small mb-0 mt-2">Click to change image</p>
                            </div>
                            <input type="file" id="imageInput" accept=".jpg, .jpeg" name="image" style="display: none;">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description / Amenities</label>
                                <textarea name="description" class="form-control" id="description" placeholder="Type Your Text Here.." required><?php echo  $_SESSION['description'] ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                      <button type="submit" name="room_save" class="btn btn_save">Save <i class="bx bx-checkbox-checked fs-4 "></i>  </button>
                    </div>
                    </div>
                </div>
            </div>
       </form>
    </section>
</main>
