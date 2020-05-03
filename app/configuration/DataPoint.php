<?php

class DataPoint {

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
        if ($pointId != null) {

            try {

                $connection = Configuration::openConnection();

                $statement = $connection->prepare("SELECT * FROM report_data WHERE id=:id");
                $statement->bindParam(":id", $pointId);
                $statement->execute();

                $results = $statement->fetch(PDO::FETCH_ASSOC);

                $this->setId($results['id']);
                $this->setReportId($results['report_id']);
                $this->setDate($results['date_time']);
                $this->setFlowRate($results['flow_rate']);
                $this->setTotalVolume($results['total_volume']);
                $this->setSteam($results['steam']);
                $this->setFeedWater($results['feedwater']);
                $this->setFahrenheit($results['fahrenheit']);
                $this->setCelsius($results['celsius']);
                $this->setCurrent($results['current']);
                $this->setRelativeHumidity($results['relative_humidity']);
                $this->setVoltageDetected($results['voltage_detected']);
                $this->setError($results['error']);
                $this->setVelocityReading($results['velocity_reading']);
                $this->setVelocityLowLimit($results['velocity_low_limit']);
                $this->setVelocityHighLimit($results['velocity_high_limit']);
                $this->setVelocityCustomMa($results['velocity_ma_custom']);
                $this->setVelocityMa($results['velocity_ma']);
                $this->setInwc($results['inwc']);
                $this->setPressureReading($results['pressure_reading']);
                $this->setPressureLowLimit($results['pressure_low_limit']);
                $this->setPressureHighLimit($results['pressure_high_limit']);
                $this->setPressureCustomMa($results['pressure_ma_custom']);
                $this->setPressureMa($results['pressure_ma']);
                $this->setPsig($results['psig']);

                Configuration::closeConnection();
            }
            catch (PDOException $e) {
                return "Error: " . $e->getMessage();
            }

        }
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getReportId() {
        return $this->report_id;
    }
    public function setReportId($report_id) {
        $this->report_id = $report_id;
    }
    
    public function getDate() {
        return $this->date_time;
    }
    public function setDate($date_time) {
        $this->date_time = $date_time;
    }
    
    public function getFlowRate() {
        return $this->flow_rate;
    }
    public function setFlowRate($flow_rate) {
        $this->flow_rate = $flow_rate;
    }
    
    public function getTotalVolume() {
        return $this->total_volume;
    }
    public function setTotalVolume($total_volume) {
        $this->total_volume = $total_volume;
    }
    
    public function getSteam() {
        return $this->steam;
    }
    public function setSteam($steam) {
        $this->steam = $steam;
    }
    
    public function getFeedWater() {
        return $this->feedwater;
    }
    public function setFeedWater($feedwater) {
        $this->feedwater = $feedwater;
    }
    
    public function getFahrenheit() {
        return $this->fahrenheit;
    }
    public function setFahrenheit($fahrenheit) {
        $this->fahrenheit = $fahrenheit;
    }
    
    public function getCelsius() {
        return $this->celsius;
    }
    public function setCelsius($celsius) {
        $this->celsius = $celsius;
    }
    
    public function getCurrent() {
        return $this->current;
    }
    public function setCurrent($current) {
        $this->current = $current;
    }
    
    public function getRelativeHumidity() {
        return $this->relative_humidity;
    }
    public function setRelativeHumidity($relative_humidity) {
        $this->relative_humidity = $relative_humidity;
    }
    
    public function getVoltageDetected() {
        return $this->voltage_detected;
    }
    public function setVoltageDetected($voltage_detected) {
        $this->voltage_detected = $voltage_detected;
    }
    
    public function getError() {
        return $this->error;
    }
    public function setError($error) {
        $this->error = $error;
    }
    
    public function getVelocityReading() {
        return $this->velocity_reading;
    }
    public function setVelocityReading($velocity_reading) {
        $this->velocity_reading = $velocity_reading;
    }
    
    public function getVelocityLowLimit() {
        return $this->velocity_low_limit;
    }
    public function setVelocityLowLimit($velocity_low_limit) {
        $this->velocity_low_limit = $velocity_low_limit;
    }
    
    public function getVelocityHighLimit() {
        return $this->velocity_high_limit;
    }
    public function setVelocityHighLimit($velocity_high_limit) {
        $this->velocity_high_limit = $velocity_high_limit;
    }
    
    public function getVelocityCustomMa() {
        return $this->velocity_ma_custom;
    }
    public function setVelocityCustomMa($velocity_ma_custom) {
        $this->velocity_ma_custom = $velocity_ma_custom;
    }
    
    public function getVelocityMa() {
        return $this->velocity_ma;
    }
    public function setVelocityMa($velocity_ma) {
        $this->velocity_ma = $velocity_ma;
    }
    
    public function getInwc() {
        return $this->inwc;
    }
    public function setInwc($inwc) {
        $this->inwc = $inwc;
    }
    
    public function getPressureReading() {
        return $this->pressure_reading;
    }
    public function setPressureReading($pressure_reading) {
        $this->pressure_reading = $pressure_reading;
    }
    
    public function getPressureLowLimit() {
        return $this->pressure_low_limit;
    }
    public function setPressureLowLimit($pressure_low_limit) {
        $this->pressure_low_limit = $pressure_low_limit;
    }
    
    public function getPressureHighLimit() {
        return $this->pressure_high_limit;
    }
    public function setPressureHighLimit($pressure_high_limit) {
        $this->pressure_high_limit = $pressure_high_limit;
    }
    
    public function getPressureCustomMa() {
        return $this->pressure_ma_custom;
    }
    public function setPressureCustomMa($pressure_ma_custom) {
        $this->pressure_ma_custom = $pressure_ma_custom;
    }
    
    public function getPressureMa() {
        return $this->pressure_ma;
    }
    public function setPressureMa($pressure_ma) {
        $this->pressure_ma = $pressure_ma;
    }
    
    public function getPsig() {
        return $this->psig;
    }
    public function setPsig($psig) {
        $this->psig = $psig;
    }

}
?>