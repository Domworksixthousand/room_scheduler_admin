

window.addEventListener("load", function() {
    const preloader = document.getElementById("preloader");
    
    // Smooth transition paalis
    if(preloader)
    {
        preloader.style.transition = "opacity 0.6s ease-in-out";
        preloader.style.opacity = "0";

            
    setTimeout(() => {
        preloader.style.display = "none";
    }, 600);
    }


});

 /*sending book animation */
document.addEventListener("DOMContentLoaded", () => {

    const postForm = document.getElementById('postForm');
    const loadingOverlay = document.getElementById('loadingOverlay');


    if (postForm) {
        postForm.addEventListener('submit', () => {
            if (loadingOverlay) {
                loadingOverlay.style.display = 'block';
                loadingOverlay.style.marginLeft = '5px';
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function() {

    const imageInput = document.getElementById('imageInput');

 
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Update Preview
                    const preview = document.getElementById('previewImage');
                    const icon = document.getElementById('uploadIcon');
                    
                 
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    if (icon) {
                        icon.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});



//modal show
document.addEventListener("DOMContentLoaded", () => {
    const modalElement = document.getElementById('modal_system');
    if (modalElement) {
        let myModal = new bootstrap.Modal(modalElement);
        myModal.show();
    }
});


//show hide ppassword type
document.addEventListener("DOMContentLoaded", () => {
  
    const showPasswordCheckbox = document.getElementById("show_password");

   
    if (showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener("change", function() {
           
            const passwordInput = document.getElementById("password"); 
            
            if (passwordInput) {
                if (this.checked) {
                    passwordInput.type = "text";  
                } else {
                    passwordInput.type = "password"; 
                }
            }
        });
    }
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
document.addEventListener("DOMContentLoaded", () => {

    const uppercaseInputs = document.querySelectorAll('.uppercase_function');

   
    uppercaseInputs.forEach(input => {

        input.addEventListener('input', function() {
            // Split the value by spaces
            const words = this.value.split(' ');

          
            for (let i = 0; i < words.length; i++) {
                if (words[i].length > 0) {
                    words[i] = words[i][0].toUpperCase() + words[i].substr(1);
                }
            }


            this.value = words.join(' ');
        });
    });
});



  //admin sidebar
document.addEventListener("DOMContentLoaded", () => {
    // Dropdown toggles
    let arrows = document.querySelectorAll(".arrow");
    arrows.forEach(arrow => {
        arrow.addEventListener("click", (e) => {
            let arrowParent = e.target.parentElement.parentElement;
            arrowParent.classList.toggle("showMenu");
        });
    });

    // Sidebar toggle (with null check)
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".menu_toggle");

    if (sidebarBtn) {
        sidebarBtn.addEventListener("click", () => {
            if(sidebar){
                   sidebar.classList.toggle("close");
            }
   
        });
    } 
});


//only numbers allowed
document.addEventListener("DOMContentLoaded", ()=> {
    const input = document.getElementById('number_of_rooms');
    if(input){
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

});

    //only numbers allowed
document.addEventListener("DOMContentLoaded", ()=> {
    const capacity = document.getElementById('capacity');
   if(capacity){
        capacity.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
   }
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
  /*
$(document).ready(function(){
      let searchTimer;
        let currentPage = 1;

        function fetchRooms(page = 1) {
            const searchTerm = $('#input_floor').val();
            currentPage = page;

            if(searchTerm){
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
*/




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


//admin/super admin history
/*
 $(document).ready(function() {
    // Fetch function
   function fetchHistory(page = 1, isRetry = false) {
    let input = $('#input_history');
    let searchQuery = input.val() || '';

    let primaryUrl = input.data('url') || '/room_scheduler_admin/admin/history.php';
    

    let fallbackUrl = '/room_scheduler_admin/super_admin/history.php';


    let targetUrl = isRetry ? fallbackUrl : primaryUrl;

    $.ajax({
        url: targetUrl,
        type: 'GET',
        data: {
            ajax: 1,
            page: page,
            search: searchQuery
        },
        dataType: 'json',
        success: function(response) {
            $('#history_body').html(response.table_html);
            $('#history_pagination').html(response.pagination_html);
        },
        error: function(xhr, status, error) {
            // Kapag nag 404 at HINDI pa nakakapag-retry
            if (xhr.status === 404 && !isRetry) {
                console.warn("404 sa " + primaryUrl + ". Sumusubok sa fallback URL: " + fallbackUrl);
               
                fetchHistory(page, true);
            } 
        }
    });
}

    // Initial fetch on load
    fetchHistory(1);

    // Click handler for pagination links
    $(document).on('click', '.page-link-ajax', function(e) {
        e.preventDefault();
        
        let parentLi = $(this).parent('.page-item');
        if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
            return;
        }

        let selectedPage = $(this).data('page');
        fetchHistory(selectedPage);
    });

    // Live Search with 300ms debounce
    let timer;
    $('#input_history').on('keyup search input', function() {
        clearTimeout(timer);
        timer = setTimeout(function() {
            fetchHistory(1);
        }, 300);
    });
});*/

 document.addEventListener("DOMContentLoaded", function(){
     $(document).ready(function() {
          let currentRequest = null;

          // Fetch function
          function fetchHistory(page = 1) {
              let input = $('#input_history');
              let searchQuery = input.val() || '';

   
              if (currentRequest && currentRequest.readyState !== 4) {
                  currentRequest.abort();
              }

              currentRequest = $.ajax({
                  url: window.location.pathname, 
                  type: 'GET',
                  data: {
                      ajax: 1,
                      page: page,
                      search: searchQuery
                  },
                  dataType: 'json',
                  success: function(response) {
                      $('#history_body').html(response.table_html);
                      $('#history_pagination').html(response.pagination_html);
                  },
                  error: function(xhr, status, error) {
                      if (status === 'abort') return;
                      $('#history_body').html("<tr><td colspan='8' class='text-center text-danger'>Error loading data.</td></tr>");
                  }
              });
          }

          // Initial fetch on load
          fetchHistory(1);

          // Click handler for pagination links
          $(document).on('click', '.page-link-ajax', function(e) {
              e.preventDefault();
              
              let parentLi = $(this).parent('.page-item');
              if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
                  return;
              }

              let selectedPage = $(this).data('page');
              fetchHistory(selectedPage);
          });

          // Live Search with 300ms debounce
          let timer;
          $('#input_history').on('keyup search input', function() {
              clearTimeout(timer);
              timer = setTimeout(function() {
                  fetchHistory(1);
              }, 300);
          });
      });
 });

//floor super admin
document.addEventListener("DOMContentLoaded", function(){
    $(document).ready(function() {

        const NO_DATA_HTML = `
                <tr>
                    <td colspan='3' class='text-center'>
                        <div class='d-flex flex-column text-muted justify-content-center align-items-center py-3'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='35' height='35' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-hotel-icon lucide-hotel mb-2'>
                                <path d='M10 22v-6.57'/>
                                <path d='M12 11h.01'/>
                                <path d='M12 7h.01'/>
                                <path d='M14 15.43V22'/>
                                <path d='M15 16a5 5 0 0 0-6 0'/>
                                <path d='M16 11h.01'/>
                                <path d='M16 7h.01'/>
                                <path d='M8 11h.01'/>
                                <path d='M8 7h.01'/>
                                <path d='M4 2h16v20H4z' rx='2'/>
                            </svg>
                            <span>No Data Found</span>
                        </div>
                    </td>
                </tr>
            `;

        function renderNoData() {
            $('#mybody_floor').html(NO_DATA_HTML);
            $('#pagination_links_floor').html('');
        }

        let currentRequest = null;

        function fetchFloors(page = 1) {
            let input = $('#input_floor');
            let searchTerm = input.val() || '';
            let targetUrl = input.data('url') || window.location.pathname;

            if (currentRequest && currentRequest.readyState !== 4) {
                currentRequest.abort();
            }

            currentRequest = $.ajax({
                url: targetUrl,
                method: "GET",
                data: {
                    ajax: 1,
                    search: searchTerm,
                    page: page
                },
                dataType: "json",
                success: function(response) {
                    if (response && typeof response.table_html === 'string' && response.table_html.length > 0) {
                        $('#mybody_floor').html(response.table_html);
                        $('#pagination_links_floor').html(response.pagination_html || '');
                    } else {
                        renderNoData();
                    }
                },
                error: function(xhr, status, error) {
                    // Ignore aborts caused by our own currentRequest.abort() above.
                    if (status === 'abort') {
                        return;
                    }
                    
                    // Any failure quietly shows "No Data Found"
                    renderNoData();
                }
            });
        }

        fetchFloors(1);

        $(document).on('click', '#pagination_links_floor .page-link-ajax', function(e) {
            e.preventDefault();
            let parentLi = $(this).parent('.page-item');

            if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
                return;
            }

            let selectedPage = $(this).data('page');
            fetchFloors(selectedPage);
        });

        let timer;
        $('#input_floor').on('keyup search input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                fetchFloors(1);
            }, 300);
        });

    });
});
// floor admin
document.addEventListener("DOMContentLoaded", function(){
    $(document).ready(function() {
        let currentRequest = null;

        function fetchFloors(page = 1) {
            let input = $('#input_floor');
            let searchTerm = input.val() || '';

            // i-abort ang lumang request kung mabilis ang pag-type ng user
            if (currentRequest && currentRequest.readyState !== 4) {
                currentRequest.abort();
            }

            currentRequest = $.ajax({
                // Lagyan ng '/' sa unahan para magsimula sa root domain
                url: "/room_scheduler_admin/admin/floors.php", 
                method: "GET",
                data: { 
                    ajax: 1,
                    search: searchTerm, 
                    page: page 
                },
                dataType: "json",
                success: function(response) {
                    $('#mybody_floor').html(response.table_html);
                    $('#pagination_links_floor').html(response.pagination_html);
                },
                error: function(xhr, status, error) {
                    if (status === 'abort') return; 
               
                }
            });
        }

        // Initial Fetch
        fetchFloors(1);

        // Live Search Handler with 300ms debounce
        let timer;
        $('#input_floor').on('keyup search input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                fetchFloors(1);
            }, 300);
        });

        // Pagination Click Handler
        $(document).on('click', '#pagination_links_floor .page-link-ajax', function(e) {
            e.preventDefault();

            let parentLi = $(this).parent('.page-item');
            if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
                return;
            }

            let selectedPage = $(this).data('page');
            if (selectedPage) {
                fetchFloors(selectedPage);
            }
        });
    });
});

//admin reservation
$(document).ready(function() {
    let searchTimer;
    let currentPage = 1;
    let isTyping = false;

    const fetchHistory = (page = 1) => {
        currentPage = page;
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
            fetchHistory(1); 
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






document.addEventListener("DOMContentLoaded", () => {
    const fullnameSelect = document.getElementById("fullname");
    const customDiv = document.getElementById("custom_fullname");


    if (!fullnameSelect || !customDiv) return;

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
    if (!dateInput || !end_date) return;
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


//get admin history
/*
$(document).ready(function() {
        // Fetch function
        function fetchHistory(page = 1) {
            let searchQuery = $('#input_history').val();

            $.ajax({
                url: 'history.php',
                type: 'GET',
                data: {
                    ajax: 1,
                    page: page,
                    search: searchQuery
                },
                dataType: 'json',
                success: function(response) {
                    $('#history_body').html(response.table_html);
                    $('#history_pagination').html(response.pagination_html);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", error);
                }
            });
        }

        // Initial fetch on load
        fetchHistory(1);

        // Click handler for pagination links
        $(document).on('click', '.page-link-ajax', function(e) {
            e.preventDefault();
            
            let parentLi = $(this).parent('.page-item');
            if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
                return;
            }

            let selectedPage = $(this).data('page');
            fetchHistory(selectedPage);
        });

        // Live Search with 300ms debounce
        let timer;
        $('#input_history').on('keyup search input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                fetchHistory(1);
            }, 300);
        });
    });
*/

//superadmin/history.php
 $(document).ready(function() {
    let searchTimer;
    let currentPage = 1;

    const fetchHistory = (page = 1) => {
        const searchTerm = $('#input_superadmin_history').val();
        currentPage = page;

       if(searchTerm){
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
       }
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

//



    
//super admin reservation
$(document).ready(function() {
    let searchTimer;
    let currentPage = 1;
    let isTyping = false;

    const fetchHistory = (page = 1) => {
        const searchTerm = $('#superadmin_input_reservation').val();
        currentPage = page;

        if(searchTerm){
    
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
                
        }
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

    // Check kung narito ang lahat ng elements
    if (!startDateInput || !endDateInput || !checkbox || !wrapper || !not_custom || !custom_section || !container || !labelText) {
        return; 
    }

    function checkDateRange() {
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;

        // Siguraduhing may napiling petsa sa dalawang input
        if (startVal && endVal) {
            // String comparison para sa YYYY-MM-DD
            if (endVal > startVal) {
                // KAPAG MAGKAIBANG ARAW: Ipakita ang checkbox wrapper
                wrapper.style.display = "block";
            } else {
                // KAPAG SAME DATE O MAS MABABA: Itago nang pilit ang checkbox wrapper
                wrapper.style.display = "none";    
                checkbox.checked = false;           
                resetToSingleMode();                
            }
        } else {
            wrapper.style.display = "none";
            checkbox.checked = false;
            resetToSingleMode();
        }
    }

    function resetToSingleMode() {
        not_custom.style.display = "flex";
        custom_section.style.display = "none";
        labelText.innerHTML = "Custom daily time schedule";
        container.innerHTML = "";
    }

    // Pakinggan ang bawat pagbago ng input
    startDateInput.addEventListener("change", checkDateRange);
    endDateInput.addEventListener("change", checkDateRange);
    startDateInput.addEventListener("input", checkDateRange);
    endDateInput.addEventListener("input", checkDateRange);

    // PAHABOL: Pilitin nating itago MUNA at i-check pagka-load ng page
    wrapper.style.display = "none"; 
    checkDateRange();

    // Checkbox Toggle Handler
    checkbox.addEventListener("change", function() {
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;

        if (this.checked) {
            if (!startVal || !endVal) {
                CoolAlert.show({ icon: "warning", title: "Wait!", text: "Select dates first." });
                this.checked = false;
                return;
            }

            const [sYear, sMonth, sDay] = startVal.split('-').map(Number);
            const [eYear, eMonth, eDay] = endVal.split('-').map(Number);
            
            let current = new Date(sYear, sMonth - 1, sDay);
            const end = new Date(eYear, eMonth - 1, eDay);

            labelText.innerHTML = "Use same time for all dates";
            not_custom.style.display = "none";
            custom_section.style.display = "block";
            container.innerHTML = ""; 

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

    // Generator ng HTML Slot Row
    function createTimeSlot(dateValue, dateLabel) {
        const div = document.createElement("div");
        div.className = "p-3 mb-2 border rounded bg-light shadow-sm";
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-4">
                    <span class="fw-bold text-primary">${dateLabel}</span>
                    <input type="hidden" name="custom_date[]" value="${dateValue}">
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm custom_input">
                        <span class="input-group-text">Start</span>
                        <input type="time" name="custom_start[]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm custom_input">
                        <span class="input-group-text">End</span>
                        <input type="time" name="custom_end[]" class="form-control" required>
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

    if (!searchInput || !dropdown || !hiddenInput || items.length === 0) {
        return; 
    }
    
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

    const searchInput = document.getElementById("empSearch");
    const dropdown = document.getElementById("empDropdown");
    const items = document.querySelectorAll(".emp-item");
    const hiddenInput = document.getElementById("selectedEmployee");

    if (!searchInput || !dropdown || !hiddenInput || items.length === 0) {
        return; 
    }

    // Normalize function
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


// upper case function
  // Select all inputs with the 'upper_function' class
document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll('.upper_function').forEach(input => {
    input.addEventListener('input', function() {
      let cursorPosition = this.selectionStart; 
      let words = this.value.split(' ');

      let formattedWords = words.map(word => {
        if (word.length === 0) return '';
        
        // First character to Upper Case, the rest to Lower Case
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
      });

      // Join the words back together with spaces
      this.value = formattedWords.join(' ');
      
      // Restore the cursor position so the typing flow isn't disrupted
      this.setSelectionRange(cursorPosition, cursorPosition);
    });
  });
});

  //stop

//start
// meeting title
$(document).ready(function() {
    const $searchInput = $('#empSearchmeeting');
    const $hiddenInput = $('#meeting_title');
    const $dropdown = $('#empDropdown_meeting');
    const $items = $('.emp-item_meeting');

  
    $searchInput.on('focus click', function() {
        $dropdown.removeClass('d-none');
        filterList();
    });


    $searchInput.on('keyup input', function() {
        const val = $(this).val();
        $hiddenInput.val(val);
        $dropdown.removeClass('d-none');
        filterList();
    });

    function filterList() {
        const query = $searchInput.val().toLowerCase();

        $items.each(function() {
            const titleText = $(this).data('name').toString().toLowerCase();
            if (titleText.includes(query)) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        });
    }


    $(document).on('click', '.emp-item_meeting', function() {
        const selectedTitle = $(this).data('name');
        
        $searchInput.val(selectedTitle);
        $hiddenInput.val(selectedTitle);
        $dropdown.addClass('d-none');
    });

 
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.position-relative').length) {
            $dropdown.addClass('d-none');
        }
    });
});
//stop


//start serial number search select 
document.addEventListener('DOMContentLoaded', function() {
    $(document).ready(function() {
        $('#serial_data').on('focus click keyup input', function() {
            var value = $(this).val().toLowerCase().trim();
            var matchFound = false;
            $('.serial_num').each(function() {
                var name = $(this).data('name').toString().toLowerCase();
                if (value === '' || name.indexOf(value) > -1) {
                    $(this).show();
                    matchFound = true;
                } else {
                    $(this).hide();
                }
            });

            if (matchFound) {
                $('#empDropdown_serial').removeClass('d-none');
            }
        });
        $(document).on('mousedown', '.serial_num', function(e) {
            e.preventDefault();

            var name = $(this).data('name');
            var token = $(this).data('token');


            $('#serial_data').val(name);
            
        
            $('#serial_number_hidden').val(token);

    
            $('#empDropdown_serial').addClass('d-none');
        });


        $(document).on('click', function(e) {
            if (!$(e.target).closest('.position-relative').length) {
                $('#empDropdown_serial').addClass('d-none');
            }
        });


    });
});
//stop

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
