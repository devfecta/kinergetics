const init = () => {
   // 
    /*
    const currentDataTime = new Date();
    let initialDateTime = 
    currentDataTime.getFullYear() + 
    "-" + (currentDataTime.getMonth() + 1) + 
    "-" + currentDataTime.getDate() + 
    " " + currentDataTime.getHours() + 
    ":" + currentDataTime.getMinutes() + 
    ":" + currentDataTime.getSeconds();

    initializeRealTimeData(initialDateTime)
    .then(sensors => {
        const charts = initializeRealTimeCharts(sensors);
    })
    .catch(error => console.log(error));
    */
}
/**
 * Gets and creates the HTML button elements for a specific user's sensors.
 */
const getUserSensors = () => {
    // Logs out after idle for 1 hour.
    const userSensors = document.querySelector("#userSensors");
    //userSensors.appendChild(createAdminHeader(null, null));
    const sensorList = document.createElement('div');
    sensorList.setAttribute("class", "d-flex justify-content-around row");
    sensorList.id = "sensors";
    userSensors.appendChild(sensorList);

    if (document.cookie.includes('; ') && document.cookie.includes('userId')) {
        const userId = document.cookie.split('; ').find(c => c.startsWith('userId')).split('=')[1];

        getApi("Sensors", "getUserSensors", "userId=" + userId)
        .then(sensors => {

            console.log(sensors);

            sensors.forEach(sensor => {
    
                const sensorButton = document.createElement('button');
                sensorButton.setAttribute("class", "btn btn-primary m-2 py-3 col-md-3");
                sensorButton.onclick = () => {window.location.href = "sensor.php?sensorId="+sensor.id};
                //sensorButton.setAttribute("data-toggle", "collapse");
                //sensorButton.setAttribute("data-target", "#sensorBody" + sensor.id);
                //sensorButton.setAttribute("aria-expanded", "false");
                //sensorButton.setAttribute("aria-controls", "sensorBody" + sensor.id);
                sensorButton.innerHTML = '<span class="fas fa-satellite-dish"></span> ' + sensor.sensor_name;
                sensorList.appendChild(sensorButton);

            });

        })
        .catch(error => console.log(error));
    }
    else {
        alert("logging out");
        location.href = './logout.php';
    }
}
/**
 * Gets the chart(s) for a specific sensor, and can be used to get data points within a specific time frame.
 *
 * @param   {string}  startDateTime  Start date and time
 * @param   {string}  endDateTime  End date and time
 */
const getSensorChart = (startDateTime, endDateTime) => {
    // Sets the date picker values.
    getMinMaxDates();

    if (document.cookie.includes('; ') && document.cookie.includes('userId')) {
        const userId = document.cookie.split('; ').find(c => c.startsWith('userId')).split('=')[1];
        const urlParams = new URLSearchParams(window.location.search);
        
        getApi("Sensor", "getSensor", "userId=" + userId + "&sensorId=" + urlParams.get("sensorId"))
        .then(sensor => {

            console.log(sensor);
            return sensor;

            /*
            sensors.forEach(sensor => {
    
                const sensorButton = document.createElement('button');
                sensorButton.setAttribute("class", "btn btn-primary m-2 py-3 col-md-3");
                sensorButton.onclick = () => {window.location.href = "sensor.php?sensorId="+sensor.id};
                sensorButton.setAttribute("data-toggle", "collapse");
                sensorButton.setAttribute("data-target", "#sensorBody" + sensor.id);
                sensorButton.setAttribute("aria-expanded", "false");
                sensorButton.setAttribute("aria-controls", "sensorBody" + sensor.id);
                sensorButton.innerHTML = '<span class="fas fa-satellite-dish"></span> ' + sensor.sensor_name;
                sensorList.appendChild(sensorButton);

            });
            */
        })
        .then(sensor => {

            return getApi("DataPoints", "getSensorDataTypes", "sensorId=" + urlParams.get("sensorId"))
            .then(dataTypes => {

                console.log(dataTypes);
                sensor.dataTypes = dataTypes;
                return sensor;
                
            })
            .catch(error => console.log(error));

        })
        .then(sensor => {
            console.log(sensor);
            startDateTime = (startDateTime === null) ? "null" : startDateTime + ":00";
            endDateTime = (endDateTime === null) ? "null" : endDateTime + ":59";
            
            return getApi("DataPoints", "getSensorDataPoints", "userId=" + userId + "&sensorId=" + urlParams.get("sensorId") + "&startDateTime=" + startDateTime + "&endDateTime=" + endDateTime)
            .then(dataPoints => {

                console.log(dataPoints);

                sensor.dataTypes.forEach(dataType => {
                    
                    let chart = null;

                    let points = dataPoints.filter(function(dataPoint) {
                        return dataPoint.data_type == dataType.data_type;
                    });
                    // Create charts here
                    console.log(points);
                    let chartId = sensor.id + "-" + dataType.data_type.replace(" ", "_");
                    chart = createChart(chartId);
                    // Title the Chart and Label the Chart's Axes
                    chart = chartData(chart, sensor.sensor_name + " Data", dataType.data_type);
                    
                    plotDataPoints(chart, points);
                    
                    buildChart(chart);

                });
                
            })
            .catch(error => console.log(error));
            
        })
        .catch(error => console.log(error));

    }

}
/**
 * Creates the HTML element for a chart and returns JSON of the chart data.
 *
 * @param   {string}  chartId  The ID of the the HTML element for the chart.
 *
 * @return  {json}  JSON of chart data.
 */
