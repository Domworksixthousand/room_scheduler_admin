    <section class="fab-section " style="margin-top: 100px;">
        <div class="fab-container">

            <!-- Speed Dial Items -->
            <div class="speed-dial" id="speedDial">

                <div class="fab-item bg-red" onclick="location.href='index.php'" data-bs-toggle="tooltip" data-bs-placement="left";
                data-bs-custom-class="custom-tooltip"
                data-bs-title="Dashboard">
                    <i class="bx bx-grid-alt fs-5  "></i>
                </div>

                <div class="fab-item"  onclick="location.href='floors.php'" data-bs-toggle="tooltip" data-bs-placement="left";
                data-bs-custom-class="custom-tooltip"
                data-bs-title="Floors">
                    <i class='bx bx-building fs-5  '></i>
                </div>

                <div class="fab-item" onclick="location.href='rooms.php'" data-bs-toggle="tooltip" data-bs-placement="left";
                data-bs-custom-class="custom-tooltip"
                data-bs-title="Rooms">
                    <i class="bx bx-door-open fs-5 " ></i>
                </div>

                <div class="fab-item" onclick="location.href='history.php'" data-bs-toggle="tooltip" data-bs-placement="left";
                data-bs-custom-class="custom-tooltip"
                data-bs-title="History">
                    <i class="bx bx-history fs-5  "></i>
                </div>

                <div class="fab-item" onclick="location.href='reservations.php'"  data-bs-toggle="tooltip" data-bs-placement="left";
                data-bs-custom-class="custom-tooltip"
                data-bs-title="Reservations">
                    <i class='bx bx-calendar fs-5  '></i>
                </div>

            </div>

            <!-- Main FAB -->
            <button class="fab-main" id="fabButton"  data-bs-toggle="tooltip" data-bs-placement="left";
                data-bs-custom-class="custom-tooltip"
                data-bs-title="Menu">
            <span class="menu_toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-menu-icon lucide-square-menu"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h10"/></svg>
            </span>
            </button>

        </div>
    </section>



    <script src="../assets/js/fab.js"></script>

















