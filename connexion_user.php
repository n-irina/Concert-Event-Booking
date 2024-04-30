<?php

include "layout.php";

$email = $_POST['email'];
$password = $_POST['password'];
$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');


$statement = $pdo->prepare("INSERT INTO user (email,password) VALUES (:email, :password)");
$statement->bindValue(':email', $email, PDO::PARAM_STR);
$statement->bindValue(':password', $password, PDO::PARAM_STR);

if ($statement->execute()) {
    $_SESSION["user"] = $email;
    header("location:agenda.php");
    exit;
}

