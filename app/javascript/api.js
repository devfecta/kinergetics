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
    //json.device = "flowMeter";
    //json.dataType = "steam";
    let formData = new FormData(searchForm);
    formData.forEach((entry, index) => {
        json[index] = entry;
    });
    //console.log(json);

    getFlowRateData(json);
    getTotalVolumeData(json);
    getSteamData(json);

}

const getMinMaxDates = () => {

    const startDate = document.querySelector("#startDate");
    const startTime = document.querySelector("#startTime");
    
    const endDate = document.querySelector("#endDate");
    const endTime = document.querySelector("#endTime");

    getApi("Reports", "getMinMaxDates", null)
    .then(data => {
        let minimumDate = new Date(data.minimum);
        startDate.value = minimumDate.toISOString().slice(0,10);
        minimumDate.setFullYear(minimumDate.getFullYear() - 5);
        startDate.min = minimumDate.toISOString().slice(0,10);
        startTime.value = ("0" + minimumDate.getHours()).slice(-2) + ":" + ("0" + minimumDate.getMinutes()).slice(-2);

        let maximumDate = new Date(data.maximum);
        endDate.value = maximumDate.toISOString().slice(0,10);
        maximumDate.setFullYear(maximumDate.getFullYear() - 5);
        endDate.min = maximumDate.toISOString().slice(0,10);
        endTime.value = ("0" + maximumDate.getHours()).slice(-2) + ":" + ("0" + maximumDate.getMinutes()).slice(-2);
    })
    .catch(error => console.log(error));
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
    /**
     * Create the canvas for the chart.
     */
    const charts = document.querySelector('#charts');
    const chart = document.createElement('canvas');
    chart.id = 'flowRateCanvas';
    charts.appendChild(chart);
    /**
     * Get data points for the chart.
     */
    formJSON.device = "flowMeter"; // Data type based on device.
    formJSON.class = "Reports";
    formJSON.method = "getDeviceReportData";
    let responseJSON = await callApi(formJSON);
    console.log(responseJSON);
    /**
     * Chart specific information.
     */
    let chartData = {};
    chartData.canvas = chart;
    chartData.canvas.innerHTML = "";
    chartData.chartTitle = "Flow Rate Data";
    chartData.unit = 'GPM';
    chartData.label = 'Flow Rate (GPM)';
    // Line Formatting
    //chartData.flow = {labelsData : []}
    chartData.labelsData = [];
    chartData.data = [];
    chartData.barBackgroundColor = [];
    chartData.barBorderColor = [];

    if(!responseJSON) {
        alert("No Record Found");
        chartData.canvas.innerHTML = "No Record Found";
    }
    else {
        responseJSON.forEach(data => {
            let date = new Date(data.date_time);
            chartData.labelsData = [...chartData.labelsData, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];
            chartData.data = [...chartData.data, data.flow_rate];
            chartData.barBackgroundColor = [...chartData.barBackgroundColor, 'rgba(50, 100, 150, 0.2)'];
            chartData.barBorderColor = [...chartData.barBorderColor, 'rgba(50, 100, 150, 0.7)'];
        });
    }

    buildChart(chartData);
}

const getTotalVolumeData = async (formJSON) => {
    /**
     * Create the canvas for the chart.
     */
    const charts = document.querySelector('#charts');
    const chart = document.createElement('canvas');
    chart.id = 'totalVolumeCanvas';
    charts.appendChild(chart);
    /**
     * Get data points for the chart.
     */
    formJSON.device = "flowMeter"; // Data type based on device.
    formJSON.class = "Reports";
    formJSON.method = "getDeviceReportData";
    let responseJSON = await callApi(formJSON);
    console.log(responseJSON);
    /**
     * Chart specific information.
     */
    let chartData = {};
    chartData.canvas = chart;
    chartData.canvas.innerHTML = "";
    chartData.chartTitle = "Total Volume Data";
    chartData.unit = 'Gallons';
    chartData.label = 'Total Volume (Gallons)';
    // Line Formatting
    //chartData.flow = {labelsData : []}
    chartData.labelsData = [];
    chartData.data = [];
    chartData.barBackgroundColor = [];
    chartData.barBorderColor = [];

    if(!responseJSON) {
        alert("No Record Found");
        chartData.canvas.innerHTML = "No Record Found";
    }
    else {
        responseJSON.forEach(data => {
            let date = new Date(data.date_time);
            chartData.labelsData = [...chartData.labelsData, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];
            chartData.data = [...chartData.data, data.total_volume];
            chartData.barBackgroundColor = [...chartData.barBackgroundColor, 'rgba(50, 100, 150, 0.2)'];
            chartData.barBorderColor = [...chartData.barBorderColor, 'rgba(50, 100, 150, 0.7)'];
        });
    }

    buildChart(chartData);
}

const getSteamData = async (formJSON) => {
    /**
     * Create the canvas for the chart.
     */
    const charts = document.querySelector('#charts');
    const chart = document.createElement('canvas');
    chart.id = 'steamCanvas';
    charts.appendChild(chart);
    /**
     * Get data points for the chart.
     */
    formJSON.device = "flowMeter"; // Data type based on device.
    formJSON.class = "Reports";
    formJSON.method = "getDeviceReportData";
    let responseJSON = await callApi(formJSON);
    console.log(responseJSON);
    /**
     * Chart specific information.
     */
    let chartData = {};
    chartData.canvas = chart;
    chartData.canvas.innerHTML = "";
    chartData.chartTitle = "Total Volume Data";
    chartData.unit = 'LB/Hr';
    chartData.label = 'Steam (LB/Hr)';
    // Line Formatting
    //chartData.flow = {labelsData : []}
    chartData.labelsData = [];
    chartData.data = [];
    chartData.barBackgroundColor = [];
    chartData.barBorderColor = [];

    if(!responseJSON) {
        alert("No Record Found");
        chartData.canvas.innerHTML = "No Record Found";
    }
    else {
        responseJSON.forEach(data => {
            let date = new Date(data.date_time);
            chartData.labelsData = [...chartData.labelsData, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];
            chartData.data = [...chartData.data, data.steam];
            chartData.barBackgroundColor = [...chartData.barBackgroundColor, 'rgba(50, 100, 150, 0.2)'];
            chartData.barBorderColor = [...chartData.barBorderColor, 'rgba(50, 100, 150, 0.7)'];
        });
    }

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

    buildChart(chartData);
}

////////////////////////////////
/*
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
    
    buildChart(chartData);
    return false;
}
*/
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

    let params = new URLSearchParams(formData);
    //console.log(formData);

    let url = "./api.php";

    return await fetch(url, {
        method: 'POST',
        body: new URLSearchParams(formData),
        headers: new Headers({
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        })
    })
    .then(response => {
        console.log(response);
        return response.json();
    })
    .then(data => data)
    .catch(error => console.log(error.toString()));

}

const getApi = async (className, methodName, parameters) => {

    formData = 'class=' + className + '&method=' + methodName + '&=parameters=' + parameters;

    let url = "./api.php";

    return await fetch(url + "?" + formData)
    .then(response => response.json())
    .then(json => json)
    .catch(error => console.log(error));

}



window.onload = init;