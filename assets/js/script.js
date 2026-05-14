
window.addEventListener("load", function() {
    const preloader = document.getElementById("preloader");
    
    // Smooth transition paalis
    preloader.style.transition = "opacity 0.6s ease-in-out";
    preloader.style.opacity = "0";
    
    setTimeout(() => {
        preloader.style.display = "none";
    }, 600);
});

 /*sending book animation */
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById('postForm').addEventListener('submit', ()=> {
        document.getElementById('loadingOverlay').style.display = 'block';
        document.getElementById('loadingOverlay').style.marginLeft = '5px';
    });
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('imageInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update Preview
            const preview = document.getElementById('previewImage');
            const icon = document.getElementById('uploadIcon');
            
            preview.src = e.target.result;
            preview.style.display = 'block';
            icon.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
});




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

document.addEventListener("DOMContentLoaded", function() {
    const fileInput = document.getElementById('imageInput2');
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImage2');
                    const icon = document.getElementById('uploadIcon2');
                    
                    // Update Image Source
                    preview.src = e.target.result;
                    
                    // Force Styles
                    preview.style.setProperty('display', 'block', 'important');
                    if (icon) {
                        icon.style.setProperty('display', 'none', 'important');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
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
    // Trigger search on typing
    $('#input_room').on('keyup', function() {
        fetchRooms(1);
    });

    // Trigger search on checkbox change
    $(document).on('change', '.hidden-checkbox, #available, #partially_occupied, #fully_occupied', function() {
        fetchRooms(1);
    });

    // Initial load
    fetchRooms(1);
});

function fetchRooms(page = 1) {
    const searchTerm = $('#input_room').val();
    
    let selectedFloors = [];
    $('.hidden-checkbox:checked').each(function() {
        selectedFloors.push($(this).val());
    });

    let selectedStatus = [];
    if($('#available').is(':checked')) selectedStatus.push('Available');
    if($('#partially_occupied').is(':checked')) selectedStatus.push('Partially Occupied');
    if($('#fully_occupied').is(':checked')) selectedStatus.push('Fully Occupied');

    // Visual feedback that it's loading
    $('#room_body').css('opacity', '0.5');

    $.ajax({
        url: "room_fetch.php",
        method: "GET",
        data: { 
            search: searchTerm, 
            page: page, 
            floors: selectedFloors,
            status: selectedStatus
        },
        success: function(response) {
            const parts = response.split("|||");
            if (parts.length === 2) {
                $('#room_body').html(parts[0]).css('opacity', '1');
                $('#pagination_links').html(parts[1]);
                
                // Re-initialize tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            }
        }
    });
}


$(document).ready(function() {
    // 1. Listen for search input typing
    $('#input_room').on('input', function() {
        fetchRooms(1);
    });

    // 2. Listen for filter checkbox changes
    $(document).on('change', '.hidden-checkbox, #available, #partially_occupied, #fully_occupied', function() {
        fetchRooms(1);
    });

    // 3. FIX: Handle Pagination Clicks via Delegation
    $(document).on('click', '.page-link-ajax', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        fetchRooms(page);
    });
});








//admin reservation
$(document).ready(function() {
    let searchTimer;
    let currentPage = 1; // This is global to this scope
    let isTyping = false;

    const fetchHistory = (page = 1) => {
        currentPage = page; // CRITICAL: Update the global currentPage
        const searchTerm = $('#input_reservation').val();
        const searchDate = $('#date_reservation').val();

        let selectedFloors = [];
        $('.hidden-checkbox:checked').each(function() {
            if($(this).attr('id').includes('floor_')) {
                selectedFloors.push($(this).attr('id').replace('floor_', ''));
            }
        });

        let selectedStatus = [];
        if($('#ongoing').is(':checked')) selectedStatus.push('On Going');
        if($('#upcoming').is(':checked')) selectedStatus.push('Upcoming');
        if($('#done').is(':checked')) selectedStatus.push('Done');
        if($('#cancelled').is(':checked')) selectedStatus.push('Cancelled');

        $.ajax({
            url: "reservation_fetch.php",
            method: "GET",
            data: { 
                search: searchTerm, 
                date: searchDate,
                page: page, // Send the requested page
                floors: selectedFloors, 
                status: selectedStatus  
            },
            success: function(response) {
                const parts = response.split("|||");
                $('#reservation_body').html(parts[0]);
                $('#pagination_reservation').html(parts[1]);
            }
        });
    };

    // --- Events ---

    // Click Pagination
    $(document).on('click', '.page-link-ajax', function(e) {
        e.preventDefault();
        const nextPage = $(this).data('page');
        fetchHistory(nextPage); // This updates currentPage and fetches data
    });

    // Checkboxes & Date
    $(document).on('change', '.hidden-checkbox, #date_reservation', function() {
        fetchHistory(1); // Reset to page 1 on filter change
    });

    // Search Input
    $(document).on('keyup', '#input_reservation', function() {
        isTyping = true;
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            fetchHistory(1); // Reset to page 1 on new search
            isTyping = false;
        }, 500);
    });
    
    // Initial Load
    fetchHistory(1);

    // Auto Refresh (Now uses the updated currentPage)
    setInterval(function() {
        if (!isTyping) {
            fetchHistory(currentPage); 
        }
    }, 3000); // Recommendation: Increase to 3-5 seconds to save server resources
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



//superadmin/history.php
 $(document).ready(function() {
    let searchTimer;
    let currentPage = 1;

    const fetchHistory = (page = 1) => {
        const searchTerm = $('#input_superadmin_history').val();
        currentPage = page;

        $.ajax({
            url: "history_fetch.php",
            method: "GET",
            data: { search: searchTerm, page: page },
            success: function(response) {

                const [tableData, paginationData] = response.split("|||");
                
                $('#superadmin_history_body').hide().html(tableData).fadeIn(200);
                $('#superadmin_history_paginatiom').html(paginationData);
            },
            error: function() {
                $('#superadmin_history_body').html("<tr><td colspan='7' class='text-center text-danger'>Connection error.</td></tr>");
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



    
//super admin reservation
$(document).ready(function() {
    let searchTimer;
    let currentPage = 1;
    let isTyping = false;

    const fetchHistory = (page = 1) => {
        const searchTerm = $('#superadmin_input_reservation').val();
        currentPage = page;

        $.ajax({
            url: "reservation_fetch.php",
            method: "GET",
            data: { search: searchTerm, page: page },
            success: function(response) {
  
                const parts = response.split("|||");
                if (parts.length === 2) {
                    $('#superadmin_reservation_body').html(parts[0]);
                    $('#superadmin_pagination_reservation').html(parts[1]);
                }
            },
            error: function() {
                console.log("Error fetching data.");
            }
        });
    };

    fetchHistory();


    $(document).on('keyup', '#superadmin_input_reservation', function() {
        isTyping = true; 
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            fetchHistory(1);
            isTyping = false;
        }, 500);
    });

    // 3. Pagination Click (Manual)
    $(document).on('click', '.page-link-ajax', function(e) {
        e.preventDefault();
        fetchHistory($(this).data('page'));
    });


    setInterval(function() {
        if (!isTyping) {
            fetchHistory(currentPage);
        }
    }, 1000); 
});




 document.addEventListener("DOMContentLoaded", function() {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const wrapper = document.getElementById("wrapper");
    const checkbox = document.getElementById("terms-checkbox-37");
    const not_custom = document.getElementById("not_custom");
    const custom_section = document.getElementById("custom");
    const container = document.getElementById("custom_container");
    const labelText = document.querySelector(".label-text");


    function checkDateRange() {
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;

        if (startVal && endVal) {
            const start = new Date(startVal);
            const end = new Date(endVal);

            if (end > start) {
                wrapper.classList.remove("d-none");
            } else {
                wrapper.classList.add("d-none");    
                checkbox.checked = false;           
                resetToSingleMode();                
            }
        }
    }

  
    function resetToSingleMode() {
        not_custom.style.display = "flex";
        custom_section.style.display = "none";
        labelText.innerHTML = "Custom daily time schedule";
        container.innerHTML = "";
    }

  
    startDateInput.addEventListener("change", checkDateRange);
    endDateInput.addEventListener("change", checkDateRange);

   
    checkbox.addEventListener("change", function() {
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;

        if (this.checked) {
     
            if (!startVal || !endVal) {
                CoolAlert.show({ icon: "warning", title: "Wait!", text: "Select dates first." });
                this.checked = false;
                return;
            }

            const start = new Date(startVal);
            const end = new Date(endVal);

          
            labelText.innerHTML = "Use same time for all dates";
            not_custom.style.display = "none";
            custom_section.style.display = "block";
            container.innerHTML = ""; 

         
            let current = new Date(start);
            while (current <= end) {
              
                let y = current.getFullYear();
                let m = String(current.getMonth() + 1).padStart(2, '0');
                let d = String(current.getDate()).padStart(2, '0');
                let dateStr = `${y}-${m}-${d}`;

          
                let displayDate = current.toLocaleDateString('en-US', { 
                    weekday: 'short', month: 'short', day: 'numeric' 
                });

                createTimeSlot(dateStr, displayDate);

                current.setDate(current.getDate() + 1);
            }
        } else {
            resetToSingleMode();
        }
    });

    // FUNCTION: Generator ng HTML Slot Row
    function createTimeSlot(dateValue, dateLabel) {
        const div = document.createElement("div");
        div.className = "p-3 mb-2 border rounded bg-light shadow-sm";
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-4">
                    <span class="fw-bold text-primary">${dateLabel}</span>
                    <input type="hidden" name="custom_date[]" value="${dateValue}">
                </div>
                <div class="col-md-4 ">
                    <div class="input-group input-group-sm custom_input">
                        <span class="input-group-text">Start</span>
                        <input type="time" name="custom_start[]" class="form-control " required>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="input-group input-group-sm custom_input">
                        <span class="input-group-text">End</span>
                        <input type="time" name="custom_end[]" class="form-control " required>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    }
});


//para sa dropdown sa book.php
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("empSearch");
    const dropdown = document.getElementById("empDropdown");
    const items = document.querySelectorAll(".emp-item");
    const hiddenInput = document.getElementById("selectedEmployee");

    // Normalize function (important)
    function normalize(str) {
        return str.toLowerCase().replace(/\s+/g, ' ').trim();
    }

    searchInput.addEventListener("focus", () => {
        dropdown.classList.remove("d-none");
    });

    searchInput.addEventListener("keyup", function () {
        let search = normalize(this.value);

        items.forEach(item => {
            let text = normalize(item.textContent);

            // Split words (for flexible search)
            let searchWords = search.split(" ");

            let match = searchWords.every(word => text.includes(word));

            item.style.display = match ? "" : "none";
        });
    });

    // Select
    items.forEach(item => {
        item.addEventListener("click", function () {
            let name = this.getAttribute("data-name");

            searchInput.value = name;
            hiddenInput.value = name;

            dropdown.classList.add("d-none");
        });
    });

    document.addEventListener("click", function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add("d-none");
        }
    });

});

 

//search dropdown
document.addEventListener("DOMContentLoaded", function () {
    const searchInput1 = document.getElementById("empSearchmeeting");
    const dropdown1 = document.getElementById("empDropdown_meeting");
    const items1 = document.querySelectorAll(".emp-item_meeting");
    
    // Fix: Match the ID from your HTML hidden input
    const hiddenInput1 = document.getElementById("meeting_title");

    function normalize(str) {
        return str.toLowerCase().replace(/\s+/g, ' ').trim();
    }

    searchInput1.addEventListener("focus", () => {
        dropdown1.classList.remove("d-none");
    });

    searchInput1.addEventListener("input", function () { // 'input' is usually better than 'keyup'
        let search = normalize(this.value);
        let hasMatches = false;

        items1.forEach(item => {
            // Fix: Use 'item' instead of 'items1'
            let text = normalize(item.textContent);
            let searchWords = search.split(" ");
            let match = searchWords.every(word => text.includes(word));

            item.style.setProperty('display', match ? "" : "none", 'important');
            if (match) hasMatches = true;
        });

        // Hide dropdown if no results, show if there are
        if (search === "" || hasMatches) {
            dropdown1.classList.remove("d-none");
        }
    });

    // Select Item logic
    items1.forEach(item => {
        item.addEventListener("click", function () { // Fix: Use 'item'
            let name = this.getAttribute("data-name");

            searchInput1.value = name;
            hiddenInput1.value = name;

            dropdown1.classList.add("d-none");
        });
    });

    // Close when clicking outside
    document.addEventListener("click", function (e) {
        // Fix: Changed 'dropdown' to 'dropdown1'
        if (!searchInput1.contains(e.target) && !dropdown1.contains(e.target)) {
            dropdown1.classList.add("d-none");
        }
    });
});


 

//superadmin
/*
 document.addEventListener("DOMContentLoaded", function() {
    const startDateInput = document.getElementById("superadmin_start_date");
    const endDateInput = document.getElementById("superadmin_end_date");
    const wrapper = document.getElementById("superadmin_wrapper");
    const checkbox = document.getElementById("terms-checkbox-38");
    const not_custom = document.getElementById("superadmin_not_custom");
    const custom_section = document.getElementById("superadmin_custom");
    const container = document.getElementById("superadmin_custom_container");
    const labelText = document.querySelector(".label-text");


    function checkDateRange() {
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;

        if (startVal && endVal) {
            const start = new Date(startVal);
            const end = new Date(endVal);

            if (end > start) {
                wrapper.classList.remove("d-none");
            } else {
                wrapper.classList.add("d-none");    
                checkbox.checked = false;           
                resetToSingleMode();                
            }
        }
    }

  
    function resetToSingleMode() {
        not_custom.style.display = "flex";
        custom_section.style.display = "none";
        labelText.innerHTML = "Custom daily time schedule";
        container.innerHTML = "";
    }

  
    startDateInput.addEventListener("change", checkDateRange);
    endDateInput.addEventListener("change", checkDateRange);

   
    checkbox.addEventListener("change", function() {
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;

        if (this.checked) {
     
            if (!startVal || !endVal) {
                CoolAlert.show({ icon: "warning", title: "Wait!", text: "Select dates first." });
                this.checked = false;
                return;
            }

            const start = new Date(startVal);
            const end = new Date(endVal);

          
            labelText.innerHTML = "Use same time for all dates";
            not_custom.style.display = "none";
            custom_section.style.display = "block";
            container.innerHTML = ""; 

         
            let current = new Date(start);
            while (current <= end) {
              
                let y = current.getFullYear();
                let m = String(current.getMonth() + 1).padStart(2, '0');
                let d = String(current.getDate()).padStart(2, '0');
                let dateStr = `${y}-${m}-${d}`;

          
                let displayDate = current.toLocaleDateString('en-US', { 
                    weekday: 'short', month: 'short', day: 'numeric' 
                });

                createTimeSlot(dateStr, displayDate);

                current.setDate(current.getDate() + 1);
            }
        } else {
            resetToSingleMode();
        }
    });

    // FUNCTION: Generator ng HTML Slot Row
    function createTimeSlot(dateValue, dateLabel) {
        const div = document.createElement("div");
        div.className = "p-3 mb-2 border rounded bg-light shadow-sm";
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-4">
                    <span class="fw-bold text-primary">${dateLabel}</span>
                    <input type="hidden" name="custom_date[]" value="${dateValue}">
                </div>
                <div class="col-md-4 ">
                    <div class="input-group input-group-sm custom_input">
                        <span class="input-group-text">Start</span>
                        <input type="time" name="custom_start[]" class="form-control " required>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="input-group input-group-sm custom_input">
                        <span class="input-group-text">End</span>
                        <input type="time" name="custom_end[]" class="form-control " required>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    }
});*/
