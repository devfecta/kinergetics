//let steamCanvas = document.querySelector('#steamCanvas');

const init = () => {

    let searchButton = document.querySelector("#getSteamData");
    searchButton.addEventListener("click", getSteamData);

}


const getSteamData = async () => {

    let chartData = {};
    chartData.canvas = document.querySelector('#steamCanvas');

    chartData.canvas.innerHTML = "";
    chartData.chartTitle = "Steam Data";
    // Select form
    let searchForm = document.querySelector("#steamForm");

    // Convert form data to JSON
    const json = {};
    json.device = "flowMeter";
    json.dataType = "steam";
    let formData = new FormData(searchForm);
    formData.forEach((entry, index) => {
        json[index] = entry;
    });
    //console.log(json);
    /*
        dataType: "steam"
        device: "flowMeter"
        endDate: "2020-04-11"
        endTime: "12:04"
        startDate: "2019-04-11"
        startTime: "12:04"
        user: "1"
    */
    
    let responseJSON = await callApi(json);
    console.log(responseJSON);

    if(responseJSON.error) {
        alert("No Records Found");
        return false;
    }

    chartData.unit = 'LB/Hr';
    // Line Formatting
    chartData.labelsData = [];
    chartData.data = [];
    chartData.barBackgroundColor = [];
    chartData.barBorderColor = [];
    total = 0;

    responseJSON.forEach(data => {
        let date = new Date(data.date_time);
        chartData.labelsData = [...chartData.labelsData, date.getFullYear() +"-"+ date.getMonth() +"-"+ date.getDate() +"\n"+ date.getHours() +":"+ date.getMinutes()];
        chartData.data = [...chartData.data, data.steam];
        chartData.barBackgroundColor = [...chartData.barBackgroundColor, 'rgba(70, 50, 150, 0.2)'];
        chartData.barBorderColor = [...chartData.barBorderColor, 'rgba(70, 50, 150, 0.7)'];
        total += Number(data.steam);
    });
    // Average Line Formatting
    chartData.average = [];
    chartData.averageBackgroundColor = [];
    chartData.averageBorderColor = [];

    let averageTotal = parseFloat(total / responseJSON.length).toFixed(2);

    responseJSON.forEach(data => {
        chartData.average = [...chartData.average, averageTotal];
        chartData.averageBackgroundColor = [...chartData.averageBackgroundColor, 'rgba(175, 175, 175, 0.2)'];
        chartData.averageBorderColor = [...chartData.averageBorderColor, 'rgba(175, 175, 175, 0.7)'];
    });

    console.log(chartData);
    
    buildChart(chartData);

/*
data_point: "1"
date_time: "2018-12-12 08:45:24"
error: "0"
feedwater: "0"
flow_rate: "1.02"
id: "1"
report_id: "1"
steam: "499.80"
time_lapse: "0.02"
total_volume: "96.00"
*/
    
}

const buildChart = (chartData) => {

    let myChart = new Chart(chartData.canvas, {
        type: 'line',
        data: {
            labels: chartData.labelsData,
            datasets: [{
                label: chartData.data.length + ' Data Points',
                data: chartData.data,
                backgroundColor: chartData.barBackgroundColor,
                borderColor: chartData.barBorderColor,
                borderWidth: 1,
                fill: true
            },
            {
                label: 'Average ' + chartData.unit,
                data: chartData.average,
                backgroundColor: chartData.averageBackgroundColor,
                borderColor: chartData.averageBorderColor,
                borderWidth: 1,
                fill: true
            }
            ]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: chartData.chartTitle
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Date-Time'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: chartData.unit
                    }
                }]
            }
            /*
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
            */
        }
    });

}

const callApi = async (formData) => {

    let url = "http://localhost/app/api.php";

    return await fetch(url, {
        method: 'POST',
        body: new URLSearchParams(formData),
        headers: new Headers({
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        })
    })
    .then(response => response.json())
    .then(data => data);

}



window.onload = init;