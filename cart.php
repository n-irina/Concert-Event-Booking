<?php
include "header.php";
session_start();

echo "<pre>";
var_dump($_SESSION['cart']);
echo "</pre>";

$pdo = new PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');

?>

<table border="1">
    <tr>
        <td>idevent</td>
        <td>Nom du concert</td>
        <td>Prix</td>
        <td>Date</td>
        <td>Quantité</td>
        <td>Total</td>
        <td>Supprimer du panier</td>
    </tr>
    <?php
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $key => $row) {
        $query = "SELECT event.idevent, event.eventName, event.category, date.date, artist.name, event.price
                  FROM event
                  JOIN date ON event.idevent = date.idevent
                  JOIN event_has_artist ON event.idevent = event_has_artist.idevent
                  JOIN artist ON event_has_artist.idartist = artist.idartist
                  WHERE event.idevent = :idevent";

        $statement = $pdo->prepare($query);
        $statement->bindParam(':idevent', $key, PDO::PARAM_INT);
        $statement->execute();
        $event = $statement->fetch(PDO::FETCH_ASSOC);
        $total = $event['price'] * $row;
    ?>
        <tr>
            <td><?= $key ?></td>
            <td><?= $event['eventName'] ?></td>
            <td><?= $event['price'] ?></td>
            <td><?= $event['date'] ?></td>
            <td>
                <a href="reduire_quantite.php?id=<?= $key ?>">-</a>
                <?= $row ?>
                <a href="augmenter_quantite.php?id=<?= $key ?>">+</a>
            </td>
            <td><?= $total ?></td>
            <td><a href="removecart.php?id=<?= $key ?>">Supprimer du panier</a></td>
        </tr>
    <?php
        $totalPrice += $total;
    }
    ?>
</table>
Total : <?= $totalPrice ?>
<br>
<a href="viderpanier.php">Vider le panier</a>
