<?php
    ob_start();
    session_start();
	include("template/header.php");
	//require_once('configuration/Configuration.php');
    //require_once('configuration/Sensor.php');
    
    require('./configuration/Report.php');

    $report = new Report(null);
?>

<style>

    canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
    }

</style>

<section class="container-fluid row">
    <div class="col-md-12"></div>
    
    <div class="col-md-12">
        
        <form action="api.php?class=Reports&method=createReport" class="form-signin needs-validation" method="post" enctype="application/x-www-form-urlencoded" novalidate>
            <img class="mb-4" src="images/Kinergetics-Logo.png" alt="Kinergetic, LLC" class="w-100" />
            <h1 class="h3 mb-3 font-weight-normal">Create Report</h1>
            <label for="company" class="sr-only">Company Name</label>
            <select id="company" name="company" class="form-control form-control-lg mb-2">
                <?php
                    
                    $users = $report->getUsers();

                    foreach($users as $user) {

                        echo '<option value="' . $user->getId() . '">' . $user->getCompany() . '</option>';
                    }
                    
                ?>
            </select>

            <label for="device" class="sr-only">Device</label>
            <select id="device" name="device" class="form-control form-control-lg mb-2">
                <?php
                    
                    $devices = $report->getDevices();

                    foreach($devices as $device) {

                        echo '<option value="' . $device->getId() . '">' . $device->getName() . '</option>';
                    }
                    
                ?>
            </select>

            <button class="btn btn-lg btn-success mt-2" type="submit">Create Report</button>

        </form>

        <script>
            (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
                });
            }, false);
            })();
        </script>

    </div>

</section>

<script src="javascript/api.js"></script>

<?php
    include("template/footer.php");
	ob_flush();
?>