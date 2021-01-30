<?php
    require_once('Configuration.php');

    class Sensor {

        private $sensorId;
        private $userId;
        private $sensorName;

        function __construct() {}

        public function getSensorId() {
            return $this->sensorId;
        }

        public function setSensorId($id) {
            $this->sensorId = $id;
        }

        public function getUserId() {
            return $this->userId;
        }

        public function setUserId($id) {
            $this->userId = $id;
        }

        public function getSensorName() {
            return $this->sensorName;
        }

        public function setSensorName($name) {
            $this->sensorName = $name;
        }

    }
?>