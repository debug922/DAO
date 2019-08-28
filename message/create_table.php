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
$con->query("SHOW TABLES LIKE 'message'");
if ($con->affected_rows==1)
    echo "Table exists<br>";
else{
    echo "Table does not exist<br>";
    $create="CREATE TABLE message (
                                      username VARCHAR(20) NOT NULL,
                                      receiver varchar(20) not null,
                                      times timestamp DEFAULT CURRENT_TIMESTAMP,
                                      texts varchar(2000) not null,
                                      FOREIGN KEY (username) REFERENCES users(username),
                                      FOREIGN KEY (receiver) REFERENCES users(username),
                                      constraint sameNot check( username<>receiver),
                                      primary key (username,times)
										)
                                        ENGINE = InnoDB;";
    if (!$con->query($create)) {
        echo "error on creation table " . $con->error;
        exit();
    }
    echo "message is created<br>";
}
$con->close();