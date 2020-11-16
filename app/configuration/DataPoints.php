<?php

require_once('Configuration.php');

class DataPoints {

    private $id;
    private $report_id;
    private $date_time;
    private $flow_rate;
    private $total_volume;
    private $steam;
    private $feedwater;
    private $fahrenheit;
    private $celsius;
    private $current;
    private $relative_humidity;
    private $voltage_detected;
    private $error;
    private $velocity_reading;
    private $velocity_low_limit;
    private $velocity_high_limit;
    private $velocity_ma_custom;
    private $velocity_ma;
    private $inwc;
    private $pressure_reading;
    private $pressure_low_limit;
    private $pressure_high_limit;
    private $pressure_ma_custom;
    private $pressure_ma;
    private $psig;

    function __construct() {
        /*
        if ($pointId != null) {

            try {

                $connection = Configuration::openConnection();

                $statement = $connection->prepare("SELECT * FROM `data_points` WHERE JSON_CONTAINS(`departments`, :businessId)");
                $statement->bindParam(":id", $pointId);
                $statement->execute();

                $results = $statement->fetch(PDO::FETCH_ASSOC);

                $this->setId($results['id']);
                $this->setReportId($results['report_id']);
                $this->setDate($results['date_time']);
                $this->setFlowRate($results['flow_rate']);
                $this->setTotalVolume($results['total_volume']);
                $this->setSteam($results['flow_rate']);
                $this->setFeedWater($results['flow_rate']);
                $this->setFahrenheit($results['fahrenheit']);
                $this->setCelsius($results['fahrenheit']);
                $this->setCurrent($results['current']);
                $this->setRelativeHumidity($results['relative_humidity']);
                $this->setVoltageDetected($results['voltage_detected']);
                $this->setError($results['error']);
                $this->setVelocityReading($results['velocity_reading']);
                $this->setVelocityLowLimit($results['velocity_low_limit']);
                $this->setVelocityHighLimit($results['velocity_high_limit']);
                $this->setVelocityCustomMa($results['velocity_ma_custom']);
                $this->setVelocityMa($results['velocity_reading'], $results['velocity_low_limit'], $results['velocity_high_limit'], $results['velocity_ma_custom']);
                $this->setInwc($this->velocity_ma);
                $this->setPressureReading($results['pressure_reading']);
                $this->setPressureLowLimit($results['pressure_low_limit']);
                $this->setPressureHighLimit($results['pressure_high_limit']);
                $this->setPressureCustomMa($results['pressure_ma_custom']);
                $this->setPressureMa($results['pressure_reading'], $results['pressure_low_limit'], $results['pressure_high_limit'], $results['pressure_ma_custom']);
                $this->setPsig($this->pressure_ma);
                
            }
            catch (PDOException $e) {
                return "Error: " . $e->getMessage();
            }
            finally {
                Configuration::closeConnection();
            }
        }
        */
    }

    public function getDataPoints($userId, $dateTime) {
        //return json_encode(array($userId, $dateTime), JSON_PRETTY_PRINT);
        $result = array();

        try {
            
            $connection = Configuration::openConnection();
            
            $statement = $connection->prepare("SELECT `sensor_id`, `data_point` FROM `data_points` WHERE `user_id`=:user_id AND `date_time`>=:date_time");

            $statement->bindParam(":user_id", $userId, PDO::PARAM_INT); 
            $statement->bindParam(":date_time", $dateTime, PDO::PARAM_STR); 
            $statement->execute();

            $dataPoints = $statement->fetchAll(PDO::FETCH_ASSOC);

            $results = array();

            foreach($dataPoints as $dataPoint) {
                // Set Sensor ID Property
                $results[$dataPoint['sensor_id']]['sensorID'] = $dataPoint['sensor_id'];
                // Set Sensor Name Property
                $sensorName = json_decode($dataPoint['data_point'])->sensorName;
                $results[$dataPoint['sensor_id']]['sensorName'] =  strpos($sensorName, '|') ? explode(' | ', $sensorName)[2] : $sensorName;
                // Set Data Type Property
                $dataType = json_decode($dataPoint['data_point'])->dataType;
                $results[$dataPoint['sensor_id']]['dataType'] =  strpos($dataType, '|') ? explode('|', $dataType)[0] : $dataType;
                // Set Unit Type Property
                $plotLabels = json_decode($dataPoint['data_point'])->plotLabels;
                $results[$dataPoint['sensor_id']]['unitType'] =  strpos($plotLabels, '|') ? explode('|', $plotLabels)[1] : $plotLabels;
                // Set Sensor Data Points Property
                $results[$dataPoint['sensor_id']]['data_points'][] = json_decode($dataPoint['data_point']);
            }
            // Re-indexes the array of data points.
            foreach($results as $sensor) {
                $result[] = $sensor;
            }

        }
        catch(PDOException $pdo) {
            $result['error'] =  $pdo->getMessage();
        }
        finally {
            Configuration::closeConnection();
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    public function addDataPoint($userId, $sensor) {

        try {
            
            $connection = Configuration::openConnection();
            
            $statement = $connection->prepare("INSERT INTO `data_points` (
                `user_id`,
                `sensor_id`,
                `date_time`,
                `data_point`
            ) 
            VALUES (
                :user_id,
                :sensor_id,
                :date_time,
                :data_point
            )");

            $statement->bindValue(":user_id", $userId, PDO::PARAM_INT); 
            $statement->bindValue(":sensor_id", (int)$sensor['sensorID'], PDO::PARAM_INT); 
            $statement->bindParam(":date_time", $sensor['messageDate'], PDO::PARAM_STR); 
            $statement->bindParam(":data_point", json_encode($sensor));
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

}
?>