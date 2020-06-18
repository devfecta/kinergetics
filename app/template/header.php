<?php
    ob_start();
    session_start();

    if (session_id() && !isset($_SESSION['userId'])) {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Matrix</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>

    <script src="javascript/api.js"></script>

</head>
<body>
	<header></header>
	<main class="container text-center">

        <section class="container-fluid text-right">
            <?php
                if ($_SESSION['type'] > 0) {
            ?>
                <!-- <a href="register.php" class="btn btn-lg btn-secondary m-1">Register an Account</a> -->
                <a href="createReport.php" class="btn btn-lg btn-secondary m-1">Create a Report</a> 
            <?php
                }
            ?>
            <a href="logout.php" class="btn btn-lg btn-secondary px-2 m-1">Logout</a> 
        </section>