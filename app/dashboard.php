<?php
    ob_start();
    session_start();
	include("template/header.php");
?>

<section class="row">
    <div class="col-md-12">
        <h1><?php echo $_SESSION['company']; ?> - Energy Matrix</h1>
    </div>
</section>

<?php if ((float)$_SESSION['type'] > 0) { ?>
<!-- Admin Section -->
<section id="adminSection" class="container-fluid"></section>

<script>
    getCompanies();
</script>

<?php } else { ?>

<?php } ?>

<?php
    include("template/footer.php");
	ob_flush();
?>