
<?php
require_once('./configuration/DataPoints.php');
// Posted WebHook data.
$webhookData = file_get_contents('php://input');
// Converts WebHook data to an array.
$webhookArray = json_decode($webhookData, true);

/*
$webhookArray['sensorMessages']

"sensorID":"528911" , 
"sensorName":"Temperature - 528911", 
    "applicationID":"2", 
    "networkID":"3", 
    "dataMessageGUID":"4c5da9b7-7e0d-46fd-88cb-cac46176cd5f", 
    "state": "16", 
    "messageDate": "2020-11-04 17:40:00", 
    "rawData":"23.3", 
    "dataType": "TemperatureData", 
    "dataValue": "23.3", 
    "plotValues": "73.94", 
    "plotLabels": "Fahrenheit", 
    "batteryLevel": "100", 
    "signalStrength": "0", 
    "pendingChange": "True", 
    "voltage": "3.19"
*/

switch($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $content = '';

        $dataPoints = new DataPoints();

        if (sizeof($webhookArray['sensorMessages']) > 0) {
            // Each Sensor
            foreach($webhookArray['sensorMessages'] as $sensor) {
                        
                $userId = (int)explode(' | ', $sensor['sensorName'])[0];

                $result = $dataPoints->addDataPoint($userId, $sensor);
                echo var_dump($result);
            }
        }
        else {
            mail("monitor@gmail.com", "Kinergetics's Webhook Data", "Webhook didn't send any sensor data.");
        }

        
        
        //file_put_contents('data.json', $content, FILE_APPEND);

        break;
    default:
        mail("monitor@gmail.com", "Kinergetics's Webhook Data", "Webhook didn't send a POST");
        break;
}

// var_dump($_POST);
/*
$obj = $json_data->payload;
$fname=$obj->signup->first_name;
$lname=$obj->signup->last_name;
$nid=$obj->signup->nationbuilder_id;
// Assemble the body of the email...
$message_body = <<<EOM
first name: $fname \n
last name: $lname \n
nationbuilder_id: $nid \n
EOM;
*/
// mail("devfecta@gmail.com", "Webhook Data", "Data Sent\r" . $content);
?>
