//let steamCanvas = document.querySelector('#steamCanvas');

const init = () => {

    let searchButton = document.querySelector("#getData");
    searchButton.addEventListener("click", getData);

    //let searchButton = document.querySelector("#getSteamData");
    //searchButton.addEventListener("click", getSteamData);

}

const getData = async () => {

    // Select form
    let searchForm = document.querySelector("#searchForm");

    // Convert form data to JSON
    const json = {};
    json.device = "flowMeter";
    json.dataType = "steam";
    let formData = new FormData(searchForm);
    formData.forEach((entry, index) => {
        json[index] = entry;
    });
    //console.log(json);

    getFlowRateData(json);
    getTotalVolumeData(json);
    getSteamData(json);

}

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

const getFlowRateData = async (formJSON) => {

    let chartData = {};
    chartData.canvas = document.querySelector('#flowRateCanvas');

    chartData.canvas.innerHTML = "";
    chartData.chartTitle = "Flow Rate Data";
    
    let responseJSON = await callApi(formJSON);
    //console.log(responseJSON);

    if(responseJSON.error) {
        alert("No Flow RateRecords Found");
        return true;
    }

    chartData.unit = 'GPM';
    chartData.label = 'Flow Rate (GPM)',
    // Line Formatting
    //chartData.flow = {labelsData : []}
    chartData.labelsData = [];
    chartData.data = [];
    chartData.barBackgroundColor = [];
    chartData.barBorderColor = [];

    responseJSON.forEach(data => {
        let date = new Date(data.date_time);
        chartData.labelsData = [...chartData.labelsData, date.getHours() +":"+ date.getMinutes()];
        chartData.data = [...chartData.data, data.flow_rate];
        chartData.barBackgroundColor = [...chartData.barBackgroundColor, 'rgba(50, 100, 150, 0.2)'];
        chartData.barBorderColor = [...chartData.barBorderColor, 'rgba(50, 100, 150, 0.7)'];
    });
    
    buildChart(chartData);
    return false;
}

const getTotalVolumeData = async (formJSON) => {

    let chartData = {};
    chartData.canvas = document.querySelector('#totalVolumeCanvas');

    chartData.canvas.innerHTML = "";
    chartData.chartTitle = "Total Volume Data";
    
    let responseJSON = await callApi(formJSON);
    //console.log(responseJSON);

    if(responseJSON.error) {
        alert("No Total Volume Records Found");
        return true;
    }

    chartData.unit = 'Gallons';
    chartData.label = 'Total Volume (Gallons)',
    // Line Formatting
    chartData.labelsData = [];
    chartData.data = [];
    chartData.barBackgroundColor = [];
    chartData.barBorderColor = [];

    responseJSON.forEach(data => {
        let date = new Date(data.date_time);
        chartData.labelsData = [...chartData.labelsData, date.getHours() +":"+ date.getMinutes()];
        chartData.data = [...chartData.data, data.total_volume];
        chartData.barBackgroundColor = [...chartData.barBackgroundColor, 'rgba(100, 100, 50, 0.2)'];
        chartData.barBorderColor = [...chartData.barBorderColor, 'rgba(100, 100, 50, 0.7)'];
    });
    
    buildChart(chartData);
    return false;
}

const getSteamData = async (formJSON) => {

    let chartData = {};
    chartData.canvas = document.querySelector('#steamCanvas');

    chartData.canvas.innerHTML = "";
    chartData.chartTitle = "Steam Data";
    
    let responseJSON = await callApi(formJSON);
    //console.log(responseJSON);

    if(responseJSON.error) {
        alert("No Steam Records Found");
        return true;
    }

    chartData.unit = 'LB/Hr';
    chartData.label = 'Steam (LB/Hr)',
    // Line Formatting
    chartData.labelsData = [];
    chartData.data = [];
    chartData.barBackgroundColor = [];
    chartData.barBorderColor = [];

    responseJSON.forEach(data => {
        let date = new Date(data.date_time);
        chartData.labelsData = [...chartData.labelsData, date.getHours() +":"+ date.getMinutes()];
        chartData.data = [...chartData.data, data.steam];
        chartData.barBackgroundColor = [...chartData.barBackgroundColor, 'rgba(70, 50, 150, 0.2)'];
        chartData.barBorderColor = [...chartData.barBorderColor, 'rgba(70, 50, 150, 0.7)'];
    });
    /*
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
    */
    //console.log(chartData);
    
    buildChart(chartData);
    return false;
}

const buildChart = (chartData) => {

    let myChart = new Chart(chartData.canvas, {
        type: 'line',
        data: {
            labels: chartData.labelsData,
            datasets: [{
                label: chartData.label,
                data: chartData.data,
                backgroundColor: chartData.barBackgroundColor,
                borderColor: chartData.barBorderColor,
                borderWidth: 1,
                fill: true
            }]
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
                        labelString: 'Time'
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




const buildCharts = (chartData) => {

    let myChart = new Chart(chartData.canvas, {
        type: 'line',
        data: {
            labels: chartData.labelsData,
            datasets: [{
                label: chartData.label,
                data: chartData.steam.data,
                backgroundColor: chartData.steam.barBackgroundColor,
                borderColor: chartData.steam.barBorderColor,
                borderWidth: 1,
                fill: true
            },
            {
                label: 'Flow Rate (GPM)',
                data: chartData.flow.data,
                backgroundColor: chartData.flow.barBackgroundColor,
                borderColor: chartData.flow.barBorderColor,
                borderWidth: 1,
                fill: true
                /*
                label: 'Average ' + chartData.unit,
                data: chartData.average,
                backgroundColor: chartData.averageBackgroundColor,
                borderColor: chartData.averageBorderColor,
                borderWidth: 1,
                fill: true
                */
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
                        labelString: 'Time'
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

    let url = "./api.php";

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