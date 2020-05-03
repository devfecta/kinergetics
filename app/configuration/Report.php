<?php
require_once('User.php');
require_once('Device.php');
require_once('Configuration.php');

class Report {

    private $id;
    private $userId;
    private $deviceId;

    function __construct($reportId) {
        if ($reportId != null) {

            try {

                $connection = Configuration::openConnection();

                $statement = $connection->prepare("SELECT * FROM reports WHERE id=:id");
                $statement->bindParam(":id", $reportId);
                $statement->execute();

                $results = $statement->fetch(PDO::FETCH_ASSOC);

                $this->setId($results['id']);
                $this->setUserId($results['user_id']);
                $this->setDeviceId($results['device_id']);

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

    public function getUserId() {
        return $this->userId;
    }
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getDeviceId() {
        return $this->deviceId;
    }
    public function setDeviceId($deviceId) {
        $this->deviceId = $deviceId;
    }

    public function getUsers() {

        $users = [];

        try {

            $connection = Configuration::openConnection();

            $statement = $connection->prepare("SELECT `id` FROM `users` WHERE `type`=0");
            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $result) {

                $user = new User($result['id']);
                array_push($users, $user);
                
            }

            Configuration::closeConnection();
        }
        catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }


        return $users;

    }

    public function getDevices() {

        $devices = [];

        try {

            $connection = Configuration::openConnection();

            $statement = $connection->prepare("SELECT `id` FROM `devices`");
            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $result) {

                $device = new Device($result['id']);
                array_push($devices, $device);
                
            }

            Configuration::closeConnection();
        }
        catch (PDOException $e) {
            //return "Error: " . $e->getMessage();
            array_push($devices, $e->getMessage());
        }

        return $devices;

    }
}

?>