<?php
    ob_start();
    session_start();
	include("template/header.php");
	//require_once('configuration/Configuration.php');
    //require_once('configuration/Sensor.php');
    require('./configuration/User.php');
    require('./configuration/Device.php');
    require('./configuration/Report.php');
    require('./configuration/Reports.php');
    
    $report = json_decode($_SESSION['report'], false);

    $dataPoint = json_decode($_SESSION['dataPoint'], false);
    

    $Report = new Report(null);

    $User = new User($report->userId);
    $Device = new Device($report->deviceId);
?>

<style>

    canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
    }

</style>

<section class="container-fluid row">
    <div class="col-md-12">
        <h1><?php echo $User->getCompany(); ?> - Energy Matrix</h1>
        <h2><?php echo $Device->getName(); ?> Device</h2>

        <?php 
            if (isset($dataPoint->dataPointId)) { 
                echo '<div class="alert alert-success" role="alert">Data Point Added</div>';
            } 
            elseif (isset($dataPoint->error)) {
                echo '<div class="alert alert-danger" role="alert">' . $dataPoint->error . '</div>';
            }
            unset($_SESSION['dataPoint']); 
        ?>
    </div>
    
    <div class="col-md-12">
        
        <form action="api.php?class=Reports&method=addDataPoint" class="form-signin needs-validation" method="post" enctype="application/x-www-form-urlencoded" novalidate>
            <img class="mb-4" src="images/Kinergetics-Logo.png" alt="Kinergetic, LLC" class="w-100" />
            <h1 class="h3 mb-3 font-weight-normal">Add Data Point</h1>
            
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="pointDate">Timestamp: </label>
                    <input type="date" class="form-control" id="pointDate" name="pointDate" 
                        value="<?php echo date("Y-m-d"); ?>"
                        min="<?php echo date("Y-m-d", strtotime('-5 years')); ?>" 
                        max="<?php echo date("Y-m-d"); ?>">
                    <input type="time" class="form-control" id="pointTime" name="pointTime"
                        value="<?php echo date("H:m"); ?>"
                        min="00:00" 
                        max="23:59">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="flowRate">Flow Rate: </label>
                    <input type="number" step="0.001" class="form-control" id="flowRate" name="flowRate" maxlength="7" />
                </div>
                <div class="col-md-6 form-group">
                    <label for="totalVolume">Total Volume: </label>
                    <input type="number" step="0.001" class="form-control" id="totalVolume" name="totalVolume" maxlength="10" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="fahrenheit">Fahrenheit: </label>
                    <input type="number" step="0.001" class="form-control" id="fahrenheit" name="fahrenheit" maxlength="7" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="relativeHumidity">Relative Humidity: </label>
                    <input type="number" step="0.001" class="form-control" id="relativeHumidity" name="relativeHumidity" maxlength="7" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="current">Current: </label>
                    <input type="number" step="0.001" class="form-control" id="current" name="current" maxlength="7" />
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <p>Voltage Detected:</p>
                        </div>
                        <div class="col-md-8 form-check form-check-inline">
                            <input type="radio" class="form-control w-50" id="voltageDetectedNo" name="voltageDetected" value="0" checked /> <label for="voltageDetectedNo" class="form-check-label">No</label> 
                            <input type="radio" class="form-control w-50" id="voltageDetectedYes" name="voltageDetected" value="1" /> <label for="voltageDetectedYes" class="form-check-label">Yes</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="errorCode">Error Code: </label>
                    <input type="number" class="form-control" id="errorCode" name="errorCode" maxlength="4" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="velocityReading">Velocity Reading: </label>
                    <input type="number" step="0.001" class="form-control" id="velocityReading" name="velocityReading" maxlength="7" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="velocityLowLimit">Velocity Low Limit: </label>
                    <input type="number" step="0.001" class="form-control" id="velocityLowLimit" name="velocityLowLimit" maxlength="7" />
                </div>
                <div class="col-md-6 form-group">
                    <label for="velocityHighLimit">Velocity High Limit: </label>
                    <input type="number" step="0.001" class="form-control" id="velocityHighLimit" name="velocityHighLimit" maxlength="7" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="velocityCustom">Velocity Custom ma: </label>
                    <input type="number" step="0.001" class="form-control" id="velocityCustom" name="velocityCustom" maxlength="7" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="pressureReading">Pressure Reading: </label>
                    <input type="number" step="0.001" class="form-control" id="pressureReading" name="pressureReading" maxlength="7" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="pressureLowLimit">Pressure Low Limit: </label>
                    <input type="number" step="0.001" class="form-control" id="pressureLowLimit" name="pressureLowLimit" maxlength="7" />
                </div>
                <div class="col-md-6 form-group">
                    <label for="pressureHighLimit">Pressure High Limit: </label>
                    <input type="number" step="0.001" class="form-control" id="pressureHighLimit" name="pressureHighLimit" maxlength="7" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="pressureCustom">Pressure Custom ma: </label>
                    <input type="number" step="0.001" class="form-control" id="pressureCustom" name="pressureCustom" maxlength="7" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <button class="btn btn-lg btn-primary mt-2" type="submit">Add Data Point</button>
                    <input type="hidden" id="reportId" name="reportId" value="<?php echo $report->reportId; ?>" />
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