let ctx = document.querySelector('canvas');

const init = () => {

    let searchButton = document.querySelector("#getData");
    searchButton.addEventListener("click", getData);
    
    /*
    var ctx = document.getElementById('canvas').getContext('2d');
    window.myHorizontalBar = new Chart(ctx, {
        type: 'horizontalBar',
        data: horizontalBarChartData,
        options: {
            // Elements options apply to all of the options unless overridden in a dataset
            // In this case, we are setting the border of each horizontal bar to be 2px wide
            elements: {
                rectangle: {
                    borderWidth: 2,
                }
            },
            responsive: true,
            legend: {
                position: 'right',
            },
            title: {
                display: true,
                text: 'Chart.js Horizontal Bar Chart'
            }
        }
    });
    */
}


const getData = async () => {

    let searchForm = document.querySelector("#searchForm");

    const json = {};

    let formData = new FormData(searchForm);
    
    formData.forEach((entry, index) => {
        json[index] = entry;
    })

    //console.log(json);
    
    let chartData = await callApi(json);

    console.log(chartData.length);

    let labelsData = [];
    let steamData = [];
    let barBackgroundColor = [];
    let barBorderColor = [];

    chartData.forEach(data => {
        let date = new Date(data.date_time).toLocaleDateString();
        labelsData = [...labelsData, date];
        steamData = [...steamData, data.steam];
        barBackgroundColor = [...barBackgroundColor, 'rgba(255, 99, 132, 0.2)'];
        barBorderColor = [...barBorderColor, 'rgba(255, 99, 132, 0.7)'];
    });

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

    let myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: labelsData,
            datasets: [{
                label: '# of Votes',
                data: steamData,
                backgroundColor: barBackgroundColor,
                borderColor: barBorderColor,
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



    return false;
}

const callApi = async (formData) => {

    console.log(formData);

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

    /*
    console.log("API Call");
    let url = `http://localhost/app/api.php?`;
    let params = 'user=' + user;
    params += '&class=' + sensor;
    params += '&method=' + dataType;
    params += '&startDate=' + startDate;
    params += '&endDate=' + endDate;
    url = url + params;
    
    return await fetch(url)
        .then(response => response.json())
        .then(data => console.log(data));
    */
}



window.onload = init;