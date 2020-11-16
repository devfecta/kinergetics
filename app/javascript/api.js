const init = () => {

    let count = 0;
    const currentDataTime = new Date();
    let initialDateTime = 
    currentDataTime.getFullYear() + 
    "-" + (currentDataTime.getMonth() + 1) + 
    "-" + currentDataTime.getDate() + 
    " " + currentDataTime.getHours() + 
    ":" + currentDataTime.getMinutes() + 
    ":" + currentDataTime.getSeconds();

    initializeRealTimeData(initialDateTime)
    .then(dataPoints => {
        buildRealTimeCharts(dataPoints);
    })
    .catch(error => console.log(error));

    
    /*
    getRealTimeData(initialDateTime);

    let interval = setInterval(
        function() { 
            if (count === 5) {
                stopInterval();
            }
            else {
                getRealTimeData(updatedDateTime);
                //console.log("Hello"); 
                count++;
            }
        }
    , 1000);

    const stopInterval = () => {
        clearInterval(interval);
        console.log("Interval Stopped"); 
    }
    */
}
// await
const initializeRealTimeData = (dateTime) => {
    const id = document.cookie.split('; ').find(c => c.startsWith('userId')).split('=')[1];
    //console.log(id);
    return getApi("DataPoints", "getDataPoints", "userId=" + id + "&dateTime=" + dateTime)
    .then(dataPoints => dataPoints)
    .catch(error => console.log(error));
}

const buildRealTimeCharts = (dataPoints) => {

    console.log(dataPoints);
    
    dataPoints.forEach((dataPoint, index) => {
        // console.log(Object.entries(report.dataPoints));

        //let dataPointJson = JSON.parse(dataPoint);
        
        let chart = null;

        chart = createChart(dataPoint.dataType + index);
        
        chart = chartData(chart, dataPoint.sensorName + " Data", dataPoint.unitType, dataPoint.dataType + " (" + dataPoint.unitType + ")");

        //let averageTotal = parseFloat(report.dataPoints.flow_rate.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.flow_rate.length).toFixed(2);
        chart = drawRealTimeChartLines(chart, dataPoint.data_points, averageTotal=0);
        buildChart(chart);
        
    });
    
}

const drawRealTimeChartLines = (chart, dataPoints, averageTotal) => {

    //console.log(dataPoints);

    dataPoints.forEach(dataPoint => {

        console.log();
        
        let date = new Date(dataPoint.messageDate);
        chart.labelsData = [...chart.labelsData, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];
        
        chart.data = [...chart.data, dataPoint.plotValues.includes('|') ? dataPoint.plotValues.split('|')[1] : dataPoint.plotValues];
        chart.lineShadingColor = [...chart.lineShadingColor, 'rgba(' + chart.color + ', 0.2)'];
        chart.lineColor = [...chart.lineColor, 'rgba(' + chart.color + ', 0.7)'];
        
        chart.average = [...chart.average, averageTotal];
        chart.averageLineShadingColor = [...chart.averageLineShadingColor, 'rgba(' + chart.averageColor + ', 0.2)'];
        chart.averageLineColor = [...chart.averageLineColor, 'rgba(' + chart.averageColor + ', 0.7)'];
        
    });
    return chart;
}



const plotRealTimeDataPoints = () => {}


const getRealTimeData = (updatedDateTime) => {

    const id = document.cookie.split('; ').find(c => c.startsWith('userId')).split('=')[1];
    console.log(id);
    // For creating the report
    //const formFields = document.querySelector("#formFields");
    //const newUser = urlParams.get('id')
    /*
    getApi("DataPoints", "getDataPoints", "userId=" + id + "lastDateTime=" + lastDateTime)
    .then(data => {
        //console.log(data);
        data.forEach(field => {

            if (field.Field !== "id" && field.Field !== "report_id" && field.Field !== "date_time") {
                

            }
            
        });

    })
    .catch(error => console.log(error));
    */
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
    console.log(json);

    getCharts(json);

    //getFlowRateData(json);
    //getTotalVolumeData(json);
    //getSteamData(json);

}

