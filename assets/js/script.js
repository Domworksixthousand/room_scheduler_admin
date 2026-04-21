


//modal show
document.addEventListener("DOMContentLoaded", ()=> {
    let myModal = new bootstrap.Modal(document.getElementById('modal_system'));
    myModal.show();
});


//show hide ppassword type
document.addEventListener("DOMContentLoaded", ()=> {
    document.getElementById("show_password").addEventListener("change", ()=> {
        let show_password_text = document.getElementById("show_password_text");
        let password = document.getElementById("password");
        if(password.type === "password"){
            password.type = "text";
            show_password_text.innerText = "Hide Password";
        }else{
            password.type = "password";
            show_password_text.innerText = "Show Password";
        }
    });
});



/*super admin sidebar*/
document.addEventListener("DOMContentLoaded", () => {

    const sidebarToggleBtns = document.querySelectorAll(".sidebar-toggle");
    const sidebar = document.querySelector(".sidebar");
    const searchForm = document.querySelector(".search-form");
    const themeToggleBtn = document.querySelector(".theme-toggle");
    const themeIcon = themeToggleBtn ? themeToggleBtn.querySelector(".theme-icon") : null;
    const menuLinks = document.querySelectorAll(".menu-link");



    sidebarToggleBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            if (!sidebar) return;

            sidebar.classList.toggle("collapsed");
            updateThemeIcon();
        });
    });



    if (menuLinks.length > 0) {
        menuLinks.forEach(link => {
            link.addEventListener("click", function () {
                menuLinks.forEach(l => l.classList.remove("active"));
                this.classList.add("active");
            });
        });
    }


    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove("collapsed");
    }

});



//uppercase first character
document.addEventListener("DOMContentLoaded", ()=>{
    const input = document.querySelector('.uppercase_function');
    input.addEventListener('input', function() {
        // Split the value by spaces
        const words = this.value.split(' ');

        // Capitalize first letter of each word
        for (let i = 0; i < words.length; i++) {
            if (words[i].length > 0) {
                words[i] = words[i][0].toUpperCase() + words[i].substr(1);
            }
        }

        // Join back into string
        this.value = words.join(' ');
    });
});




  //admin sidebar
document.addEventListener("DOMContentLoaded", ()=>{
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e) => {
        let arrowParent = e.target.parentElement.parentElement; 
        arrowParent.classList.toggle("showMenu");
    });
    }
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".menu_toggle");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", () => {
    sidebar.classList.toggle("close");
    });
});


//only numbers allowed
document.addEventListener("DOMContentLoaded", ()=> {
    const input = document.getElementById('number_of_rooms');
    input.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
});

    //only numbers allowed
document.addEventListener("DOMContentLoaded", ()=> {
    const capacity = document.getElementById('capacity');
    capacity.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
});

// search account super admin accounts.php
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    var anyVisible = false;

    // loop through table rows except the #nofound row
    $("#myTable tr").not("#nofound").each(function() {
      var match = $(this).text().toLowerCase().indexOf(value) > -1;
      $(this).toggle(match);
      if(match) anyVisible = true;
    });

    // toggle No Accounts Found row
    if(!anyVisible){
      $("#nofound").removeClass("d-none");
    } else {
      $("#nofound").addClass("d-none");
    }
  });
});


  // search account super admin floors.php
$(document).ready(function(){
      let searchTimer;
        let currentPage = 1;

        function fetchRooms(page = 1) {
            const searchTerm = $('#input_floor').val();
            currentPage = page;

            $.ajax({
                url: "floor_fetch.php",
                method: "GET",
                data: { search: searchTerm, page: page },
                success: function(response) {
                    const parts = response.split("|||");
                    $('#mybody_floor').html(parts[0]);
                    $('#pagination_links_floor').html(parts[1]);
                },
                error: function() {
                    $('#mybody_floor').html("<tr><td colspan='6' class='text-center'>Error loading data.</td></tr>");
                }
            });
        }

        // Live search
        $('#input_floor').on('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => fetchRooms(1), 300);
        });

        // Handle Pagination Clicks
        $(document).on('click', '.page-link-ajax', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            fetchRooms(page);
        });

        // Initial Load
        fetchRooms();
        
        // Optional: Polling
        setInterval(() => {
            if ($('#input_floor').val() === "") fetchRooms(currentPage);
        }, 1000); 
});




