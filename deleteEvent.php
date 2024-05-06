<?php
include "layout.php";

$id=$_GET['id']; 
$pdo = new \PDO('mysql:host=localhost;dbname=donkeyEvent', 'root','Masgroovy_04');
$statement = $pdo->prepare("DELETE FROM event WHERE idevent = :id");
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$event = $statement->fetch(PDO::FETCH_ASSOC);
echo "<pre>";
var_dump($event);
echo "</pre>";

header("location:agenda.php");
?>