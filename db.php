<?php

$db['host']         = 'localhost';
$db['username']     = 'db_username_here';
$db['password']     = 'db_password_here';
$db['db_name']      = 'db_name_here';

foreach($db as $key => $value){
    define(strtoupper($key), $value);
}

$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME);


if(!$conn){
    die("Unable to connect to the database.");
}

?>