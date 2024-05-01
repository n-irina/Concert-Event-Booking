<?php

include "layout.php";

$id = $_GET['id'];


$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');
$statement = $pdo->query("SELECT * FROM event WHERE idevent=$id");

$event = $statement->fetch(PDO::FETCH_ASSOC);

?>


<div class="card" style="width: 25rem;">
    <div class="card-body">
        <h4 class="card-title text-center" style="margin-top:20px; margin-top:20px;"><?= $event['eventName'] ?>
        </h4>
        <p class="card-text text-center">
            <img src="<?= $event['picture'] ?>" class="card-img-top" style="object-fit: contain; height:300px; margin-top:10px; margin-bottom:15px;">

            <?php
            $event_statement = $pdo->prepare("SELECT * FROM event WHERE idevent = :idevent");
            $event_statement->bindValue(':idevent', $id, \PDO::PARAM_INT);
            $event_statement->execute();

            $event = $event_statement->fetchAll(PDO::FETCH_ASSOC);

            ?>Concert :
            <?= $event['eventName'] ?> :<br>
            <?php foreach ($event as $oneEvent) { ?>
                <a href="detailEvent.php?id=<?= $oneEvent['idevent'] ?>">
                    <?= $oneEvent['eventName'] ?></a><br>
            <?php
            }
            ?>
        </p>
        <p>
            Categorie : <tr><?= $event['category'] ?>
        </p>
    </div>
</div>