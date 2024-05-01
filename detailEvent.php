<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Événement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

    </style>
</head>

<?php
include "layout.php";


$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');
$statement = $pdo->query("select * from event");
$event = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("SELECT * FROM event ORDER BY date");
$statement->execute();
$event = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<body>
    <section class="detailEvent">
        <?php foreach ($event as $oneEvent) { ?>

            <div class="detailcontainer">

                <div class="event-card">
                    <div class="event-image"><img src="imageconcertbeyonce.jpg" alt=""></div>
                    <div class="event-details">
                        <div>
                            <h4><?= $oneEvent['eventName'] ?></h4>
                            <p class="event-info"><?= $oneEvent['category'] ?><br>
                                Artiste : <?= $oneEvent['artist'] ?></p>
                                <p>Date : <?= $oneEvent['date'] ?> </p>
                        </div>
                        <div>
                            <p class="event-info">Salle de concert<br>
                                <?= $oneEvent['concertHall'] ?></p>
                            <button class="btn btn-primary">Reservez</button>
                        </div>
                        <div>
                            <span class="price"><?= $oneEvent['price'] ?>€</span>

                        </div>
                    </div>
                </div>
            </div>
            </div>
    </section>
</body>

</html>


<?php } ?>