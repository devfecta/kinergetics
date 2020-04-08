<?php

class FlowMeter {

    public function __construct() {}

    public function getFlowRate() {

        try {
            $connection = Configuration::openConnection();
            $statement = $connection->prepare("SELECT * FROM `users` WHERE `username`=:username1");
            $statement->bindParam(":username1", $data->username, PDO::PARAM_STR);
            $statement->execute();
        }
        catch (PDOException $pdo) {
            return "PDO Error: " . $pdo->getMessage();
        }
    }

}

?>