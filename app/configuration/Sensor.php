<?php
class Sensor {

    public function __construct() {}

    public function getSensors() {

        try {
            $connection = Configuration::openConnection();

            $statement = $connection->prepare("SELECT * FROM `sensors`");

            $statement->execute();

            $options = '';
    
            if ($statement->rowCount() > 0) {
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($results as &$result) {
                    $options .= '<option value="'.$result['id'].'">'.$result['sensor'].'</option>';
                }
            }

            Configuration::closeConnection();
    
            return $options;

        }
        catch (PDOException $pdo) {
            return "PDO Error: " . $pdo->getMessage();
        }

    }

}
?>