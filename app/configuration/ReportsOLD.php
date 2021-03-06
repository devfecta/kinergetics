<?php

require_once('DataPoint.php');

class Reports {

    function __construct() {}
    // OLD
    public function getUserReports($formData) {
        
        try {
            $connection = Configuration::openConnection();

            $startDate = $formData['startDate']." ".$formData['startTime'];
            $endDate = $formData['endDate']." ".$formData['endTime'];

            $statement = $connection->prepare("SELECT DISTINCT `devices`.`name`, `devices`.`tag`, `reports`.`id`, `reports`.`form_fields`, `report_data`.`date_time` FROM `reports` 
            INNER JOIN `report_data` ON `report_data`.`report_id`=`reports`.`id` 
            INNER JOIN `devices` ON `devices`.`id`=`reports`.`device_id` 
            WHERE `reports`.`user_id`=:user AND `report_data`.`date_time` 
            IN (SELECT MAX(`date_time`) FROM `report_data` WHERE `date_time` BETWEEN :startDate AND :endDate AND `report_id`=`reports`.`id`) 
            ORDER BY `report_data`.`date_time` DESC");
            $statement->bindParam(":user", $formData['user'], PDO::PARAM_INT);
            $statement->bindParam(":startDate", $startDate, PDO::PARAM_STR);
            $statement->bindParam(":endDate", $endDate, PDO::PARAM_STR);
            $statement->execute();

            $reports = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;

            if (sizeof($reports)) {
                foreach($reports as $reportIndex => $report) {

                    $result[$reportIndex]['device']['name'] = $report['name'];
                    $result[$reportIndex]['device']['tag'] = $report['tag'];

                    $statement = $connection->prepare("SELECT `report_data`.* FROM `report_data` 
                    INNER JOIN `reports` ON `report_data`.`report_id`=`reports`.`id` 
                    WHERE `date_time` BETWEEN :startDate AND :endDate AND `report_data`.`report_id`=:reportId
                    ORDER BY `date_time` ASC");
                    $statement->bindParam(":startDate", $startDate, PDO::PARAM_STR);
                    $statement->bindParam(":endDate", $endDate, PDO::PARAM_STR);
                    $statement->bindParam(":reportId", $report['id'], PDO::PARAM_INT);
                    $statement->execute();

                    $dataPoints = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;

                    if (sizeof($dataPoints)) {

                        $fields = json_decode($report['form_fields']);

                        foreach($fields as $field) {

                            foreach($dataPoints as $dataPointIndex => $dataPoint) {

                                $result[$reportIndex]['dataPoints'][$field][$dataPointIndex]['date_times'] = $dataPoint['date_time'];
                                $result[$reportIndex]['dataPoints'][$field][$dataPointIndex]['values'] = $dataPoint[$field];

                                $dataPoint = new DataPoint($dataPoint['id']);
                                // Calculated Properties
                                switch ($field) {
                                    case "flow_rate":
                                    case "total_volume":
                                        $result[$reportIndex]['dataPoints']['steam'][$dataPointIndex]['date_times'] = $dataPoint->getDate();
                                        $result[$reportIndex]['dataPoints']['steam'][$dataPointIndex]['values'] = $dataPoint->getSteam();
                                        $result[$reportIndex]['dataPoints']['feedwater'][$dataPointIndex]['date_times'] = $dataPoint->getDate();
                                        $result[$reportIndex]['dataPoints']['feedwater'][$dataPointIndex]['values'] = $dataPoint->getFeedWater();
                                        break;
                                    case "fahrenheit":
                                        $result[$reportIndex]['dataPoints']['celsius'][$dataPointIndex]['date_times'] = $dataPoint->getDate();
                                        $result[$reportIndex]['dataPoints']['celsius'][$dataPointIndex]['values'] = $dataPoint->getCelsius();
                                        break;
                                    case "velocity_reading":
                                    case "velocity_ma_custom":
                                        $result[$reportIndex]['dataPoints']['velocity_ma'][$dataPointIndex]['date_times'] = $dataPoint->getDate();
                                        $result[$reportIndex]['dataPoints']['velocity_ma'][$dataPointIndex]['values'] = $dataPoint->getVelocityMa();
                                        $result[$reportIndex]['dataPoints']['inwc'][$dataPointIndex]['date_times'] = $dataPoint->getDate();
                                        $result[$reportIndex]['dataPoints']['inwc'][$dataPointIndex]['values'] = $dataPoint->getInwc();
                                        break;
                                    case "pressure_reading":
                                    case "pressure_ma_custom":
                                        $result[$reportIndex]['dataPoints']['pressure_ma'][$dataPointIndex]['date_times'] = $dataPoint->getDate();
                                        $result[$reportIndex]['dataPoints']['pressure_ma'][$dataPointIndex]['values'] = $dataPoint->getPressureMa();
                                        $result[$reportIndex]['dataPoints']['psig'][$dataPointIndex]['date_times'] = $dataPoint->getDate();
                                        $result[$reportIndex]['dataPoints']['psig'][$dataPointIndex]['values'] = $dataPoint->getPsig();
                                        break;
                                    default:
                                        break;
                                }
                                
                            }

                        }

                    }

                }
            }
            
        }
        catch(PDOException $pdo) {
            $result = array('error' => $pdo->getMessage());
        }
        finally {
            Configuration::closeConnection();
        }
        return json_encode($result, JSON_PRETTY_PRINT);
    }
    // OLD
    public function getFormFields() {
        // For creating the report
        try {
            $connection = Configuration::openConnection();

            $statement = $connection->prepare("DESCRIBE `report_data`");
            $statement->execute();

            $result = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;

        }
        catch(PDOException $pdo) {
            $result = array('error' => $pdo->getMessage());
        }
        finally {
            Configuration::closeConnection();
        }
        return json_encode($result, JSON_PRETTY_PRINT);
    }
    // OLD
    public function createReport($formData) {

        try {
            $connection = Configuration::openConnection();

            $statement = $connection->prepare("INSERT INTO reports (`user_id`, `device_id`, `form_fields`) VALUES (:user, :device, :formFields)");
            $statement->bindParam(":user", $formData['company']);
            $statement->bindParam(":device", $formData['device']);
            $statement->bindParam(":formFields", json_encode($formData['formFields']));
            $statement->execute();

            $reportId = $connection->lastInsertId();

            $result = array('userId' => $formData['company'], 'deviceId' => $formData['device'], 'reportId' => $reportId);
        }
        catch(PDOException $pdo) {
            $result = array('error'=> $pdo->getMessage());
        }
        finally {
            Configuration::closeConnection();
        }
        return json_encode($result, JSON_PRETTY_PRINT);
    }
    
    // OLD
    public function getReportDatapoints($reportId) {
        try {
            $connection = Configuration::openConnection();

            $statement = $connection->prepare("SELECT `reports`.`form_fields`, `report_data`.* FROM `report_data` 
            INNER JOIN `reports` ON `reports`.`id`=`report_data`.`report_id`
            WHERE `report_data`.`report_id`=:reportId 
            ORDER BY `date_time` DESC");
            $statement->bindParam(":reportId", $reportId , PDO::PARAM_STR);
            $statement->execute();
            $dataPoints = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;

            if (sizeof($dataPoints)) {
                
                foreach($dataPoints as $index => $data) {
                    $dataPoint = new DataPoint($data['id']);
                    $result[$index] = $data;
                    // Calculated Properties
                    $result[$index]['steam'] = $dataPoint->getSteam();
                    $result[$index]['feedwater'] = $dataPoint->getFeedWater();
                    $result[$index]['celsius'] = $dataPoint->getCelsius();
                    $result[$index]['velocity_ma'] = $dataPoint->getVelocityMa();
                    $result[$index]['inwc'] = $dataPoint->getInwc();
                    $result[$index]['pressure_ma'] = $dataPoint->getPressureMa();
                    $result[$index]['psig'] = $dataPoint->getPsig();
                }
            }
            else {
                $result = $dataPoints;
            }
        }
        catch (PDOException $pdo) {
            $result = array('error' => $pdo->getMessage());
        }
        finally {
            Configuration::closeConnection();
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }
    // OLD
    public function getDeviceReportData($formData) {

        try {
            $connection = Configuration::openConnection();

            $startDate = $formData['startDate']." ".$formData['startTime'];                
            $endDate = $formData['endDate']." ".$formData['endTime'];

            $statement = $connection->prepare("SELECT `report_data`.* FROM `report_data` 
            INNER JOIN `reports` ON `report_data`.`report_id`=`reports`.`id` 
            INNER JOIN `devices` ON `devices`.`id`=`reports`.`device_id` 
            WHERE `date_time` BETWEEN :startDate AND :endDate AND `reports`.`user_id`=:user AND `devices`.`tag`=:device
            ORDER BY `date_time`");
            $statement->bindParam(":startDate", $startDate, PDO::PARAM_STR);
            $statement->bindParam(":endDate", $endDate, PDO::PARAM_STR);
            $statement->bindParam(":device", $formData['device'], PDO::PARAM_STR);
            $statement->bindParam(":user", $formData['user'], PDO::PARAM_STR);
            $statement->execute();

            $dataPoints = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;

            if (sizeof($dataPoints)) {
                
                foreach($dataPoints as $index => $data) {
                    $dataPoint = new DataPoint($data['id']);
                    $result[$index] = $data;
                    // Calculated Properties
                    $result[$index]['steam'] = $dataPoint->getSteam();
                    $result[$index]['feedwater'] = $dataPoint->getFeedWater();
                    $result[$index]['celsius'] = $dataPoint->getCelsius();
                    $result[$index]['velocity_ma'] = $dataPoint->getVelocityMa();
                    $result[$index]['inwc'] = $dataPoint->getInwc();
                    $result[$index]['pressure_ma'] = $dataPoint->getPressureMa();
                    $result[$index]['psig'] = $dataPoint->getPsig();
                }
            }
            else {
                $result = $dataPoints;
            }
        }
        catch (PDOException $pdo) {
            $result = array('error' => $pdo->getMessage());
        }
        finally {
            Configuration::closeConnection();
        }

        return json_encode($result, JSON_PRETTY_PRINT);
        
    }

    
    // OLD
    public function addDataPoint($formData) {
        try {
            $connection = Configuration::openConnection();

            foreach ($formData as $key => $data) {
                if (strlen($data) > 0) {}
                else {
                    $formData[$key] = 0;
                }
            }

            $pointDate = $formData['pointDate']." ".$formData['pointTime']; 

            $statement = $connection->prepare("INSERT INTO report_data (
                `report_id`, 
                `date_time`, 
                `flow_rate`, 
                `total_volume`, 
                `fahrenheit`, 
                `relative_humidity`, 
                `current`, 
                `voltage_detected`, 
                `error`, 
                `velocity_reading`, 
                `velocity_low_limit`, 
                `velocity_high_limit`, 
                `velocity_ma_custom`, 
                `pressure_reading`, 
                `pressure_low_limit`, 
                `pressure_high_limit`, 
                `pressure_ma_custom`
            ) 
            VALUES (
                :report_id, 
                :date_time, 
                :flow_rate, 
                :total_volume, 
                :fahrenheit, 
                :relative_humidity, 
                :current, 
                :voltage_detected, 
                :error, 
                :velocity_reading, 
                :velocity_low_limit, 
                :velocity_high_limit, 
                :velocity_ma_custom, 
                :pressure_reading, 
                :pressure_low_limit, 
                :pressure_high_limit, 
                :pressure_ma_custom
            )");

            $statement->bindParam(":report_id", $formData['reportId']);
            $statement->bindParam(":date_time", $pointDate, PDO::PARAM_STR);
            $statement->bindParam(":flow_rate", $formData['flowRate']);
            $statement->bindParam(":total_volume", $formData['totalVolume']);
            $statement->bindParam(":fahrenheit", $formData['fahrenheit']);
            $statement->bindParam(":relative_humidity", $formData['relativeHumidity']);
            $statement->bindParam(":current", $formData['current']);
            $statement->bindParam(":voltage_detected", $formData['voltageDetected']);
            $statement->bindParam(":error", $formData['errorCode']);
            $statement->bindParam(":velocity_reading", $formData['velocityReading']);
            $statement->bindParam(":velocity_low_limit", $formData['velocityLowLimit']);
            $statement->bindParam(":velocity_high_limit", $formData['velocityHighLimit']);
            $statement->bindParam(":velocity_ma_custom", $formData['velocityCustom']);
            $statement->bindParam(":pressure_reading", $formData['pressureReading']);
            $statement->bindParam(":pressure_low_limit", $formData['pressureLowLimit']);
            $statement->bindParam(":pressure_high_limit", $formData['pressureHighLimit']);
            $statement->bindParam(":pressure_ma_custom", $formData['pressureCustom']);
            $statement->execute();

            $dataPointId = $connection->lastInsertId();

            $result['dataPointId'] = $dataPointId;
        }
        catch(PDOException $pdo) {
            $result['error'] =  $pdo->getMessage();
        }
        finally {
            Configuration::closeConnection();
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }
    /**
     * Returns the minimum and maximum dates based on a specific sensor's data points.
     *
     * @param   int  $userId    Logged in user ID
     * @param   int  $sensorId  Selected sensor ID
     *
     * @return  json  JSON string containing the minimum and maximum dates based on a specific sensor's data points.
     */
    public function getMinMaxDates($userId, $sensorId) {
        try {

            $connection = Configuration::openConnection();

            $statement = $connection->prepare("SELECT MIN(date_time) AS minDate, MAX(date_time) AS maxDate FROM dataPoints WHERE user_id=:userId AND sensor_id=:sensorId");
            $statement->bindParam(":userId", $userId, PDO::PARAM_STR);
            $statement->bindParam(":sensorId", $sensorId, PDO::PARAM_STR);
            $statement->execute();
            $results = $statement->fetch(PDO::FETCH_ASSOC);

            $dates['minimum'] = $results['minDate'];
            $dates['maximum'] = $results['maxDate'];

            return json_encode($dates, JSON_PRETTY_PRINT);
        }
        catch (PDOException $pdo) {
            return json_encode(array('error' => $pdo->getMessage()), JSON_PRETTY_PRINT);
        }
        finally {
            Configuration::closeConnection();
        }
    }
    // OLD
    public function getMinMaxDatesOLD() {
        try {

            $connection = Configuration::openConnection();

            $statement = $connection->prepare("SELECT MIN(date_time) as dateTime FROM data_points");
            $statement->execute();
            $results = $statement->fetch(PDO::FETCH_ASSOC);

            $dates['minimum'] = $results['dateTime'];

            $statement = $connection->prepare("SELECT MAX(date_time) as dateTime FROM data_points");
            $statement->execute();
            $results = $statement->fetch(PDO::FETCH_ASSOC);

            $dates['maximum'] = $results['dateTime'];

            return json_encode($dates, JSON_PRETTY_PRINT);
        }
        catch (PDOException $pdo) {
            return json_encode(array('error' => $pdo->getMessage()), JSON_PRETTY_PRINT);
        }
        finally {
            Configuration::closeConnection();
        }
    }

}

?>