//admin room.php
 $(document).ready(function() {
        let searchTimer;
        let currentPage = 1;

        function fetchRooms(page = 1) {
            const searchTerm = $('#input_room').val();
            currentPage = page;

            $.ajax({
                url: "room_fetch.php",
                method: "GET",
                data: { search: searchTerm, page: page },
                success: function(response) {
                    const parts = response.split("|||");
                    $('#room_body').html(parts[0]);
                    $('#pagination_links').html(parts[1]);
                },
                error: function() {
                    $('#room_body').html("<tr><td colspan='6' class='text-center'>Error loading data.</td></tr>");
                }
            });
        }

        // Live search
        $('#input_room').on('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => fetchRooms(1), 300);
        });

        // Handle Pagination Clicks
        $(document).on('click', '.page-link-ajax', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            fetchRooms(page);
        });

        // Initial Load
        fetchRooms();
        
        // Optional: Polling
        setInterval(() => {
            if ($('#input_room').val() === "") fetchRooms(currentPage);
        }, 1000); 
    });


    

//admin/reservation.php
 $(document).ready(function() {
    let searchTimer;
    let currentPage = 1;

    const fetchHistory = (page = 1) => {
        const searchTerm = $('#input_reservation').val();
        currentPage = page;

        $.ajax({
            url: "reservation_fetch.php",
            method: "GET",
            data: { search: searchTerm, page: page },
            success: function(response) {

                const [tableData, paginationData] = response.split("|||");
                
                $('#reservation_body').hide().html(tableData).fadeIn(200);
                $('#pagination_reservation').html(paginationData);
            },
            error: function() {
                $('#reservation_body').html("<tr><td colspan='7' class='text-center text-danger'>Connection error.</td></tr>");
            }
        });
    };

    // Trigger search on typing (with 300ms delay to save server resources)
    $(document).on('keyup', '#input_reservation', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchHistory(1), 300);
    });

    // Handle Pagination Clicks using Delegation
    $(document).on('click', '.page-link-ajax', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        fetchHistory(page);
    });

    // Initial Load
    fetchHistory();

});



//admin/history.php
 $(document).ready(function() {
    let searchTimer;
    let currentPage = 1;

    const fetchHistory = (page = 1) => {
        const searchTerm = $('#input_history').val();
        currentPage = page;

        $.ajax({
            url: "history_fetch.php",
            method: "GET",
            data: { search: searchTerm, page: page },
            success: function(response) {

                const [tableData, paginationData] = response.split("|||");
                
                $('#history_body').hide().html(tableData).fadeIn(200);
                $('#history_paginatiom').html(paginationData);
            },
            error: function() {
                $('#history_body').html("<tr><td colspan='7' class='text-center text-danger'>Connection error.</td></tr>");
            }
        });
    };

    // Trigger search on typing (with 300ms delay to save server resources)
    $(document).on('keyup', '#input_history', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchHistory(1), 300);
    });

    // Handle Pagination Clicks using Delegation
    $(document).on('click', '.page-link-ajax', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        fetchHistory(page);
    });

    // Initial Load
    fetchHistory();

});




document.addEventListener("DOMContentLoaded", () => {
    const fullnameSelect = document.getElementById("fullname");
    const customDiv = document.getElementById("custom_fullname");

   
    function toggleCustomInput() {
        if (fullnameSelect.value === "Others") {
            customDiv.classList.remove('d-none');
        } else {
            customDiv.classList.add('d-none');
        }
    }

  
    toggleCustomInput();


    fullnameSelect.addEventListener("change", toggleCustomInput);
});



// prevent to input previous date
document.addEventListener("DOMContentLoaded", ()=> {
    const dateInput = document.getElementById('start_date');
    const end_date = document.getElementById('end_date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
    end_date.setAttribute('min', today);
    dateInput.addEventListener('input', function() {
        if (this.value < today) {
            this.value = today; 
        }
    });
     end_date.addEventListener('input', function() {
        if (this.value < today) {
            this.value = today; 
        }
    });
});