const createChart = (chartId) => {
    // Remove the old chart
    (document.getElementById(chartId)) ? document.getElementById(chartId).remove() : '';

    const charts = document.querySelector('#charts');
    const chart = document.createElement('canvas');
    chart.setAttribute("class", "col-lg-6 col-md");

    chart.id = chartId;
    charts.appendChild(chart);
    return chart;
}
/**
 * Sets chart specific information.
 * @returns  {json}  JSON of chart information.
 */
const chartData = (chart, title, verticalLabel) => {
    //console.log(verticalLabel);
    let chartData = {};
    chartData.canvas = chart;
    chartData.canvas.innerHTML = "";
    chartData.chartTitle = title;
    chartData.unit = verticalLabel;
    // Line Formatting
    //chartData.flow = {labelsData : []}
    chartData.datasets = [];
    chartData.labelsData = [];
    return chartData;
}
/**
 * Adds the data points to the chart, along with x-axis information and returns JSON of the chart data.
 *
 * @param   {json}  chart  JSON of chart data.
 * @param   {json}  dataPoints  JSON of data point information.
 *
 * @return  {json}  JSON of chart data.
 */
const plotDataPoints = (chart, dataPoints) => {

    let xAxislabels = []; // Time line
    let pointData = []; // Data points
    let lineShadingColor = []; // Line background color
    let lineColor = []; // Line color
    const pointColor = getRandomColor(); // Color for the line and background

    dataPoints.forEach(point => {

        const date = new Date(point.date_time);
        xAxislabels = [...xAxislabels, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];

        pointData = [...pointData, point.data_value];

        lineShadingColor = [...lineShadingColor, 'rgba(' + pointColor + ', 0.2)'];
        lineColor = [...lineColor, 'rgba(' + pointColor + ', 0.7)'];

    });

    /**
     * label: Colored key at the top of the chart and in the data point pop-up label.
     * data: Array of data points.
     * backgroundColor: Array of color for the background color of the data points.
     * borderColor: Array of color for the line color of the data points.
     * borderWidth: Width of the line for the data points.
     * fill: Show the background color for the line of the data points.
     */

    chart.datasets = [...chart.datasets, {
        label: dataPoints[0].data_type
        , data: pointData
        , backgroundColor: lineShadingColor
        , borderColor: lineColor
        , borderWidth: 1
        , fill: true
    }];

    chart.label = xAxislabels; // x-axis labels

    return chart;
}
/**
 * Adds property values to the chart.
 * @param {json}  chartData  JSON of chart information
 */
