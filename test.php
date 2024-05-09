<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier - DonkeyEvent</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Votre Panier</h1>
    <?php
    session_start();
    include "layout.php";
    $pdo = new PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');
    $idDates = array_keys($_SESSION['cart']); // S'assurer que les clés sont des id_date
    $placeholders = implode(',', array_fill(0, count($idDates), '?'));

    $query = "SELECT event.idevent, event.eventName, event.category, date.date, date.iddate, artist.name, event.price
              FROM event
              JOIN date ON event.idevent = date.idevent
              JOIN event_has_artist ON event.idevent = event_has_artist.idevent
              JOIN artist ON event_has_artist.idartist = artist.idartist
              WHERE date.iddate IN ($placeholders)";
    $statement = $pdo->prepare($query);
    $statement->execute($idDates);
    $events = $statement->fetchAll(PDO::FETCH_ASSOC);
    $eventsByIdDate = []; // Changement pour indexer par id_date
    foreach ($events as $event) {
        $eventsByIdDate[$event['iddate']] = $event; // Utilisation de id_date comme clé
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
            <?php foreach ($_SESSION['cart'] as $idDate => $quantity) {
                if (isset($eventsByIdDate[$idDate])) {
                    $event = $eventsByIdDate[$idDate];
                    $total = $event['price'] * $quantity; ?>
                    <tr>
                        <td><?= htmlspecialchars($idDate) ?></td>
                        <td><?= htmlspecialchars($event['eventName']) ?></td>
                        <td><?= htmlspecialchars($event['price']) ?></td>
                        <td><?= htmlspecialchars($event['date']) ?></td>
                        <td>
                            <a href="decrease_cart.php?id=<?= htmlspecialchars($idDate) ?>">-</a>
                            <?= htmlspecialchars($quantity) ?>
                            <a href="increase_cart.php?id=<?= htmlspecialchars($idDate) ?>">+</a>
                        </td>
                        <td><?= htmlspecialchars($total) ?></td>
                        <td><a href="removecart.php?id=<?= htmlspecialchars($idDate) ?>">Supprimer</a></td>
                    </tr>
                    <?php $totalPrice += $total;
                }
            } ?>
        </tbody>
    </table>
    <p>Total : <?= htmlspecialchars($totalPrice) ?> €</p>
    <a href="emptycart.php">Vider le panier</a>
</body>
</html>
