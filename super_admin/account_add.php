<?php include 'accounts.php'; ?>


<style>
    .assign_account_section .modal-body{
        padding:20px;
        background:white;
    }
    .assign_account_section .inner_body{
         background: white;
    padding: 26px;
    border-radius: 16px;
    }

    .assign_account_section input{
        background: #f0f0f0;
    }
</style>

<main >
    <section class="assign_account_section" >
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content ">
                    <div class="modal-header border-0 d-flex justify-content-between">
                        <p class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Assign Accounts</p>
                        <a href="accounts.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                       <div class="inner_body shadow-lg">
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
                                                    <td><a href='confirm_assign.php?data=$employee_id' class='btn btn-primary text-light btn-sm '><img src='../assets/images/user-check.png' alt=''> Assign</a></td>
                                                </tr>";
                                            
                                            }

                                        } else {
                                        echo "<tr> <td colspan='5'><p class='text-center  m-0'>No Result Found</p></td> </tr>";
                                        }
                                    }else {
                                        echo "<tr> <td colspan='5'><p class='text-center  m-0'>Start typing to see results.</p></td> </tr>";
                                        }
                            ?>
                                </tbody>
                            </table>  
                       </div>
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
