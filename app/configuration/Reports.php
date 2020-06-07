<?php

class Reports {

    function __construct() {}

    public function createReport($formData) {

        try {
            $connection = Configuration::openConnection();

            $statement = $connection->prepare("INSERT INTO reports (`user_id`, `device_id`) VALUES (:user, :device)");
            $statement->bindParam(":user", $formData['company']);
            $statement->bindParam(":device", $formData['device']);
            $statement->execute();

            $reportId = $connection->lastInsertId();

            Configuration::closeConnection();

            return json_encode(array('userId' => $formData['company'], 'deviceId' => $formData['device'], 'reportId' => $reportId), JSON_PRETTY_PRINT);
        }
        catch(PDOException $pdo) {
            return json_encode(array('error'=> $pdo->getMessage()), JSON_PRETTY_PRINT);
        }

    }

    public function getDeviceReportData($formData) {

        try {
            $connection = Configuration::openConnection();

            $startDate = $formData['startDate']." ".$formData['startTime'];                
            $endDate = $formData['endDate']." ".$formData['endTime'];

            $statement = $connection->prepare("SELECT * FROM `report_data` 
            INNER JOIN `reports` ON `report_data`.`report_id`=`reports`.`id` 
            INNER JOIN `devices` ON `devices`.`id`=`reports`.`device_id` 
            WHERE `date_time` BETWEEN :startDate AND :endDate AND `reports`.`user_id`=:user AND `devices`.`tag`=:device
            ORDER BY `date_time`");
            $statement->bindParam(":startDate", $startDate, PDO::PARAM_STR);
            $statement->bindParam(":endDate", $endDate, PDO::PARAM_STR);
            $statement->bindParam(":device", $formData['device'], PDO::PARAM_STR);
            $statement->bindParam(":user", $formData['user'], PDO::PARAM_STR);
            $statement->execute();

            $result = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;

        }
        catch (PDOException $pdo) {
            $result = array('error' => $pdo->getMessage());
        }
        finally {
            Configuration::closeConnection();
        }

        return json_encode($result, JSON_PRETTY_PRINT);
        
    }
    /*
    public function getSteamData($formData) {

        try {
            $connection = Configuration::openConnection();

            $statement = $connection->prepare("SELECT * FROM `devices` WHERE `tag`=:device");
            $statement->bindParam(":device", $formData['device'], PDO::PARAM_STR);
            $statement->execute();
            $device = $statement->fetch(PDO::FETCH_ASSOC);

            $statement = $connection->prepare("SELECT * FROM `reports` WHERE `user_id`=:user AND `device_id`=:device");
            $statement->bindParam(":user", $formData['user'], PDO::PARAM_STR);
            $statement->bindParam(":device", $device['id'], PDO::PARAM_STR);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                $report = $statement->fetch(PDO::FETCH_ASSOC);

                $startDate = $formData['startDate']." ".$formData['startTime'];                
                $endDate = $formData['endDate']." ".$formData['endTime'];

                $statement = $connection->prepare("SELECT * FROM `report_data` WHERE `date_time` BETWEEN :startDate AND :endDate AND `report_id`=:report ORDER BY `date_time`");
                $statement->bindParam(":startDate", $startDate, PDO::PARAM_STR);
                $statement->bindParam(":endDate", $endDate, PDO::PARAM_STR);
                $statement->bindParam(":report", $report['id'], PDO::PARAM_STR);
                $statement->execute();

                if ($statement->rowCount() > 0) {
                    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                    return json_encode($data, JSON_PRETTY_PRINT);
                }
            }

            Configuration::closeConnection();
        }
        catch (PDOException $pdo) {
            //return "PDO Error: " . $pdo->getMessage();
            return json_encode(array('error'=> $pdo->getMessage()), JSON_PRETTY_PRINT);
        }

        return json_encode(array('error'=> "No Records Found"), JSON_PRETTY_PRINT);
        
    }

    public function getFlowRateData($formData) {

        try {
            $connection = Configuration::openConnection();

            $startDate = $formData['startDate']." ".$formData['startTime'];                
            $endDate = $formData['endDate']." ".$formData['endTime'];

            $statement = $connection->prepare("SELECT * FROM `report_data` 
            INNER JOIN `reports` ON `report_data`.`report_id`=`reports`.`id` 
            INNER JOIN `devices` ON `devices`.`id`=`reports`.`device_id` 
            WHERE `date_time` BETWEEN :startDate AND :endDate AND `reports`.`user_id`=:user AND `devices`.`tag`=:device
            ORDER BY `date_time`");
            $statement->bindParam(":startDate", $startDate, PDO::PARAM_STR);
            $statement->bindParam(":endDate", $endDate, PDO::PARAM_STR);
            $statement->bindParam(":device", $formData['device'], PDO::PARAM_STR);
            $statement->bindParam(":user", $formData['user'], PDO::PARAM_STR);
            $statement->execute();

            $result = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;

        }
        catch (PDOException $pdo) {
            $result = array('error' => $pdo->getMessage());
        }
        finally {
            Configuration::closeConnection();
        }

        return json_encode($result, JSON_PRETTY_PRINT);
        
    }
    */

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

    public function getMinMaxDates() {
        try {

            $connection = Configuration::openConnection();

            $statement = $connection->prepare("SELECT MIN(date_time) as dateTime FROM report_data");
            $statement->execute();
            $results = $statement->fetch(PDO::FETCH_ASSOC);

            $dates['minimum'] = $results['dateTime'];

            $statement = $connection->prepare("SELECT MAX(date_time) as dateTime FROM report_data");
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