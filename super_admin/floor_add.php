<?php include 'floors.php'; ?>

<main >
    <section class="floor_add_section" >
       <form action="../functions.php" method="POST">
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between">
                        <p class="modal-title  fw-bold" id="staticBackdropLabel">Add Floor</p>
                        <a href="floors.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="inner_body shadow-lg">
                            <div class="mb-3">
                                <label for="floor_name" class="form-label">Floor Name</label>
                                <input type="text" class="form-control" id="floor_name" name="floor_name" value="<?php echo $_SESSION['floor_name'] ?? ''; ?>" placeholder="Enter Floor Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="number_of_rooms" class="form-label">Number of Rooms</label>
                                <input type="text" class="form-control" name="number_of_rooms" value="<?php echo $_SESSION['number_of_rooms'] ?? ''; ?>" placeholder="Enter Number of Rooms" id="number_of_rooms" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                      <button type="submit" name="floors_save" class="btn btn_save"> Save <i class="bx bx-checkbox-checked fs-4 "></i></button>
                    </div>
                    </div>
                </div>
            </div>
       </form>
    </section>
</main>
