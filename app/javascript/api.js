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

const getRandomColor = () => {
    return (Math.floor(Math.random() * 200) + 1) + ", " + (Math.floor(Math.random() * 200) + 1) + ", " + (Math.floor(Math.random() * 200) + 1);
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
/**
 * Create a chart.
 */
const createChart = (chartId) => {
    const charts = document.querySelector('#charts');
    const chart = document.createElement('canvas');
    chart.id = chartId;
    charts.appendChild(chart);
    return chart;
}
/**
 * Get data points for the chart.
 * @returns json of data points
 */
const getDataPoints = async (deviceName, formJSON) => {
    formJSON.device = deviceName; // Data type based on device.
    formJSON.class = "Reports";
    formJSON.method = "getDeviceReportData";
    //console.log(responseJSON);
    return await callApi(formJSON);
}
/**
 * Chart specific information.
 * @returns json of chart information
 */
const chartData = (chart, title, verticalLabel, horizontalLabel) => {
    let chartData = {};
    chartData.canvas = chart;
    chartData.canvas.innerHTML = "";
    chartData.chartTitle = title;
    chartData.unit = verticalLabel;
    chartData.label = horizontalLabel;
    // Line Formatting
    //chartData.flow = {labelsData : []}
    chartData.labelsData = [];
    chartData.data = [];
    chartData.color = getRandomColor();
    chartData.lineShadingColor = [];
    chartData.lineColor = [];
    // Average Line Formatting
    chartData.average = [];
    chartData.averageColor = "175, 175, 175";
    chartData.averageLineShadingColor = [];
    chartData.averageLineColor = [];
    
    return chartData;
}

const getFlowRateData = async (formJSON) => {
    let chart = createChart("flowRateCanvas");
    
    let dataPoints = await getDataPoints("flowMeter", formJSON).then(json => json).catch(error => console.log(error));
    chart = chartData(chart, "Flow Rate Data", "GPM", "Flow Rate (GPM)");
     
    if(!dataPoints) {
        alert("No Record Found");
        chart.canvas.innerHTML = "No Record Found";
    }
    else {
        let averageTotal = parseFloat(dataPoints.reduce((total, data) => total + Number(data.flow_rate), 0) / dataPoints.length).toFixed(2);

        dataPoints.forEach(data => {
            let date = new Date(data.date_time);
            chart.labelsData = [...chart.labelsData, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];
            chart.data = [...chart.data, data.flow_rate];
            chart.lineShadingColor = [...chart.lineShadingColor, 'rgba(' + chart.color + ', 0.2)'];
            chart.lineColor = [...chart.lineColor, 'rgba(' + chart.color + ', 0.7)'];
            
            chart.average = [...chart.average, averageTotal];
            chart.averageLineShadingColor = [...chart.averageLineShadingColor, 'rgba(' + chart.averageColor + ', 0.2)'];
            chart.averageLineColor = [...chart.averageLineColor, 'rgba(' + chart.averageColor + ', 0.7)'];
        });
    }

    buildChart(chart);
}

const getTotalVolumeData = async (formJSON) => {
    let chart = createChart("totalVolumeCanvas");
    chart = chartData(chart, "Total Volume Data", "Gallons", "Total Volume (Gallons)");
    let dataPoints = await getDataPoints("flowMeter", formJSON).then(json => json).catch(error => console.log(error));
    
    if(!dataPoints) {
        alert("No Record Found");
        chart.canvas.innerHTML = "No Record Found";
    }
    else {
        let averageTotal = parseFloat(dataPoints.reduce((total, data) => total + Number(data.total_volume), 0) / dataPoints.length).toFixed(2);

        dataPoints.forEach(data => {
            let date = new Date(data.date_time);
            chart.labelsData = [...chart.labelsData, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];
            chart.data = [...chart.data, data.total_volume];
            chart.lineShadingColor = [...chart.lineShadingColor, 'rgba(' + chart.color + ', 0.2)'];
            chart.lineColor = [...chart.lineColor, 'rgba(' + chart.color + ', 0.7)'];

            chart.average = [...chart.average, averageTotal];
            chart.averageLineShadingColor = [...chart.averageLineShadingColor, 'rgba(' + chart.averageColor + ', 0.2)'];
            chart.averageLineColor = [...chart.averageLineColor, 'rgba(' + chart.averageColor + ', 0.7)'];
        });
    }

    buildChart(chart);
}

const getSteamData = async (formJSON) => {
    let chart = createChart("steamCanvas");
    chart = chartData(chart, "Steam Data", "LB/Hr", "Steam (LB/Hr)");
    let dataPoints = await getDataPoints("flowMeter", formJSON).then(json => json).catch(error => console.log(error));
    
    if(!dataPoints) {
        alert("No Record Found");
        chart.canvas.innerHTML = "No Record Found";
    }
    else {
        let averageTotal = parseFloat(dataPoints.reduce((total, data) => total + Number(data.steam), 0) / dataPoints.length).toFixed(2);

        dataPoints.forEach(data => {
            let date = new Date(data.date_time);
            chart.labelsData = [...chart.labelsData, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];
            chart.data = [...chart.data, data.steam];
            chart.lineShadingColor = [...chart.lineShadingColor, 'rgba(' + chart.color + ', 0.2)'];
            chart.lineColor = [...chart.lineColor, 'rgba(' + chart.color + ', 0.7)'];
            
            chart.average = [...chart.average, averageTotal];
            chart.averageLineShadingColor = [...chart.averageLineShadingColor, 'rgba(' + chart.averageColor + ', 0.2)'];
            chart.averageLineColor = [...chart.averageLineColor, 'rgba(' + chart.averageColor + ', 0.7)'];
        });
    }

    buildChart(chart);
}

const buildChart = (chartData) => {

    let myChart = new Chart(chartData.canvas, {
        type: 'line',
        data: {
            labels: chartData.labelsData,
            datasets: [
                {
                    label: chartData.label,
                    data: chartData.data,
                    backgroundColor: chartData.lineShadingColor,
                    borderColor: chartData.lineColor,
                    borderWidth: 1,
                    fill: true
                },
                {
                    label: 'Average ' + chartData.unit,
                    data: chartData.average,
                    backgroundColor: chartData.averageLineShadingColor,
                    borderColor: chartData.averageLineColor,
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
        //console.log(response);
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