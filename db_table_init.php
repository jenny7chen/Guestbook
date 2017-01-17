<?php

require_once 'DBSetting.php';
$dbSetting = new DBSetting();
try {
    $dbh = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);

    $dbh->exec('create table account(name varchar(30) CHARACTER SET utf8, password varchar(8) CHARACTER SET utf8, primary key (name))')
        or error_log('table create failed', '3', 'guestbook.log');
    $dbh->exec('create table posts(id int NOT NULL AUTO_INCREMENT, author varchar(30), update_time DATETIME, title varchar(100) CHARACTER SET utf8, content varchar(2000) CHARACTER SET utf8, filepath varchar(100) CHARACTER SET utf8, primary key (id))')
            or error_log('table2 create failed', '3', 'guestbook.log');

} catch (PDOException $e) {
    echo 'table create faild'.$e->getMessage();
}
