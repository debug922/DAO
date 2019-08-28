<?php
/**
 * Created by PhpStorm.
 * User: gio
 * Date: 23/06/2018
 * Time: 22:18
 */
//require_once ("../mysql_credentials.php");
include __DIR__ .('/../mysql_credentials.php');

$con=new mysqli($mysql_server,$mysql_user,$mysql_pass,$mysql_db);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: ". mysqli_connect_error();
    exit();
}
$con->query("SHOW TABLES LIKE 'categories'");
if ($con->affected_rows==1)
    echo "Table exists<br>";
else{
    echo "Table does not exist<br>";
    $create="CREATE TABLE categories ( 
                                      username VARCHAR(20) not null,
                                      sport boolean default false,
                                      event boolean default false,
                                      trip boolean default false,
                                      activity boolean default false,
                                      study boolean default false,
                                      FOREIGN KEY (username) REFERENCES users(username),
                                      primary key (username)
										)
                                        ENGINE = InnoDB;";
    if (!$con->query($create)) {
        echo "error on creation table " . $con->error;
        exit();
    }
    echo "categories is created<br>";
}
$con->close();