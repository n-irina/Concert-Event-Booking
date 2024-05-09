<?php
include "header.php";

$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');

//on stock l'id de l'évnt à modifier
$id = $_GET['id'];

//on stock les données du post
$eventName = $_POST['eventName'];
$category = $_POST['category'];
$nbPlaces = $_POST['nbPlaces'];
$price = $_POST['price'];
$dates = $_POST['dates'];
$time = $_POST['time'];
$newArtists = $_POST['artists'];

//On récupère les noms des artistes déjà existants
$request = "SELECT idartist, name FROM artist";
$statement = $pdo->query($request);
$allArtists =  $statement->fetchAll(PDO::FETCH_ASSOC);

//on stock les noms des artistes à comparer dans un tableau
$artists = [];
foreach ($allArtists as $allArtist) {
    $artists[] = $allArtist['name'];
}

//on stock les id des artistes déjà existants
$ids = [];
foreach ($newArtists as $newArtist) {
    foreach ($allArtists as $allArtist) {
        if ($newArtist == $allArtist['name']) {
            $ids[] = $allArtist['idartist'];
        }
    }
}

//on stock les noms des artistes à rajouter
$names = [];
foreach ($newArtists as $newArtist) {
    if (array_search($newArtist, $artists) == false) {
        $names[] = $newArtist;
    }
}

//si les artistes n'existent pas on rajoute les artistes à la table artiste et on stock le nouvel id dans le tableau d'ids
foreach ($names as $name) {
    $request1 = "INSERT INTO artist (name) VALUES (:name)";
    $statement1 = $pdo->prepare($request1);
    $statement1->bindParam(':name', $name);
    $statement1->execute();
    $ids[] = $pdo->lastInsertId();
}

//on update la table event avec les nouvelles données du POST
$request2 = "UPDATE event SET price = :price, numberPlaces = :nbPlaces, eventName = :name, category = :category WHERE idevent = :id ";
$statement2 = $pdo->prepare($request2);
$statement2->bindParam(':price', $price, PDO::PARAM_INT);
$statement2->bindParam(':nbPlaces', $nbPlaces, PDO::PARAM_INT);
$statement2->bindParam(':name', $eventName, PDO::PARAM_STR);
$statement2->bindParam(':category', $category, PDO::PARAM_STR);
$statement2->bindParam(':id', $id, PDO::PARAM_INT);
$statement2->execute();

//On supprime les données de event_has_artist avant de les update
$request4 = "DELETE FROM event_has_artist WHERE idevent = :id";
$statement4 = $pdo->prepare($request4);
$statement4->bindParam(':id', $id, PDO::PARAM_INT);
$statement4->execute();

//on update la table event_has_artist avec les id des artistes récupérés dans ids
foreach ($ids as $oneId) {
    $request3 = "INSERT INTO event_has_artist (idevent, idartist) VALUES (:idevent, :idartist)";
    $statement3 = $pdo->prepare($request3);
    $statement3->bindParam(':idartist', $oneId, PDO::PARAM_INT);
    $statement3->bindParam(':idevent', $id, PDO::PARAM_INT);
    $statement3->execute();
}

//on supprimes les données de date avant de les update
$request6 = "DELETE FROM date WHERE idevent = :id";
$statement6 = $pdo->prepare($request6);
$statement6->bindParam(':id', $id, PDO::PARAM_INT);
$statement6->execute();

//on update la table date avec les nouvelles dates du POST
foreach ($dates as $date) {
    $request5 = "INSERT INTO date (date, time, idevent) VALUES (:date, :time, :idevent)";
    $statement5 = $pdo->prepare($request5);
    $statement5->bindParam(':date', $date, PDO::PARAM_STR);
    $statement5->bindParam(':time', $time, PDO::PARAM_INT);
    $statement5->bindParam(':idevent', $id, PDO::PARAM_INT);
    $statement5->execute();
}

header("location:detailEvent.php?id=$id");

include "footer.php";
