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
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>

    <script src="javascript/api.js"></script>

</head>
<body>
	<header></header>
	<main class="text-center">

        <section class="container text-right">
            <a href="logout.php" class="btn btn-lg btn-secondary px-2 m-1">Logout</a> 
        </section>