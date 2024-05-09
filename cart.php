<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier - DonkeyEvent</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    session_start();
    include "layout.php";
?>
    <h1>Votre Panier</h1>
    <?php
   
    
    $pdo = new PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');
    $iddates = array_keys($_SESSION['cart']); 
    $placeholders = implode(',', array_fill(0, count($iddates), '?'));

    $query = "SELECT event.idevent, event.eventName, event.category, date.date, date.iddate, artist.name, event.price
              FROM event
              JOIN date ON event.idevent = date.idevent
              JOIN event_has_artist ON event.idevent = event_has_artist.idevent
              JOIN artist ON event_has_artist.idartist = artist.idartist
              WHERE date.iddate IN ($placeholders)";
    $statement = $pdo->prepare($query);
    $statement->execute($iddates);
    $events = $statement->fetchAll(PDO::FETCH_ASSOC);
    $eventsByIddate = []; 
    foreach ($events as $event) {
        $eventsByIddate[$event['iddate']] = $event; 
    }

    $totalPrice = 0;
    ?>
    <table>
        <thead>
            <tr>
                <th>ID Date</th>
                <th>Nom du concert</th>
                <th>Prix (€)</th>
                <th>Date</th>
                <th>Quantité</th>
                <th>Total (€)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $iddate => $quantity) {
                if (isset($eventsByIddate[$iddate])) {
                    $event = $eventsByIddate[$iddate];
                    $total = $event['price'] * $quantity; ?>
                    <tr>
                        <td><?= htmlspecialchars($iddate) ?></td>
                        <td><?= htmlspecialchars($event['eventName']) ?></td>
                        <td><?= htmlspecialchars($event['price']) ?></td>
                        <td><?= htmlspecialchars($event['date']) ?></td>
                        <td>
                            <a href="decrease_cart.php?id=<?= htmlspecialchars($iddate) ?>">-</a>
                            <?= htmlspecialchars($quantity) ?>
                            <a href="increase_cart.php?id=<?= htmlspecialchars($iddate) ?>">+</a>
                        </td>
                        <td><?= htmlspecialchars($total) ?></td>
                        <td><a href="removecart.php?id=<?= htmlspecialchars($iddate) ?>">Supprimer</a></td>
                    </tr>
                    <?php $totalPrice += $total;
                }
            } ?>
        </tbody>
    </table>
    <p>Total : <?= htmlspecialchars($totalPrice) ?> €</p>
    <button><a href="emptycart.php">Vider le panier</a> &nbsp;</button>
   
    <button><a href="booking.php?id=">Valider votre commande</a></button>
    
</body>
</html>
