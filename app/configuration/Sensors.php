<?php
    require_once('Configuration.php');
    require_once('Sensor.php');

    class Sensors extends Sensor {

        function __construct() {}

        public function getUserSensors($userId) {

            try {
                $connection = Configuration::openConnection();

                $statement = $connection->prepare("SELECT * FROM `sensors` WHERE `user_id`=:user_id");
                $statement->bindValue(":user_id", $userId, PDO::PARAM_INT);
                $statement->execute();

                $results = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : array();
                
            }
            catch(PDOException $pdo) {
                error_log(date('Y-m-d H:i:s') . " " . $pdo->getMessage() . "\n", 3, "/var/www/html/app/php-errors.log");
            }
            catch (Exception $e) {
                error_log(date('Y-m-d H:i:s') . " " . $e->getMessage() . "\n", 3, "/var/www/html/app/php-errors.log");
            }
            finally {
                Configuration::closeConnection();
            }

            //error_log(date('Y-m-d H:i:s') . " " . var_dump($results) . "\n", 3, "/var/www/html/app/php-errors.log");

            return $results;

        }

    }
?>