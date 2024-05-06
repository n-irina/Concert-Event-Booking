<?php
include "layout.php";

$id= $_GET['id']; 
$pdo = new \PDO('mysql:host=localhost;dbname=donkeyEvent', 'root','Masgroovy_04');
$statement = $pdo->prepare("DELETE FROM user WHERE iduser = :id");
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);
echo "<pre>";
var_dump($user);
echo "</pre>";

header("location:donkeyEvent.php");
?>