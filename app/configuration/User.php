<?php

    class User {

        public function __construct() {}
        
        public function login($formData) {
            $data = json_decode(json_encode($formData), false);
    
            $userInfo = ["id" => 0, "authenticated" => false];
    
            /**
             * Returns the JSON with only user ID and authentication boolean
             */
            try {
    
                $connection = Configuration::openConnection();

                $statement = $connection->prepare("SELECT * FROM `energy_matrix`.`users` WHERE `username`=:username1");

                $statement->bindParam(":username1", $data->username, PDO::PARAM_STR);

                $statement->execute();
    
                if ($statement->rowCount() > 0) {
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
                    $userInfo['id'] = $result['id'];
                    $userInfo['company'] = $result['company'];

                    $userInfo['authenticated'] = password_verify($data->password, $result['password']);
    
                }

                
                
                Configuration::closeConnection();
    
                return json_encode($userInfo, JSON_PRETTY_PRINT);
    
            }
            catch (PDOException $pdo) {

                
                return "PDO Error: " . $e->getMessage();
            }
            catch (Exception $e) {

                return json_encode('{'.$data->username.'}', JSON_PRETTY_PRINT);
                return "Error: " . $e->getMessage();
            }
    
            return json_encode($userInfo, JSON_PRETTY_PRINT);
        }
    }
 
 ?>