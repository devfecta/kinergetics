<?php

    class User {

        public function __construct() {}


        public function register($formData) {
            $data = json_decode(json_encode($formData), false);
    
            $userInfo = ["authenticated" => false];
    
            /**
             * Returns the JSON with only user ID and authentication boolean
             */
            try {
    
                $connection = Configuration::openConnection();

                $statement = $connection->prepare("INSERT INTO users (`company`, `username`, `password`) VALUES (:company, :username, :password)");
                $statement->bindParam(":company", $data->company);
                $statement->bindParam(":username", $data->username);
                $statement->bindParam(":password", password_hash($data->password, PASSWORD_BCRYPT));
                $statement->execute();
    
                if ($newUserId = $connection->lastInsertId() > 0) {
                    $userInfo['authenticated'] = true;
                }

                Configuration::closeConnection();
    
                return json_encode($userInfo, JSON_PRETTY_PRINT);
    
            }
            catch (PDOException $pdo) {
                return json_encode(array('error'=> $pdo->getMessage()), JSON_PRETTY_PRINT);
            }
            catch (Exception $e) {
                return json_encode(array('error'=> $e->getMessage()), JSON_PRETTY_PRINT);
            }
    
            return json_encode($userInfo, JSON_PRETTY_PRINT);
        }

        
        public function login($formData) {
            $data = json_decode(json_encode($formData), false);
    
            $userInfo = ["id" => 0, "authenticated" => false];
    
            /**
             * Returns the JSON with only user ID and authentication boolean
             */
            try {
    
                $connection = Configuration::openConnection();

                $statement = $connection->prepare("SELECT * FROM `users` WHERE `username`=:username1");

                $statement->bindParam(":username1", $data->username, PDO::PARAM_STR);

                $statement->execute();
    
                if ($statement->rowCount() > 0) {
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
                    $userInfo['id'] = $result['id'];
                    $userInfo['company'] = $result['company'];
                    $userInfo['type'] = $result['type'];

                    $userInfo['authenticated'] = password_verify($data->password, $result['password']);
    
                }

                Configuration::closeConnection();
    
                return json_encode($userInfo, JSON_PRETTY_PRINT);
    
            }
            catch (PDOException $pdo) {
                return json_encode(array('error'=> $pdo->getMessage()), JSON_PRETTY_PRINT);
            }
            catch (Exception $e) {
                return json_encode(array('error'=> $e->getMessage()), JSON_PRETTY_PRINT);
            }
    
            return json_encode($userInfo, JSON_PRETTY_PRINT);
        }
    }
 
 ?>