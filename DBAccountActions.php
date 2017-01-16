<?php

class DBAccountActions
{
    public function createUser($name, $password)
    {
        require_once 'DBSetting.php';
        $dbSetting = new DBSetting();
        try {
            $dbh = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $stmt = $dbh->prepare('INSERT INTO account (name, password) VALUES (:name, :password)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':password', $password);
            $success = $stmt->execute();
            return $success;
        } catch (PDOException $e) {
            // echo 'Connection failed: '.$e->getMessage();
          return false;
        }
    }
}
