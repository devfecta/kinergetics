<?php
	include("template/header.php");
	require_once('configuration/Configuration.php');
	require_once('configuration/Sensor.php');
?>

<style>

    canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
    }

</style>

<section>
    <form id="searchForm" method="post">
        <label for="startDate">Sensor:</label>
        <select name="sensor" id="sensor">
            <?php
                $sensor = new Sensor();
                echo $sensor->getSensors();
            ?>
        </select>
        <label for="dataType">Data Type:</label>
        <select name="dataType" id="dataType">
            <option value="flowRate">Flow Rate</option>
            <option value="totalVolume">Total Volume</option>
            <option value="steam">Steam</option>
        </select>
        <label for="startDate">Start Date:
            <input type="date" id="startDate" name="startDate" 
                value="<?php echo date("Y-m-d", strtotime('-1 years')); ?>"
                min="<?php echo date("Y-m-d", strtotime('-5 years')); ?>" 
                max="<?php echo date("Y-m-d"); ?>">
            <input type="time" id="startTime" name="startTime"
                value="<?php echo date("H:m"); ?>"
                min="00:00" 
                max="23:59">
        </label>
        <label for="endDate">End Date:
            <input type="date" id="endDate" name="endDate" 
                value="<?php echo date("Y-m-d"); ?>"
                min="<?php echo date("Y-m-d", strtotime('-5 years')); ?>" 
                max="<?php echo date("Y-m-d"); ?>">
            <input type="time" id="endTime" name="endTime"
                value="<?php echo date("H:m"); ?>"
                min="00:00" 
                max="23:59">
        </label>
        
        <input type="hidden" name="user" id="user" value="<?php echo $_SESSION['userId']; ?>" />
        <button type="button" id="getData">Get Data</button>
    </form>

    <div id="container" style="width: 75%;">
		<canvas id="canvas"></canvas>
	</div>

</section>

<script src="javascript/api.js"></script>

<?php
    include("template/footer.php");
?>