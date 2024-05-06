<?php
include "layout.php";

$id=$_GET['id']; 
$pdo = new \PDO('mysql:host=localhost;dbname=donkeyEvent', 'root','Masgroovy_04');
$statement = $pdo->prepare("DELETE FROM booking WHERE idbooking = :id");
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$booking = $statement->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
var_dump($booking);
echo "</pre>";

header("location:donkeyEvent.php");
?>