const getFormFields = () => {
    // For creating the report
    const formFields = document.querySelector("#formFields");

    getApi("Reports", "getFormFields", null)
    .then(data => {
        //console.log(data);
        data.forEach(field => {

            if (field.Field !== "id" && field.Field !== "report_id" && field.Field !== "date_time") {
                
                const fieldCell = document.createElement('div');
                fieldCell.setAttribute("class", "col-md-3 form-group text-left flex-nowrap form-check-inline");
        
                const fieldRadioButton = document.createElement('input');
                fieldRadioButton.setAttribute("class", "form-control w-50");
                fieldRadioButton.setAttribute("type", "checkbox");
                fieldRadioButton.setAttribute("name", "formFields[]");
                fieldRadioButton.setAttribute("id", field.Field);
                fieldRadioButton.setAttribute("data-value", field.Field);
                fieldRadioButton.setAttribute("value", field.Field);
                fieldRadioButton.checked = true;
        
                const fieldLabel = document.createElement('label');
                fieldLabel.setAttribute("for", field.Field);
                fieldLabel.setAttribute("class", "form-check-label w-50 text-capitalize");
                fieldLabel.innerText = field.Field.replace(/_/g, " ");
    
                fieldCell.appendChild(fieldRadioButton);
                fieldCell.appendChild(fieldLabel);
                
                formFields.appendChild(fieldCell);

            }
            
        });

    })
    .catch(error => console.log(error));
}

const createAdminHeader = (headerType, reportId) => {
    const adminButtons = document.createElement('div');
    const createReportButton = document.createElement('a');
    createReportButton.setAttribute("href", "createReport.php");
    createReportButton.setAttribute("class", "btn btn-md btn-secondary m-1");
    createReportButton.innerText = "Create Report";
    adminButtons.appendChild(createReportButton);

    switch (headerType) {
        case "dataPoints":
            const addDataPointButton = document.createElement('a');
            addDataPointButton.setAttribute("href", "addDataPoint.php?reportId=" + reportId);
            addDataPointButton.setAttribute("class", "btn btn-md btn-secondary m-1");
            addDataPointButton.innerText = "Add Data Point";
            adminButtons.appendChild(addDataPointButton);
            break;
        default:
            break;
    }

    return adminButtons;
    
}

const getCompanies = () => {

    const adminSection = document.querySelector("#adminSection");
    adminSection.appendChild(createAdminHeader(null, null));

    //<div id="accordion" class="w-100"></div>

    const companyList = document.createElement('div');
    companyList.setAttribute("class", "w-100");
    companyList.id = "accordion";
    adminSection.appendChild(companyList);

    getApi("Reports", "getCompanies", null)
    .then(data => {

        console.log(data);
        
        data.forEach(company => {

            //const companyList = document.querySelector("#accordion");

            const companyCard = document.createElement('div');
            companyCard.setAttribute("class", "card");
            // Card Header START
            const companyCardHeader = document.createElement('div');
            companyCardHeader.setAttribute("class", "card-header p-0");
            companyCardHeader.setAttribute("id", "companyHeader" + company.id);

            const companyHeader = document.createElement('h5');
            companyHeader.setAttribute("class", "mb-0");

            const companyHeaderButton = document.createElement('button');
            companyHeaderButton.setAttribute("class", "btn btn-primary py-3 w-100");
            companyHeaderButton.setAttribute("data-toggle", "collapse");
            companyHeaderButton.setAttribute("data-target", "#companyBody" + company.id);
            companyHeaderButton.setAttribute("aria-expanded", "false");
            companyHeaderButton.setAttribute("aria-controls", "companyBody" + company.id);
            companyHeaderButton.innerText = company.company;

            companyHeader.appendChild(companyHeaderButton);
            companyCardHeader.appendChild(companyHeader);
            
            companyCard.appendChild(companyCardHeader);

            companyList.appendChild(companyCard);
            // Card Header END
            // Card Body START
            const companyCardBody = document.createElement('div');
            companyCardBody.setAttribute("class", "collapse");
            companyCardBody.setAttribute("id", "companyBody" + company.id);
            companyCardBody.setAttribute("aria-labelledby", "companyHeader" + company.id);
            companyCardBody.setAttribute("data-parent", "#accordion");

            const companyBody = document.createElement('div');
            companyBody.setAttribute("class", "card-body p-0 border-0");

            const deviceList = document.createElement('div');
            deviceList.setAttribute("class", "list-group");
            
            company.reports.forEach(report => {
                const deviceLink = document.createElement('button');
                //deviceLink.setAttribute("href", "/api.php?class=Reports&method=getUserReports&report=" + report.reportId);
                //deviceLink.setAttribute("class", "list-group-item list-group-item-action");
                deviceLink.setAttribute("class", "btn btn-light py-3 w-100");
                deviceLink.setAttribute("value", report.reportId);
                deviceLink.addEventListener("click", getReportDatapoints, event);
                deviceLink.innerHTML = "<em style=\"font-size:80%\">( Report ID: " + report.reportId + " )</em> " + report.name;
                deviceList.appendChild(deviceLink);
            });
            companyBody.appendChild(deviceList);
            
            companyCardBody.appendChild(companyBody);
            companyCard.appendChild(companyCardBody);
            // Card Body END
            
        });
    })
    .catch(error => console.log(error));
}