const buildChart = (chartData) => {

    //console.log(chartData.datasets);

    const myChart = new Chart(chartData.canvas, {
        type: 'line',
        data: {
            labels: chartData.label
            , datasets: chartData.datasets
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

const getData = async () => {
    // Select form
    let searchForm = document.querySelector("#searchForm");
    // Convert form data to JSON
    const json = {};

    let formData = new FormData(searchForm);

    formData.forEach((entry, index) => {
        json[index] = entry;
    });

    json.startDateTime = searchForm.querySelector("#startDate").value + " "+ searchForm.querySelector("#startTime").value;
    json.endDateTime = searchForm.querySelector("#endDate").value + " "+ searchForm.querySelector("#startTime").value;

    //console.log(json.startDateTime + " = " + json.endDateTime);

    getSensorChart(json.startDateTime, json.endDateTime);

//    getCharts(json);

}



// Admin Functions






/**
 * Get initial real time data, and start at the current date and time.
 * OLD
 * @param {string} dateTime 
 */
const initializeRealTimeData = (dateTime) => {
    // Logs out after idle for 1 hour.
    if (document.cookie.includes('; ') && document.cookie.includes('userId')) {
        const id = document.cookie.split('; ').find(c => c.startsWith('userId')).split('=')[1];
        //console.log(dateTime);
        return getApi("DataPoints", "getDataPoints", "userId=" + id + "&startDateTime=" + dateTime)
        .then(dataPoints => dataPoints)
        .catch(error => console.log(error));
    }
    else {
        alert("logging out");
        location.href = './logout.php';
    }
}
/**
 * Creates blank charts
 * OLD
 * @param {array} dataPoints 
 */
const initializeRealTimeCharts = (sensors) => {

    sensors.forEach((sensor, index) => {
        //console.log(sensor);
        // console.log(Object.entries(report.dataPoints));
        let chart = null;
        //let chartId = dataPoint.dataType + index;
        let chartId = sensor.sensorID + "-" + index;
        // Create the Chart
        chart = createChart(chartId);
        // Title the Chart and Label the Chart's Axes
        // REMOVE chart = chartData(chart, sensor.sensorName + " Data", sensor.unitType, dataPoint.dataType + " (" + sensor.unitType + ")");
        chart = chartData(chart, sensor.sensorName + " Data", "", "");
        //let averageTotal = parseFloat(report.dataPoints.flow_rate.reduce((total, data) => total + Number(data.values), 0) / report.dataPoints.flow_rate.length).toFixed(2);
        
        chart = drawRealTimeChartLines(chart, sensor.data_points, averageTotal=0);
        
        buildChart(chart);
        
    });

}
//  OLD
const drawRealTimeChartLines = (chart, dataPoints, averageTotal) => {

    let date = "";

    //let dataSets = [];
    let xAxislabels = [];
    let pointData = [];
    let lineShadingColor = [];
    let lineColor = [];

    let pointColor = "";

    for (const key of Object.keys(dataPoints)) {
        
        if (Object.keys(dataPoints).length > 1) {

            xAxislabels = [];
            pointData = [];
            lineShadingColor = [];
            lineColor = [];

            pointColor = getRandomColor();

            dataPoints[key].forEach(point => {

                date = new Date(point.dateTime);
                xAxislabels = [...xAxislabels, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];

                pointData = [...pointData, point.value];

                lineShadingColor = [...lineShadingColor, 'rgba(' + pointColor + ', 0.2)'];
                lineColor = [...lineColor, 'rgba(' + pointColor + ', 0.7)'];

            });

            chart.datasets = [...chart.datasets, {
                label: key 
                , data: pointData
                , backgroundColor: lineShadingColor
                , borderColor: lineColor
                , borderWidth: 1
                , fill: true
            }];

        }
        else {

            pointColor = getRandomColor();

            dataPoints[key].forEach(point => {

                date = new Date(point.dateTime);
                xAxislabels = [...xAxislabels, date.getHours() +":"+ ("0" + date.getMinutes()).slice(-2)];

                pointData = [...pointData, point.value];

                lineShadingColor = [...lineShadingColor, 'rgba(' + pointColor + ', 0.2)'];
                lineColor = [...lineColor, 'rgba(' + pointColor + ', 0.7)'];

            });

            chart.datasets = [...chart.datasets, {
                label: key 
                , data: pointData
                , backgroundColor: lineShadingColor
                , borderColor: lineColor
                , borderWidth: 1
                , fill: true
            }];
            
        }

    }

//    console.log(chart.datasets);

    chart.label = xAxislabels; // x-axis labels

    return chart;
}


/** REMOVE IF NOT NEEDED
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
*/
const createAdminHeader = (headerType, reportId) => {
    const adminButtons = document.createElement('div');

    const dashboardButton = document.createElement('a');
    dashboardButton.setAttribute("href", "index.php");
    dashboardButton.setAttribute("class", "btn btn-md btn-secondary m-1");
    dashboardButton.innerText = "Dashboard";
    adminButtons.appendChild(dashboardButton);

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

        //console.log(data);
        
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
            companyHeaderButton.setAttribute("class", "btn btn-primary py-4 w-100");
            companyHeaderButton.setAttribute("data-toggle", "collapse");
            companyHeaderButton.setAttribute("data-target", "#companyBody" + company.id);
            companyHeaderButton.setAttribute("aria-expanded", "false");
            companyHeaderButton.setAttribute("aria-controls", "companyBody" + company.id);
            companyHeaderButton.innerHTML = '<span class="fas fa-building"></span> ' + company.company;

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
        //console.log(data);
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
/**
 * Gets the minimum and maximum dates based on a specific sensor's data points.
 * Then assigns them to the appropriate HTML date elements.
 */
const getMinMaxDates = () => {

    if (document.cookie.includes('; ') && document.cookie.includes('userId')) {

        const userId = document.cookie.split('; ').find(c => c.startsWith('userId')).split('=')[1];
        const urlParams = new URLSearchParams(window.location.search);

        let searchButton = document.querySelector("#getData");
        searchButton.addEventListener("click", getData);

        

        getApi("Reports", "getMinMaxDates", "userId=" + userId + "&sensorId=" + urlParams.get('sensorId'))
        .then(data => {

            const startDate = document.querySelector("#startDate");
            const startTime = document.querySelector("#startTime");
            
            const endDate = document.querySelector("#endDate");
            const endTime = document.querySelector("#endTime");
            
            let minimumDate = new Date(data.minimum);
            startDate.value = minimumDate.toLocaleDateString("fr-CA");
            minimumDate.setFullYear(minimumDate.getFullYear() - 5);
            startDate.min = minimumDate.toLocaleDateString("fr-CA");
            startTime.value = ("0" + minimumDate.getHours()).slice(-2) + ":" + ("0" + minimumDate.getMinutes()).slice(-2);

            let maximumDate = new Date(data.maximum);
            endDate.value = maximumDate.toLocaleDateString("fr-CA");
            maximumDate.setFullYear(maximumDate.getFullYear() - 5);
            endDate.min = maximumDate.toLocaleDateString("fr-CA");
            endTime.value = ("0" + maximumDate.getHours()).slice(-2) + ":" + ("0" + maximumDate.getMinutes()).slice(-2);
        })
        .catch(error => console.log(error));

    }

}

const getRandomColor = () => {
    return (Math.floor(Math.random() * 200) + 1) + ", " + (Math.floor(Math.random() * 200) + 1) + ", " + (Math.floor(Math.random() * 200) + 1);
}

/**
 * Get data points for the chart. NOT IN USE
 * @returns json of data points
 */
const getDataPoints = async (deviceName, formJSON) => {
    formJSON.device = deviceName; // Data type based on device.
    formJSON.class = "Reports";
    formJSON.method = "getDeviceReportData";
    //console.log(responseJSON);
    return await postApi(formJSON);
}

// IN USE
const getCharts = async (formJSON) => {

    if (document.cookie.includes('; ')) {
        const id = document.cookie.split('; ').find(c => c.startsWith('userId')).split('=')[1];
        formJSON.userId = id;
        formJSON.startDateTime = formJSON.startDate + " "+ formJSON.startTime;
        formJSON.endDateTime = formJSON.endDate + " "+ formJSON.endTime;
        //console.log(id);
        formJSON.class = "DataPoints";
        formJSON.method = "getDataPoints";

        //console.log(formJSON);
        document.getElementById('charts').innerHTML = '';

        return postApi(formJSON)
        .then(sensors => {
            //console.log(sensors);
            initializeRealTimeCharts(sensors);
        })
        .catch(error => console.log(error));
    }
    else {
        alert("logging out");
        location.href = './logout.php';
    }

}







const postApi = async (formData) => {

    let params = new URLSearchParams(formData);

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