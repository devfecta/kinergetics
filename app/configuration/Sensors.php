<?php
    require_once('Configuration.php');
    require_once('Sensor.php');

    class Sensors extends Sensor {

        function __construct() {}
        /**
         * Gets user specific sensor information.
         *
         * @param   int  $userId  Logged in user's ID
         *
         * @return  array  An array of user specific sensors.
         */
        public function getUserSensors($userId) {

            $sensors = array();

            try {

                $connection = Configuration::openConnection();

                $statement = $connection->prepare("SELECT * FROM `sensors` WHERE `user_id`=:user_id");
                $statement->bindValue(":user_id", $userId, PDO::PARAM_INT);
                $statement->execute();

                $results = $statement->rowCount() > 0 ? $statement->fetchAll(PDO::FETCH_ASSOC) : array();

                foreach ($results as $sensor) {
                    array_push($sensors, Sensor::getSensor($sensor['id']));
                }

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

            return $sensors;

        }

    }
?>