const getReportDatapoints = (event) => {

    const adminSection = document.querySelector("#adminSection");
    adminSection.innerHTML = "";

    adminSection.appendChild(createAdminHeader("dataPoints", event.target.value));

    getApi("Reports", "getReportDatapoints", "reportId=" + event.target.value)
    .then(data => {
        console.log(data);
        const responsiveTable = document.createElement('div');
        responsiveTable.setAttribute("class", "table-responsive");

        const dataPointTable = document.createElement('table');
        dataPointTable.setAttribute("class", "table");

        if (data) {

            const tableHeader = document.createElement('thead');

            const tableHeaderRow = document.createElement('tr');

            let formFields = JSON.parse(data[0]['form_fields']);
            //console.log(formFields);

            formFields.forEach(name => {
                //console.log(name);
                const tableHeaderColumn = document.createElement('th');
                tableHeaderColumn.setAttribute("scope", "col");
                tableHeaderColumn.setAttribute("class", "text-nowrap text-capitalize border-0");
                tableHeaderColumn.innerText = name.replace(/_/g, " ");
                tableHeaderRow.appendChild(tableHeaderColumn);
                tableHeader.appendChild(tableHeaderRow);
            });

            dataPointTable.appendChild(tableHeader);

            data.forEach(dataPoints => {
                //console.log(Object.keys(dataPoints[0]));
                console.log(dataPoints);
                const tableRow = document.createElement('tr');

                formFields.forEach(name => {
                    // console.log(dataPoints[name]);
                    const tableColumn = document.createElement('td');
                    tableColumn.innerText = dataPoints[name];
                    tableRow.appendChild(tableColumn);
                    
                });

                dataPointTable.appendChild(tableRow);

            });
        }
        else {
            const notFoundRow = document.createElement('tr');
            const notFoundColumn = document.createElement('td');
            notFoundColumn.setAttribute("class", "border-0");
            notFoundColumn.innerText = "No Records Found";
            notFoundRow.appendChild(notFoundColumn);
            dataPointTable.appendChild(notFoundRow);
        }

        responsiveTable.appendChild(dataPointTable);
        adminSection.appendChild(responsiveTable);
        
    })
    .catch(error => console.log(error));
}

