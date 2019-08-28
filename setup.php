<?php
/**
 * Created by PhpStorm.
 * User: gio
 * Date: 24/06/2018
 * Time: 17:56
 */
echo "setup<br>";
require_once ("mysql_credentials.php");
$con=new mysqli($mysql_server,$mysql_user,$mysql_pass);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: ". mysqli_connect_error();
    exit();
}
if ($con->select_db($mysql_db))
    echo "database ok<br>";
else
    include "create_db.php";

include "users/create_table.php";
include "categories/create_table.php";
include "message/create_table.php";
include "post/create_table.php";