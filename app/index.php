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

    <section class="container-fluid text-right">
        <?php
            if ($_SESSION['type'] > 0) {
        ?>
                <a href="register.php" class="btn btn-lg btn-secondary m-1">Register an Account</a> 
        <?php
            }
        ?>
        <a href="logout.php" class="btn btn-lg btn-secondary px-2 m-1">Logout</a> 
    </section>


<section class="container-fluid row">
    <div class="col-md-12">
        <h1><?php echo $_SESSION['company']; ?> - Energy Matrix</h1>
    </div>
    
    <div class="col-md-3">
        <form id="searchForm" method="post">
            <div class="form-group row mx-2">
                <label for="startDate">Start Date: </label>
                    <input type="date" class="form-control" id="startDate" name="startDate" 
                        value="<?php echo date("Y-m-d", strtotime('-1 years')); ?>"
                        min="<?php echo date("Y-m-d", strtotime('-5 years')); ?>" 
                        max="<?php echo date("Y-m-d"); ?>">
                    <input type="time" class="form-control" id="startTime" name="startTime"
                        value="<?php echo date("H:m"); ?>"
                        min="00:00" 
                        max="23:59">
                
            </div>
            <div class="form-group row mx-2">
                <label for="endDate">End Date: </label>
                    <input type="date" class="form-control" id="endDate" name="endDate" 
                        value="<?php echo date("Y-m-d"); ?>"
                        min="<?php echo date("Y-m-d", strtotime('-5 years')); ?>" 
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
    <div class="col-md-9">

        <canvas id="flowRateCanvas"></canvas>

        <canvas id="totalVolumeCanvas"></canvas>

        <canvas id="steamCanvas"></canvas>

    </div>    

</section>

<script src="javascript/api.js"></script>

<?php
    include("template/footer.php");
	ob_flush();
?>