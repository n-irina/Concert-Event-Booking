<?php
session_start();
echo "<pre>";
var_dump($_SESSION['cart']);
echo "</pre>";
$pdo = new PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');

// Récupérer tous les idevent du panier
$eventIds = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($eventIds), '?'));

// Préparation de la requête pour récupérer les données de tous les événements concernés en une seule fois
$query = "SELECT event.idevent, event.eventName, event.category, date.date, artist.name, event.price
          FROM event
          JOIN date ON event.idevent = date.idevent
          JOIN event_has_artist ON event.idevent = event_has_artist.idevent
          JOIN artist ON event_has_artist.idartist = artist.idartist
          WHERE event.idevent IN ($placeholders)";
$statement = $pdo->prepare($query);
$statement->execute($eventIds);

// Récupération des résultats dans un tableau associatif clé/valeur où la clé est 'idevent'
$events = $statement->fetchAll(PDO::FETCH_ASSOC);
$eventsById = [];
foreach ($events as $event) {
    $eventsById[$event['idevent']] = $event;
}

$totalPrice = 0;
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
    foreach ($_SESSION['cart'] as $key => $quantity) {
        if (isset($eventsById[$key])) {
            $event = $eventsById[$key];
            $total = $event['price'] * $quantity;
            ?>
            <tr>
                <td><?= htmlspecialchars($key) ?></td>
                <td><?= htmlspecialchars($event['eventName']) ?></td>
                <td><?= htmlspecialchars($event['price']) ?></td>
                <td><?= htmlspecialchars($event['date']) ?></td>
                <td>
                    <a href="decrease_cart.php?id=<?= htmlspecialchars($key) ?>">-</a>
                    <?= htmlspecialchars($quantity) ?>
                    <a href="increase_cart.php?id=<?= htmlspecialchars($key) ?>">+</a>
                </td>
                <td><?= htmlspecialchars($total) ?></td>
                <td><a href="removecart.php?id=<?= htmlspecialchars($key) ?>">Supprimer du panier</a></td>
            </tr>
            <?php
            $totalPrice += $total;
        }
    }
    ?>
</table>
Total : <?= htmlspecialchars($totalPrice) ?>
<br>
<a href="emptycart.php">Vider le panier</a>
