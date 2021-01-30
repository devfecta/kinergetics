<?php
    ob_start();
    session_start();
	include("template/header.php");
	//require_once('configuration/Configuration.php');
	//require_once('configuration/Sensor.php');
?>

<section class="row">
    <div class="col-md-12">
        <h1><?php echo $_SESSION['company']; ?> - Energy Matrix</h1>
    </div>
</section>

<?php if ((float)$_SESSION['type'] > 0) { ?>
<!-- Admin Section -->
<section id="adminSection" class="container-fluid row"></section>

<script>
    getCompanies();
</script>

<?php } else { ?>
<!-- User Section -->
<section id="userSensors" class="container-fluid">
    <div id="sensors" class="d-flex justify-content-around row">
        
    </div>
</section>
<script>
    getUserSensors();
</script>

<script>
    getMinMaxDates();
</script>

<?php } ?>

<?php
    include("template/footer.php");
	ob_flush();
?>