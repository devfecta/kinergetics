<?php
    ob_start();
    session_start();
	include("template/header.php");
	//require_once('configuration/Configuration.php');
	//require_once('configuration/Sensor.php');
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
        <h1><?php echo $_SESSION['company']; ?> - Energy Matrix</h1>
    </div>
</section>

<?php if ((float)$_SESSION['type'] > 0) { ?>

<section id="adminSection" class="container-fluid row">
    <div id="accordion" class="w-100"></div>
</section>

<script>
    getCompanies();
</script>

<?php } else { ?>

<section id="userSection" class="container-fluid row <?php echo ($_SESSION['type'] == 0) ? "" : "d-none"; ?>">
    <div class="col-md-3">
        <form id="searchForm" method="post">
            <div class="form-group row mx-2">
                <label for="startDate">Start Date: </label>
                    <input type="date" class="form-control" id="startDate" name="startDate" 
                        value=""
                        min="" 
                        max="<?php echo date("Y-m-d"); ?>">
                    <input type="time" class="form-control" id="startTime" name="startTime"
                        value="<?php echo date("H:m"); ?>"
                        min="00:00" 
                        max="23:59">
            </div>
            <div class="form-group row mx-2">
                <label for="endDate">End Date: </label>
                    <input type="date" class="form-control" id="endDate" name="endDate" 
                        value=""
                        min="" 
                        max="<?php echo date("Y-m-d"); ?>">
                    <input type="time" class="form-control" id="endTime" name="endTime"
                        value="<?php echo date("H:m"); ?>"
                        min="00:00" 
                        max="23:59">
            </div>
            <div class="form-group row mx-2">
                <input type="hidden" name="user" id="user" value="<?php echo $_SESSION['userId']; ?>" />
                <button type="button" class="btn btn-primary w-100" id="getData">Get Data</button>
            </div>
        </form>
    </div>

    <div id="charts" class="col-md-9"></div>
</section>

<script>
    getMinMaxDates();
</script>

<?php } ?>

<?php
    include("template/footer.php");
	ob_flush();
?>