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
    /**
     * Gets the data points for a specific company/user based on a start and end datetime.
     *
     * @param   int  $userId         User ID
     * @param   string  $startDateTime  Start DateTime of the data point search.
     * @param   string  $endDateTime    End DateTime of the data point search.
     *
     * @return  json                  Datapoint JSON
     */
    public function getDataPoints($userId, $startDateTime, $endDateTime) {
        //return json_encode(array($userId, $dateTime), JSON_PRETTY_PRINT);
        $result = array();

        try {
            
            $connection = Configuration::openConnection();

            if ($endDateTime != "null") {
                $statement = $connection->prepare("SELECT `sensor_id`, `data_point` FROM `data_points` WHERE `user_id`=:user_id AND `date_time`>=:startDateTime AND `date_time`<=:endDateTime ORDER BY `sensor_id`, `date_time` ASC");
                $statement->bindParam(":user_id", $userId, PDO::PARAM_INT); 
                $statement->bindParam(":startDateTime", $startDateTime, PDO::PARAM_STR); 
                $statement->bindParam(":endDateTime", $endDateTime, PDO::PARAM_STR); 
            }
            else {
                $statement = $connection->prepare("SELECT `sensor_id`, `data_point` FROM `data_points` WHERE `user_id`=:user_id AND `date_time`<=:startDateTime ORDER BY `sensor_id`, `date_time` ASC LIMIT 0, 50");
                $statement->bindParam(":user_id", $userId, PDO::PARAM_INT); 
                $statement->bindParam(":startDateTime", $startDateTime, PDO::PARAM_STR); 
            }
            
            $statement->execute();

            $dataPoints = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            //return json_encode($dataPoints, JSON_PRETTY_PRINT);

            $results = array();

            /**
             * Data point manipulation here for charts
             */

            foreach($dataPoints as $dataPoint) {
                // Set Sensor ID Property from Database Column
                $results[$dataPoint['sensor_id']]['sensorID'] = $dataPoint['sensor_id'];
                // Set Sensor Name Property from JSON in the Database Column
                $sensorName = json_decode($dataPoint['data_point'])->sensorName;
                $results[$dataPoint['sensor_id']]['sensorName'] =  strpos($sensorName, '|') ? explode(' | ', $sensorName)[2] : $sensorName;
                


                // Set Data Type Property from JSON in the Database Column
                //$dataType = json_decode($dataPoint['data_point'])->dataType;
                // Get Plot Labels and Values for the Data Points from JSON in the Database Column
                $plotLabels = json_decode($dataPoint['data_point'])->plotLabels;
                $plotValues = json_decode($dataPoint['data_point'])->plotValues;

                

                if (strpos($plotLabels, '|')) {
                    //$dataTypeArray = explode('|', $dataType);
                    $plotLabelArray = explode('|', $plotLabels);
                    $plotValueArray = explode('|', $plotValues);

                    //return json_encode(array("test" => "0"), JSON_PRETTY_PRINT);

                    for ($i = 0; $i < count($plotLabelArray); $i++) {

                        //$plotLabelNames[] = str_replace(' ', '', $plotLabelArray[$i]);
                        $count = count($results[$dataPoint['sensor_id']]['data_points'][$plotLabelArray[$i]]);

                        $results[$dataPoint['sensor_id']]['data_points'][$plotLabelArray[$i]][$count] = floatval($plotValueArray[$i]);
                        
                        //$results[$dataPoint['sensor_id']]['data_points'][$sensorDataPointIndex][$i]['value'] = floatval($plotValueArray[$i]);
                        // Set Datetime Property from Database Column
                        $results[$dataPoint['sensor_id']]['data_points'][$plotLabelArray[$i]][$count]['dateTime'] = json_decode($dataPoint['data_point'])->messageDate;
                    }

                   // $results[$dataPoint['sensor_id']]['dataType'] = explode('|', $dataType)[0];

                }
                else {
                    $sensorDataPointIndex = count($results[$dataPoint['sensor_id']]['data_points'][empty($plotLabels) ? 0 : $plotLabels]);

                    $results[$dataPoint['sensor_id']]['data_points'][empty($plotLabels) ? 0 : $plotLabels][$sensorDataPointIndex] = empty($plotValues) ? 0 : floatval($plotValues);
                    $results[$dataPoint['sensor_id']]['data_points'][empty($plotLabels) ? 0 : $plotLabels][$sensorDataPointIndex]['dateTime'] = json_decode($dataPoint['data_point'])->messageDate;
                    /*
                    $results[$dataPoint['sensor_id']]['data_points'][$sensorDataPointIndex][0]['label'] = empty($plotLabels) ? 0 : $plotLabels;
                    $results[$dataPoint['sensor_id']]['data_points'][$sensorDataPointIndex][0]['value'] = empty($plotValues) ? 0 : floatval($plotValues);
                    $results[$dataPoint['sensor_id']]['data_points'][$sensorDataPointIndex][0]['dateTime'] = json_decode($dataPoint['data_point'])->messageDate;
                    */
                    //$results[$dataPoint['sensor_id']]['dataType'] = $dataType;
                }

                
                
/*
                // Set Unit Type Property from JSON in the Database Column
                $plotLabels = json_decode($dataPoint['data_point'])->plotLabels;
                $results[$dataPoint['sensor_id']]['unitType'] =  strpos($plotLabels, '|') ? explode('|', $plotLabels)[1] : $plotLabels;
*/
/* MAYBE DELETE
                // Set Specific Sensor Data Points Property from JSON in the Database Column
                $results[$dataPoint['sensor_id']]['default'][] = json_decode($dataPoint['data_point']);
*/
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
    /**
     * This inserts the data point JSON from the webhook, along with the sensor ID, 
     * company/user ID, and the datetime of the sensor reading.
     *
     * @param   int  $userId  User ID
     * @param   json  $sensor  JSON from the webhook
     *
     * @return  json           JSON of the new data point ID
     */
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