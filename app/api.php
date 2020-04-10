<?php
/**
 * May be able to remove this in production
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
ob_start();
session_start();
require_once('configuration/Configuration.php');
require_once('configuration/User.php');

//$requestMethod = $_SERVER['REQUEST_METHOD'];
$registration = null;
$encodedJSON = null;

require_once("configuration/FlowMeter.php");

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
                    $User = new User();
                    switch ($_GET['method']) {
                        case "register":
                            // Return JSON of the registrants
                            echo $User->register($_POST);
                            break;
                        case "login":
                            // Return JSON of the registrants
                            $result = json_decode($User->login($_POST), false);
                            if ($result->authenticated) {
                                $_SESSION['userId'] = $result->id;
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

        

        if (isset($_POST["sensor"])) {
            switch ($_POST["sensor"]) {
                case "1":
                    $FlowMeter = new FlowMeter();
                    switch ($_POST['dataType']) {
                        case "flowRate":

                            //echo json_encode(array("error" => $FlowMeter->getFlowRate($_POST)));
                            //exit();

                            echo $FlowMeter->getFlowRate($_POST);
                            break;
                        case "totalVolume":
                            echo $FlowMeter->getTotalVolume($_POST);
                            break;
                        case "steam":
                            echo $FlowMeter->getSteam($_POST);
                            break;
                        default:
                            echo json_encode(array("error" => 'GET METHOD ERROR: The '.$_GET['method'].' method does not exist.\n'), JSON_PRETTY_PRINT);
                            break;
                    }
                    break;
                default;
                    echo json_encode(array("error" => 'CLASS ERROR: The '.$_POST["sensor"].' class does not exist.\n'), JSON_PRETTY_PRINT);
                    break;
            }
        }

        if (isset($_REQUEST['formType'])) {

            switch ($_REQUEST['formType']) {

                case "Membership":

                    $processOrder = new ProcessOrder();
                    $registration = new Membership();

                    //if (!isset($_SESSION['order']['id'])) {
                        
                        $_SESSION['order']['id'] = $processOrder->createOrder(session_id());
                    //}

                    /**
                     * Creates a member but with the status of 0 (unregistered)
                     */

                    $data = json_decode(file_get_contents('php://input'), true);

                    for ($index = 0; $index < sizeof($data); $index++) {
                        foreach ($data[$index] as $key => $value) {
                            $_POST[$key] = $value;
                        }
                        $lineItemInfo = json_decode($registration->register($_POST));
                        $processOrder->addLineItem($_SESSION['order']['id'], $lineItemInfo);
                        $response[] = $lineItemInfo;

                    }
                    echo json_encode($response);
                    break;
                default:
                    break;
            }
        } else {
            //echo json_encode(array("error" => 'POST ERROR: Form type not set.\n'), JSON_PRETTY_PRINT);
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
