<?php

class FlowMeter {

    public function __construct() {}

    public function getFlowRate($formData) {

        try {
            $connection = Configuration::openConnection();
            $statement = $connection->prepare("SELECT * FROM `reports` WHERE `sensor_id`=:sensor");
            $statement->bindParam(":sensor", $formData['sensor'], PDO::PARAM_STR);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                $report = $statement->fetch(PDO::FETCH_ASSOC);

                $startDate = $formData['startDate']." ".$formData['startTime'];                
                $endDate = $formData['endDate']." ".$formData['endTime'];

                $statement = $connection->prepare("SELECT * FROM `data` WHERE `date_time` BETWEEN :startDate AND :endDate AND `report_id`=:report");
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
        
    }

}

?>