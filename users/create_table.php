<?php
/**
 * Created by PhpStorm.
 * User: gio
 * Date: 23/06/2018
 * Time: 22:18
 */
require_once (__DIR__.'/../mysql_credentials.php');

$con=new mysqli($mysql_server,$mysql_user,$mysql_pass,$mysql_db);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: ". mysqli_connect_error();
    exit();
}
$con->query("SHOW TABLES LIKE 'users'");
if ($con->affected_rows==1)
    echo "Table exists<br>";
else{
    echo "Table does not exist<br>";
    $create="CREATE TABLE users ( 
                                      firstname VARCHAR(40) NOT NULL , 
                                      surname VARCHAR(40) NOT NULL , 
                                      email VARCHAR(40) NOT NULL unique, 
                                      username VARCHAR(20) NOT NULL PRIMARY KEY, 
                                      password VARCHAR(255) NOT NULL ,
                                      photo boolean not null default false,
                                      city VARCHAR(100),
                                      description varchar(500)
										)
                                        ENGINE = InnoDB;";
    if (!$con->query($create))
        echo "error on creation table ".$con->error;
    echo "users is created<br>";
}
$con->close();