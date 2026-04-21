<?php include 'accounts.php'; ?>


<main >
    <section class="assign_account_section" >
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                    <div class="modal-header border-0">
                        <p class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Assign Accounts</p>
                        <button type="button" onclick="location.href='accounts.php'" class="btn-close me-2"  data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <form action="" method="GET" class="d-flex gap-2 mb-3">
                            <input type="search" class="form-control" name="search_input" placeholder="Search Name" required>
                            <button type="submit" name="search_butt" class="btn btn_search"><img src="../assets/images/searching_icon.png" alt=""></button>
                       </form>
                       <table class="table">
                        <thead>
                            <tr>
                                <th>Lastname</th>
                                <th>Firstname</th>
                                <th>Middlename</th>
                                <th>Suffix</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                      
                       <?php
                                if(isset($_GET['search_butt'])){
                                $search_input = htmlspecialchars($_GET['search_input']);
                                $words = explode(" ", $search_input); 


                                $sql = "SELECT * FROM employee WHERE ";
                                $params = [];
                                $types = "";

                                $conditions = [];
                                foreach($words as $word){
                                    $word = "%$word%"; 
                                    $conditions[] = "(FirstName LIKE ? OR LastName LIKE ? OR MiddleName LIKE ?) ";
                                    $params[] = $word;
                                    $params[] = $word;
                                    $params[] = $word;
                                    $types .= "sss";
                                    
                                }

                                $sql .= implode(" AND ", $conditions) ; 
                                $sql .= " ORDER BY LastName ASC, FirstName ASC, MiddleName ASC";
                                $stmt = $conn1->prepare($sql);

                                // Bind params dynamically
                                $stmt->bind_param($types, ...$params);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                                        $firstname = htmlspecialchars($row['FirstName'] ?? '');
                                        $lastname = htmlspecialchars($row['LastName'] ?? '');
                                        $middlename = htmlspecialchars($row['MiddleName'] ?? '');
                                        $suffix = htmlspecialchars($row['Suffix_ID'] ?? '');
                                        $employee_id = htmlspecialchars($row['Employee_ID'] ?? '');
                                        $gender = htmlspecialchars($row['Gender_ID'] ?? '');
                                      
                                        echo "
                                        <tr>
                                            <td>$lastname</td>
                                            <td>$firstname</td>
                                            <td>$middlename</td>
                                            <td>$suffix</td>
                                            <td><a href='confirm_assign.php?data=$employee_id' class='btn btn_assign '><img src='../assets/images/user-check.png' alt=''> Assign</a></td>
                                        </tr>";
                                     
                                    }

                                } else {
                                   echo "<tr> <td colspan='5'><p class='text-center'>No Result Found</p></td> </tr>";
                                }
                            }
                       ?>
                         </tbody>
                       </table>  
                    </div>
                    <div class="modal-footer border-0">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                     <!--   <button type="submit" class="btn btn_confirmbook"> Confirm Book</button>-->
                    </div>
                    </div>
                </div>
            </div>
    </section>
</main>
