<?php

$username = 'root';
$dsn = 'mysql:host=localhost; dbname=makan_apa';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $ex) {
    echo "Connected failed ".$ex->getMessage();
}
