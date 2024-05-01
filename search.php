<?php
include "layout.php";

?>
<div>
    <?php
    $search = $_GET['search'];

    $pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    $statement = $pdo->prepare("SELECT * FROM event");
    $statement->execute();
    $event = $statement->fetchAll(PDO::FETCH_ASSOC);


    if (empty($_GET['search'])) {
        header("location:donkeyEvent.php");
    } elseif (count($event) > 0) {
        echo "<h5>Résultats de la recherche pour '$search' : </h5>";
        foreach ($event as $oneEvent) {
            ?>
            <br>
            <tr><br>
                <td><a href="detailEvent.php?id=<?= $oneEvent['idevent'] ?>">
                        <?= $oneEvent['eventName'] ?></a>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "Aucun résultat trouvé pour '$search'.";
    }