<?php
include_once '../resource/session.php';
include_once '../resource/utilities.php';
include_once '../resource/Database.php';

$id = $_SESSION['id'];
                
$sqlUpdate = "UPDATE users SET Status = 'Offline now' WHERE id = :id";
$statement = $db->prepare($sqlUpdate);
$statement->execute(array(':id' => $id));

signout();

