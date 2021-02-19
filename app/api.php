<?php
/**
 * May be able to remove this in production
 */
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
ob_start();
session_start();

require('configuration/Configuration.php');

require('configuration/Device.php');
require("configuration/Devices.php");

require('configuration/User.php');
require("configuration/Users.php");

require("configuration/Sensor.php");
require("configuration/Sensors.php");

//require("configuration/DataPoint.php");
require("configuration/DataPoints.php");

//$requestMethod = $_SERVER['REQUEST_METHOD'];
$registration = null;
$encodedJSON = null;

//echo json_encode(array("type" => "API Call", "request-method" => $_SERVER['REQUEST_METHOD'], "post" => $_POST, "get" => $_GET));
////$test = json_encode($_POST, false);
////echo json_encode(array("type" => $_POST["sensor"]));
//echo json_encode($_GET, false);
//echo json_encode($_POST, false);
//exit();

switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
    //echo "REQUEST_METHOD Post";
        header('Content-Type: application/json; charset=utf-8');
        //$post = json_decode($_POST, false);
        // Creates
        if (isset($_GET['class']) && !empty($_GET['class'])) {
            switch ($_GET['class']) {
                case "User":
                    $User = new User();
                    switch ($_GET['method']) {
                        case "register":
                            // Return JSON of the registrants
                            $result = json_decode($User->register($_POST), false);
                            $_SESSION['company'] = $_POST['company'];
                            $_SESSION['username'] = $_POST['username'];
                            if (!$result->authenticated) {
                                $_SESSION['message'] = "Could Not Register the Account";
                                header("Location: register.php");
                            }
                            else {
                                header("Location: index.php");
                            }
                            
                            break;
                        case "login":
                            $result = json_decode($User->login($_POST), false);
                            if ($result->authenticated) {
                                $_SESSION['userId'] = $result->id;
                                $_SESSION['company'] = $result->company;
                                $_SESSION['type'] = $result->type;

                                if ($result->type > 0) {
                                    setcookie("adminId", $result->id, time()+3600);
                                }
                                else {
                                    setcookie("userId", $result->id, time()+3600);
                                    
                                }

                                header("Location: index.php");

                            }
                            else {
                                header("Location: login.php");
                            }
                            break;
                        default:
                            echo json_encode(array("error" => 'METHOD ERROR: The '.$_GET['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                default;
                    echo json_encode(array("error" => 'CLASS ERROR: The '.$_GET['class'].' class does not exist.\n'), JSON_PRETTY_PRINT);
                    break;
            }
        }
        
        if (isset($_POST["class"])) {
            switch ($_POST['class']) {
                case "DataPoints":
                    $dataPoints = new DataPoints();
                    
                    switch ($_POST['method']) {
                        case "getDataPoints":
                            //echo json_encode(array("message" => $_GET['userId'].' = '.$_GET['dateTime']), JSON_PRETTY_PRINT);
                            echo $dataPoints->getDataPoints((int)$_POST['userId'], $_POST['startDateTime'], $_POST['endDateTime']);
                            break;
                        default:
                            echo json_encode(array("error" => 'GET METHOD ERROR: The '.$_POST['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                default;
                    echo json_encode(array("error" => 'CLASS ERROR: The '.$_POST['class'].' class does not exist.\n'), JSON_PRETTY_PRINT);
                    break;
            }
        }
        break;
    case "GET":
        // Reads
        if (isset($_GET['class'])) {
            switch ($_GET['class']) {
                case "Users":
                    $users = new Users();
                    switch ($_GET['method']) {
                        case "getCompanies":

                            $companies = $users->getCompanies();

                            $companiesArray = array();
                            
                            foreach($companies as $company) {
                                array_push($companiesArray, array("id" => $company->getId(), "company" => $company->getCompany(), "type" => $company->getType()));    
                            }

                            echo json_encode($companiesArray);

                            break;
                        default:
                            echo json_encode(array("error" => 'GET METHOD ERROR: The '.$_GET['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                case "Sensors":
                    $Sensors = new Sensors();
                    switch ($_GET['method']) {
                        case "getUserSensors":
                            
                            $userSensors = $Sensors->getUserSensors($_GET['userId']);

                            $sensorArray = array();
                            
                            foreach($userSensors as $sensor) {
                                array_push($sensorArray, array("id" => $sensor->getSensorId(), "sensor_name" => $sensor->getSensorName()));    
                            }

                            echo json_encode($sensorArray);

                            break;
                        case "getSensors":
                            $sensors = $Sensors->getSensors();

                            $sensorsArray = array();
                            
                            foreach($sensors as $sensor) {
                                array_push($sensorsArray, array("id" => $sensor->getSensorId(), "sensor_name" => $sensor->getSensorName()));    
                            }

                            echo json_encode($sensorsArray);
                            break;
                        default:
                            echo json_encode(array("error" => 'GET METHOD ERROR: The '.$_GET['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                case "Sensor":
                    $sensor = new Sensor();
                    switch ($_GET['method']) {
                        case "getSensor":
                            $sensor = Sensor::getSensor($_GET['sensorId']);

                            $sensorArray = array("id" => $sensor->getSensorId(), "sensor_name" => $sensor->getSensorName());

                            echo json_encode($sensorArray);

                            break;
                        default:
                            echo json_encode(array("error" => 'GET METHOD ERROR: The '.$_GET['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                case "DataPoints":
                    $dataPoints = new DataPoints();
                    switch ($_GET['method']) {
                        case "getMinMaxDates":
                            echo $dataPoints->getMinMaxDates($_GET['userId'], $_GET['sensorId']);
                            break;
                        case "getSensorDataPoints":
                            $dataPointArray = array();

                            $dataPointsArray = $dataPoints->getSensorDataPoints($_GET['userId'], $_GET['sensorId'], $_GET['startDateTime'], $_GET['endDateTime']);

                            foreach($dataPointsArray as $dataPoint) {
                                
                                array_push($dataPointArray, 
                                    array(
                                        "id" => $dataPoint->getDataPointId()
                                        , "user_id" => $dataPoint->getUserId()
                                        , "sensor_id" => $dataPoint->getSensorId()
                                        , "date_time" => $dataPoint->getDate()
                                        , "data_type" => $dataPoint->getDataType()
                                        , "data_value" => $dataPoint->getDataValue()
                                        , "custom_value" => $dataPoint->getCustomValue()
                                    )
                                );
                                
                            }

                            echo json_encode($dataPointArray);
                            break;
                        case "getSensorDataTypes":
                            $dataTypessArray = $dataPoints->getSensorDataTypes($_GET['sensorId']);
                            echo json_encode($dataTypessArray);
                            break;
                        case "getDataPoints":
                            //echo json_encode(array("message" => $_GET['userId'].' = '.$_GET['dateTime']), JSON_PRETTY_PRINT);
                            echo $dataPoints->getDataPoints((int)$_GET['userId'], $_GET['startDateTime'], 'null');
                            break;
                        default:
                            echo json_encode(array("error" => 'GET METHOD ERROR: The '.$_GET['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                default;
                    echo json_encode(array("error" => 'GET CLASS ERROR: The '.$_GET['class'].' class does not exist.\n'), JSON_PRETTY_PRINT);
                    break;
            }
        } else {
           // echo json_encode(array("error" => 'GET ERROR: Form type not set.\n'), JSON_PRETTY_PRINT);
        }
        break;
        
    case "PUT":
        //echo "REQUEST_METHOD Put";
        // Updates
        if (isset($_REQUEST['formType'])) {
            
        } else {
            echo json_encode(array("error" => 'PUT ERROR: Form type not set.\n'), JSON_PRETTY_PRINT);
        }
        break;
        
    case "DELETE":
        //echo "REQUEST_METHOD Delete";
        // Deletes
        if (isset($_GET['formType'])) {
            
        } else {
            echo json_encode(array("error" => 'DELETE ERROR: Form type not set.\n'), JSON_PRETTY_PRINT);
        }
        break;
    default:
        //echo "REQUEST_METHOD Default";
        break;
}

ob_flush();
?>
