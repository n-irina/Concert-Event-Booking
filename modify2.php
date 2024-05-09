<?php
include "header.php";

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$request1 = "SELECT * FROM event WHERE idevent = :id";
$statement1 = $pdo->prepare($request1);
$statement1->bindParam(':id', $id, PDO::PARAM_INT);
$statement1 ->execute();
$event = $statement1->fetch(PDO::FETCH_ASSOC);


$request2 = "SELECT name FROM artist
             JOIN event_has_artist ON artist.idartist = event_has_artist.idartist
             JOIN event ON event_has_artist.idevent = event.idevent
             WHERE event.idevent = :id";
$statement2 = $pdo ->prepare($request2);
$statement2->bindParam(':id', $id, PDO::PARAM_INT);
$statement2 ->execute();
$artists = $statement2->fetchAll(PDO::FETCH_ASSOC);


$request3 = "SELECT iddate, date, time FROM date
             JOIN event ON event.idevent = date.idevent
             WHERE event.idevent = :id";
$statement3 = $pdo ->prepare($request3);
$statement3->bindParam(':id', $id, PDO::PARAM_INT);
$statement3 ->execute();
$dates = $statement3->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="detailcontainer">
    <div class="modify-event-card">
        <form action="modify2action.php?id=<?=$id?>" method="POST" >
            Modifier le nom du concert:
            <input class="form-label" name="eventName" type="text" placeholder="<?= htmlspecialchars($event['eventName']) ?>"><br>
            
            Modifier le nom de l'artiste ou des artistes:
                <?php foreach($artists as $artist){?>
                    <input class="form-label" name="artists[]" type="text" placeholder="<?=$artist['name']?>"><br>

         <?php
            }
            ?>
           

            Modifier la catégorie:
            <input class="form-label" name="category" type="text" placeholder="<?= htmlspecialchars($event['category']) ?>"><br>

            Modifier la date:
                <?php foreach($dates as $date){?>
                    <input class="form-label" name="dates[]" type="date" value="<?=$date['date']?>"><br>
                <?php
                }
                ?>
           

            Modifier l'heure:
            <input class="form-label" name="time" type="time" value="<?= htmlspecialchars(date('H:i', strtotime($dates[0]['time']))) ?>"><br>
            
            Modifier le nombre de places:
            <input class="form-label" name="nbPlaces" type="number" placeholder="<?= htmlspecialchars($event['numberPlaces']) ?>"><br>

            Modifier le prix (en €):
            <input class="form-label" name="price" type="text" placeholder ="<?= htmlspecialchars(str_replace(' €', '', $event['price'])) ?>"><br>

            <label for="upload">Envoyer une image</label><br>
            <img src="<?= $event['picture'] ?>" style="max-width: 200px;">
            <input type="file" name="picture" id="upload"><br>

            <input type="submit" value="Modifier">
        </form>
    </div>
</div>

<?php

include "footer.php";

?>