<nav id="sidebarMenu" class="col-md-3 col-lg-3 d-md-block bg-light sidebar collapse">

    <div class="position-sticky pt-3">
        <div class="nav flex-column">
            <div class="accordion" id="sidebarNav">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="dashboardHeader">
                    <button onclick="window.location.href='index.php'" class="btn btn-light m-2 text-nowrap" type="button">
                        <span class="fas fa-home"></span> Dashboard
                    </h2>
                </div>     

                <?php if ((float)$_SESSION['type'] == 0) { ?>
                    <!-- User Sidebar -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="sensorsHeader">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sensorMenuItems" aria-expanded="false" aria-controls="sensorMenuItems">
                                <span class="fas fa-satellite-dish pe-1"></span> Sensors
                            </button>
                        </h2>
                        <div id="sensorMenuItems" class="accordion-collapse collapse" aria-labelledby="sensorsHeader" data-bs-parent="#sidebarMenu">
                            <div id="userSensors" class="accordion-body p-0"></div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="placeHolderHeader">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#placeHolderMenuItems" aria-expanded="false" aria-controls="placeHolderMenuItems">
                            Place Holder Menu
                        </button>
                        </h2>
                        <div id="placeHolderMenuItems" class="accordion-collapse collapse" aria-labelledby="placeHolderHeader" data-bs-parent="#sidebarMenu">
                            <div id="placeHolderMenuItem" class="accordion-body">
                                Place Holder Menu Items
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        getUserSidebar();
                    </script>

                <?php } else { ?>

                    <!-- Admin Sidebar -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="companiesHeader">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#companyMenuItems" aria-expanded="false" aria-controls="companyMenuItems">
                                <span class="fas fa-building pe-1"></span> Companies
                            </button>
                        </h2>
                        <div id="companyMenuItems" class="accordion-collapse collapse" aria-labelledby="companiesHeader" data-bs-parent="#sidebarMenu">
                            <div id="adminCompanies" class="accordion-body p-0"></div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="placeHolderHeader">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#placeHolderMenuItems" aria-expanded="false" aria-controls="placeHolderMenuItems">
                            Place Holder Menu
                        </button>
                        </h2>
                        <div id="placeHolderMenuItems" class="accordion-collapse collapse" aria-labelledby="placeHolderHeader" data-bs-parent="#sidebarMenu">
                            <div id="placeHolderMenuItem" class="accordion-body">
                                Place Holder Menu Items
                            </div>
                        </div>
                    </div>

                    <script>
                        getAdminSidebar();
                    </script>

                <?php } ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="logoutHeader">
                        <button onclick="window.location.href='logout.php'" class="btn btn-light m-2 text-nowrap" type="button">
                            <span class="fas fa-sign-out-alt"></span> Logout
                        </h2>
                    </div>
            </div>
        </div>
    </div>
</nav>