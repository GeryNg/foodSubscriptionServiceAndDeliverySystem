<?php

$username = 'root';
$dsn = 'mysql:host=localhost; dbname=makan_apa';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $ex) {
    echo "Connected failed ".$ex->getMessage();
}
