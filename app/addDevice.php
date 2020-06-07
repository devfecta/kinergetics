<?php
    ob_start();
    session_start();
	include("template/header.php");
	//require_once('configuration/Configuration.php');
    //require_once('configuration/Sensor.php');
    //require('./configuration/User.php');
    //require('./configuration/Device.php');
    //require('./configuration/Report.php');
    //require('./configuration/Reports.php');
    
    $device = json_decode($_SESSION['device'], false);
    
    //$Device = new Device($device->deviceId);
?>

<style>

    canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
    }

</style>

<script>
    const generateTag = (deviceName) => {
        const deviceTag = document.querySelector("#deviceTag");

        let device = '';

        deviceName.split(' ').map( (name, index) => {
            if (index > 0) {
                name = name.charAt(0).toUpperCase() + name.slice(1);
            }
            else {
                name = name.charAt(0).toLowerCase() + name.slice(1);
            }
            device += name;
        });
        deviceTag.value = device;
    }
</script>

<section class="container-fluid row">
    <div class="col-md-12">
        <?php 
            if (isset($device->deviceId)) { 
                echo '<div class="alert alert-success" role="alert">Device Added</div>';
            } 
            elseif (isset($device->error)) {
                echo '<div class="alert alert-danger" role="alert">' . $device->error . '</div>';
            }
            unset($_SESSION['device']); 
        ?>
    </div>
    
    <div class="col-md-12">
        
        <form action="api.php?class=Devices&method=addDevice" class="form-signin needs-validation" method="post" enctype="application/x-www-form-urlencoded" novalidate>
            <img class="mb-4" src="images/Kinergetics-Logo.png" alt="Kinergetic, LLC" class="w-100" />
            <h1 class="h3 mb-3 font-weight-normal">Add a Device</h1>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="deviceName">Device Name: </label>
                    <input type="text" onkeyup="generateTag(this.value)" class="form-control" id="deviceName" name="deviceName" maxlength="48" placeholder="Device Name" value="" required />
                </div>
                <div class="col-md-6 form-group">
                    <label for="deviceTag">Device Tag: </label>
                    <input type="text" readonly class="form-control" id="deviceTag" name="deviceTag" maxlength="48" placeholder="Device Tag" value="" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <button class="btn btn-lg btn-primary mt-2" type="submit">Add Device</button>
                </div>
            </div>

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