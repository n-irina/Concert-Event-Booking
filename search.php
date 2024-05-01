<?php
include "layout.php";

?>
<div>
    <?php
    $search = $_GET['search'];

    $pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $statement = $pdo->prepare("SELECT * FROM event WHERE category LIKE :search OR eventName LIKE :search");
    $statement->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $statement->execute();
    $event = $statement->fetchAll(PDO::FETCH_ASSOC);
    ?>



<?php
        if (empty($_GET['search'])) {
            header("location:donkeyEvent.php");
        } elseif (count($event) > 0) {
            ?>
    <section class="detailEvent">
        <?php foreach ($event as $oneEvent) { ?>

            <div class="detailcontainer">

                <div class="event-card">
                    <div class="event-image"><img src="<?= $oneEvent['picture'] ?>"></div>
                    <div class="event-details">
                        <div>
                            <h4><?= $oneEvent['eventName'] ?></h4>
                            <p class="event-info"><?= $oneEvent['category'] ?><br>
                                Artiste : <?= $oneEvent['artist'] ?></p>
                            <p>Date : <?= $oneEvent['date'] ?> </p>
                        </div>
                        <div>
                            <button class="btn btn-primary">Reservez</button>
                        </div>
                        <div>
                            <span class="price"><?= $oneEvent['price'] ?>€</span>

                        </div>
                    </div>
                </div>
            </div>
</div>
<?php } ?> 
</section>
<?php } ?>


    