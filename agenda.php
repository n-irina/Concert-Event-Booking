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
    <h1 class="text-center mt-5">Tous nos concerts</h1>

    <a href="addEvent.php" class="btn btn-outline-secondary" style="margin-left:30px;">Ajouter un nouvel évent</a>


    <?php foreach ($event as $oneEvent) { ?>
        <h2><?= $oneEvent['date'] ?><h2>
                <h5 class="card-title"><a href="detailEvent.php?id=<?= $oneEvent['eventName'] ?>"><?= $oneEvent['eventName'] ?></a></h5>
                <?= $oneEvent['category'] ?>
                <div>
                    <a href="deleteEvent.php?id=<?= $oneEvent['idevent'] ?>" class="btn btn-outline-secondary">Supprimer</a>
                    <a href="modifyEvent.php?id=<?= $oneEvent['idevent'] ?>" class="btn btn-outline-secondary">Modifier</a>
                    <a href="booking.php?id=<?= $oneEvent['idevent'] ?>" class="btn btn-outline-secondary">Billetterie</a>
                </div>


            <?php } ?>

</body>