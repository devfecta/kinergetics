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

    console.log(json);

    let chartData = await callApi(json);

    console.log(chartData);

    return false;
/*
    const formData = new FormData(searchEntries.target);

    console.log(formData);

    let user = document.querySelector('#user').value;
    let sensor = document.querySelector('#sensor').value;
    let dataType = document.querySelector('#dataType').value;
    let startDate = document.querySelector('#startDate').value;
    let endDate = document.querySelector('#endDate').value;
    

    console.log(startDate);
    console.log(endDate);

    let chartData = await callApi(user, sensor, dataType, startDate, endDate);

    console.log(chartData);
*/
    /*
    let coordinates = await getCoordinates(document.querySelector('#startDate').value);
    console.log(coordinates);
    let weatherData = await getWeather(coordinates);
    console.log(weatherData);
    */
}

const callApi = async (formData) => {

    console.log(formData);

    let url = `http://localhost/app/api.php`;

    return await fetch(url, {
        method: 'POST',
        body: JSON.stringify(formData),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
        
    })
    .then(response => response.json())
    .then(data => console.log(data));

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