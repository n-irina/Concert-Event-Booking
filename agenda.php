<?php
include "layout.php";

$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');
$statement = $pdo->query("select * from event");
$event = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("SELECT * FROM event ORDER BY date");
$statement->execute();
$event = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<h1 class="text-center mt-5">Tous nos concerts</h1>

<a href="addEvent.php" class="btn btn-outline-secondary" style="margin-left:30px; margin-bottom:30px;">Ajouter un nouvel évent</a>

<div class="container">
    <div class="row justify-content-center">
        <?php foreach ($event as $oneEvent) { ?>
            <div class="col-md-3 mb-3">
                <div class="card" style="height: 100%;">
                    <img src="<?= $oneEvent['picture'] ?>" class="card-img-top" style="object-fit: contain; height: 200px; margin-top:15px;">
                    <div class="card-body text-center">
                        <h3 class="card-title"><a href="detailEvent.php?id=<?= $oneEvent['idevent'] ?>"><?= $oneEvent['eventName'] ?></a></h3>
                        <div class="card-text">
                            <h4><?= $oneEvent['category'] ?></h4>
                        </div>
                        <div class="cardtext text-center">
                            <h5>?= $oneEvent['date'] ?><h5>
                        </div>
                        <div>
                            <h6><?= $oneEvent['price'] ?> €</h6>
                        </div><br>
                        <div>
                            <a href="deleteEvent.php?id=<?= $oneEvent['idevent'] ?>" class="btn btn-outline-secondary">Supprimer</a>
                            <a href="modifyEvent.php?id=<?= $oneEvent['idevent'] ?>" class="btn btn-outline-secondary">Modifier</a>
                        </div><br>
                        <div>
                            <a href="booking.php?id=<?= $oneEvent['idevent'] ?>" class="btn btn-outline-secondary">Billeterie</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>