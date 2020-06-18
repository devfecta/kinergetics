<?php
/**
 * May be able to remove this in production
 */
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
ob_start();
session_start();

require('configuration/Configuration.php');
require('configuration/User.php');
require('configuration/Device.php');
require("configuration/Reports.php");
require("configuration/Devices.php");

//$requestMethod = $_SERVER['REQUEST_METHOD'];
$registration = null;
$encodedJSON = null;

//echo json_encode(array("type" => "API Call", "request-method" => $_SERVER['REQUEST_METHOD'], "post" => $_POST, "get" => $_GET));
////$test = json_encode($_POST, false);
////echo json_encode(array("type" => $_POST["sensor"]));
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
                    $User = new User(null);
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
                            // Return JSON of the registrants
                            $result = json_decode($User->login($_POST), false);
                            if ($result->authenticated) {
                                $_SESSION['userId'] = $result->id;
                                $_SESSION['company'] = $result->company;
                                $_SESSION['type'] = $result->type;
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
                case "Reports":
                    $Reports = new Reports();
                    switch ($_GET['method']) {
                        case "createReport":
                            $_SESSION['report'] = $Reports->createReport($_POST);
                            header("Location: addDataPoint.php");
                            break;
                        case "addDataPoint":
                            $_SESSION['dataPoint'] = $Reports->addDataPoint($_POST);
                            header("Location: addDataPoint.php");
                            break;
                        case "addDevice":
                            echo $Reports->addDevice($_POST);
                            break;
                        default:
                            echo json_encode(array("error" => 'METHOD ERROR: The '.$_GET['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                        break;
                    }
                    break;
                case "Devices":
                    $Devices = new Devices();
                    switch ($_GET['method']) {
                        case "addDevice":
                            $_SESSION['device'] = $Devices->addDevice($_POST);
                            header("Location: addDevice.php");
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
                case "Reports":
                    $Reports = new Reports();
                    switch ($_POST['method']) {
                        case "getDeviceReportData":
                            /*
                                class: "Reports"
                                device: "flowMeter"
                                endDate: "2020-06-07"
                                endTime: "13:06"
                                method: "getDeviceReportData"
                                startDate: "2019-05-03"
                                startTime: "16:05"
                                user: "2"   
                            */
                            echo $Reports->getDeviceReportData($_POST);
                            break;
                        default:
                            echo json_encode(array("error" => 'METHOD ERROR: The '.$_POST['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                default;
                    echo json_encode(array("error" => 'CLASS ERROR: The '.$_POST['class'].' class does not exist.\n'), JSON_PRETTY_PRINT);
                    break;
            }
        }
        

        if (isset($_POST["dataType"])) {
            
            $Reports = new Reports();

            switch ($_POST['dataType']) {
                case "steam":
                    //echo json_encode(array("error" => $_POST));
                    //exit();
                    echo $Reports->getSteamData($_POST);
                    break;
                case "totalVolume":
                    echo $FlowMeter->getTotalVolume($_POST);
                    break;
                case "steam":
                    echo $FlowMeter->getSteam($_POST);
                    break;
                default:
                    echo json_encode(array("error" => 'POST METHOD ERROR: The '.$_POST['dataType'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                    break;
            }
        }
        break;
    case "GET":
        //echo "REQUEST_METHOD Get";
        // Reads
        if (isset($_GET['class'])) {
            switch ($_GET['class']) {
                case "FlowMeter":
                    $FlowMeter = new FlowMeter();
                    switch ($_GET['method']) {
                        case "flowRate":
                            echo $FlowMeter->getFlowRate($_GET['formData']);
                            break;
                        case "totalVolume":
                            echo $FlowMeter->getTotalVolume($_GET['formData']);
                            break;
                        case "steam":
                            echo $FlowMeter->getSteam($_GET['formData']);
                            break;
                        default:
                            echo json_encode(array("error" => 'GET METHOD ERROR: The '.$_GET['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                case "Reports":
                    $Reports = new Reports();
                    switch ($_GET['method']) {
                        case "getFormFields":
                            echo $Reports->getFormFields();
                            break;
                        case "getMinMaxDates":
                            echo $Reports->getMinMaxDates();
                            break;
                        case "getCompanies":
                            echo $Reports->getCompanies();
                            break;
                        case "getUserReportDatapoints":
                            echo $Reports->getUserReportDatapoints($_GET['reportId']);
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
            switch ($_REQUEST['formType']) {
                case "UpdateMember":

                    $registration = new Membership();

                    if (isset($_REQUEST['memberId'])) {
                        
                        echo $registration->updateRegistration($_REQUEST);

                    } else {
                        echo json_encode(array("error" => 'PUT ERROR: Member ID not set.\n'), JSON_PRETTY_PRINT);
                    }
                        
                    break;
                default:
                    
                    break;
            }
        } else {
            echo json_encode(array("error" => 'PUT ERROR: Form type not set.\n'), JSON_PRETTY_PRINT);
        }
        break;
        
    case "DELETE":
    //echo "REQUEST_METHOD Delete";
        // Deletes
        
        if (isset($_GET['formType'])) {
            switch ($_GET['formType']) {
                case "unregisterMembership":

                    $registration = new Membership();

                    if (isset($_GET['id'])) {
                        
                        //echo json_decode($registration->unRegister($_GET['id']));
                        echo $registration->unRegister($_GET['id']);

                        //echo json_encode(array("rowsAffected2" => 'testing'), JSON_PRETTY_PRINT);;
                    } else {
                        echo json_encode(array("error" => 'DELETE ERROR: ID not set.\n'), JSON_PRETTY_PRINT);
                    }
                        
                    break;
                default:
                    
                    break;
            }
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
