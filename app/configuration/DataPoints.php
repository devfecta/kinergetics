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

    function __construct($pointId) {
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

    public function addDataPoint($userId, $sensor) {

        try {
            
            $connection = Configuration::openConnection();
            
            $statement = $connection->prepare("INSERT INTO data_points (
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