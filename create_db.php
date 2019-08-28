<?php

    require_once ("mysql_credentials.php");
    $con=new mysqli($mysql_server,$mysql_user,$mysql_pass);
    if ($con->connect_error)
        die("Connection failed: " . $con->connect_error);

    $sql="CREATE DATABASE ".$mysql_db.";";
    if ($con->query($sql))
        echo "Database  created successfully<br>";
     else
        echo "Error creating database: " . $con->error;

     $con->close();