const getMinMaxDates = () => {

    let searchButton = document.querySelector("#getData");
    searchButton.addEventListener("click", getData);

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
 * Creates a chart canvas with an ID.
 */
const createChart = (chartId) => {
    // Remove the old chart
    (document.getElementById(chartId)) ? document.getElementById(chartId).remove() : '';

    const charts = document.querySelector('#charts');
    const chart = document.createElement('canvas');
    chart.setAttribute("class", "col-md-3");

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
    return await postApi(formJSON);
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

const getCharts = async (formJSON) => {
    formJSON.class = "Reports";
    formJSON.method = "getUserReports";
    return await postApi(formJSON)
    .then(json => {
        console.log(json);

        json.forEach((report, index) => {
            
            console.log(Object.entries(report.dataPoints));
            /*
            Object.entries(report.dataPoints).forEach(dataPoint => {
                let chart = createChart(dataPoint[0] + index);

                if (report.dataPoints.flow_rate) {
                    chart = chartData(chart, "Flow Rate Data", "GPM", "Flow Rate (GPM)");
                    let averageTotal = parseFloat(report.dataPoints.flow_rate.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.flow_rate.length).toFixed(2);
                    chart = drawChartLines(chart, report.dataPoints.flow_rate, averageTotal);
                    buildChart(chart);
                }
            });
            */
            
            let chart = null;

            if (report.dataPoints.flow_rate) {
                chart = createChart("flowRate" + index);
                chart = chartData(chart, report.device.name + " Data", "GPM", "Flow Rate (GPM)");
                let averageTotal = parseFloat(report.dataPoints.flow_rate.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.flow_rate.length).toFixed(2);
                chart = drawChartLines(chart, report.dataPoints.flow_rate, averageTotal);
                buildChart(chart);
            }
            if (report.dataPoints.total_volume) {
                chart = createChart("totalVolume" + index);
                chart = chartData(chart, report.device.name + " Data", "Gal", "Total Volume (Gal)");
                let averageTotal = parseFloat(report.dataPoints.total_volume.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.total_volume.length).toFixed(2);
                chart = drawChartLines(chart, report.dataPoints.total_volume, averageTotal);
                buildChart(chart);
            }
            if (report.dataPoints.steam) {
                chart = createChart("steam" + index);
                chart = chartData(chart, report.device.name + " Data", "LB/Hr", "Steam (LB/Hr)");
                let averageTotal = parseFloat(report.dataPoints.steam.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.steam.length).toFixed(2);
                chart = drawChartLines(chart, report.dataPoints.steam, averageTotal);
                buildChart(chart);
            }
            if (report.dataPoints.feedwater) {
                chart = createChart("feedwater" + index);
                chart = chartData(chart, report.device.name + " Data", "Fow", "Feedwater (Fow)");
                let averageTotal = parseFloat(report.dataPoints.feedwater.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.feedwater.length).toFixed(2);
                chart = drawChartLines(chart, report.dataPoints.feedwater, averageTotal);
                buildChart(chart);
            }


            if (report.dataPoints.fahrenheit) {
                chart = createChart("fahrenheit" + index);
                chart = chartData(chart, report.device.name + " Data", "Degrees", "Fahrenheit (Degrees)");
                let averageTotal = parseFloat(report.dataPoints.fahrenheit.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.fahrenheit.length).toFixed(2);
                chart = drawChartLines(chart, report.dataPoints.fahrenheit, averageTotal);
                buildChart(chart);
            }
            if (report.dataPoints.celsius) {
                chart = createChart("celsius" + index);
                chart = chartData(chart, report.device.name + " Data", "Degrees", "Celsius (Degrees)");
                let averageTotal = parseFloat(report.dataPoints.celsius.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.celsius.length).toFixed(2);
                chart = drawChartLines(chart, report.dataPoints.celsius, averageTotal);
                buildChart(chart);
            }
            
        });
    })
    .catch(error => console.log(error));

}

const drawChartLines = (chart, dataPoints, averageTotal) => {

    //console.log(dataPoints);

    dataPoints.forEach(dataPoint => {

        console.log(dataPoint.date_times);

        let date = new Date(dataPoint.date_times);
        chart.labelsData = [...chart.labelsData, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];
        
        chart.data = [...chart.data, dataPoint.values];
        chart.lineShadingColor = [...chart.lineShadingColor, 'rgba(' + chart.color + ', 0.2)'];
        chart.lineColor = [...chart.lineColor, 'rgba(' + chart.color + ', 0.7)'];
        
        chart.average = [...chart.average, averageTotal];
        chart.averageLineShadingColor = [...chart.averageLineShadingColor, 'rgba(' + chart.averageColor + ', 0.2)'];
        chart.averageLineColor = [...chart.averageLineColor, 'rgba(' + chart.averageColor + ', 0.7)'];
        
    });
    return chart;
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
/**
 * Add property values to the chart.
 * @param {json} chartData 
 */
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

const postApi = async (formData) => {

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

    formData = 'class=' + className + '&method=' + methodName + '&' + parameters;

    let url = "./api.php";

    return await fetch(url + "?" + formData)
    .then(response => response.json())
    .then(json => json)
    .catch(error => console.log(error));

}



window.onload = init;