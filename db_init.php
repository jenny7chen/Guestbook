<?php

require_once 'DBSetting.php';
$dbSetting = new DBSetting();
try {
    $dbh = new PDO("mysql:host=$dbSetting->host;port=$dbSetting->port", $dbSetting->username, $dbSetting->password);
    $dbh->exec("CREATE DATABASE `$dbSetting->dbname`;
                CREATE USER '$dbSetting->user1'@'localhost' IDENTIFIED BY '$dbSetting->user1pass';
                GRANT ALL ON `$dbSetting->dbname`.* TO '$dbSetting->user1'@'localhost';
                FLUSH PRIVILEGES;");
} catch (PDOException $e) {
    echo 'database create faild'.$e->getMessage();
}
