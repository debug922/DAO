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
$con->query("SHOW TABLES LIKE 'post'");
if ($con->affected_rows==1)
    echo "Table exists<br>";
else{
    echo "Table does not exist<br>";
    $create="CREATE TABLE post ( 
                                      id int not null auto_increment primary key ,
                                      dataCreation timestamp DEFAULT CURRENT_TIMESTAMP,
                                      dataI date not null,
                                      dataF date not null,
                                      description varchar(1000) not null,
                                      available int not null,
                                      title varchar(70),
                                      photo boolean not null default false ,
                                      tipo varchar(30) not null, 
                                      location varchar(50) not null,
                                      constraint tok check(tipo in ( 'sport', 'event','trip','activity','study')),
                                      constraint dataOk check(dataI<=dataF)
										)
                                        ENGINE = InnoDB;";
    if (!$con->query($create)) {
        echo "error on creation table " . $con->error;
        exit();
    }
    $create="CREATE TABLE lovePost ( 
                                      id int not null,
                                      username varchar(20) not null,
                                      FOREIGN KEY (id) REFERENCES post(id) ON DELETE CASCADE,
                                      FOREIGN KEY (username) REFERENCES users(username)ON DELETE CASCADE,
                                      primary key (id,username)
										)
                                        ENGINE = InnoDB;";
    if (!$con->query($create)) {
        echo "error on creation table " . $con->error;
        exit();
    }
    $create="CREATE TABLE CreatePost ( 
                                      id int not null,
                                      username varchar(20) not null,
                                      FOREIGN KEY (id) REFERENCES post(id) ON DELETE CASCADE,
                                      FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE,
                                      primary key (id,username)
										)
                                        ENGINE = InnoDB;";
    if (!$con->query($create)) {
        echo "error on creation table " . $con->error;
        exit();
    }

    echo "tables are created (post, CreatePost, lovePost)<br>";
}
$con->close();