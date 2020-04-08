<?php
	include("template/header.php");
	require_once('configuration/Configuration.php');
	require_once('configuration/Sensor.php');
?>

<script src="javascript/api.js"></script>

<style>

    canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
    }

</style>



<section>

	<label for="startDate">Sensor:</label>
    <select name="sensor" id="sensor">
		<?php
			$sensor = new Sensor();
			echo $sensor->getSensors();
		?>
	</select>
	<label for="startDate">Start Date:</label>
    <input type="date" name="startDate" id="startDate" value="2018-12-13" min="2018-01-01" max="2018-12-31" />
	<label for="endDate">End Date:</label>
	<input type="date" name="endDate" id="endDate" value="2018-12-14" min="2018-01-01" max="2018-12-31" />
    <input type="hidden" name="user" id="user" value="<?php echo $_SESSION['userId']; ?>" />
	<button type="button" id="getData">Get Data</button>

    <div id="container" style="width: 75%;">
		<canvas id="canvas"></canvas>
	</div>

</section>

<script>

let ctx = document.querySelector('canvas');
let myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

</script>

<script>
    
    

    
/*
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
	});
	*/

</script>

<?php
    include("template/footer.php");
?>