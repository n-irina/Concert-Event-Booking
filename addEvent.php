<?php
include "layout.php";

$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', 'Masgroovy_04');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Démarrer une transaction pour assurer l'intégrité des données
    $pdo->beginTransaction();

    try {
        $eventName = filter_input(INPUT_POST, 'eventName');
        $artistName = filter_input(INPUT_POST, 'artist');
        $category = filter_input(INPUT_POST, 'category');
        $date = filter_input(INPUT_POST, 'date');
        $time = filter_input(INPUT_POST, 'time');
        $price = filter_input(INPUT_POST, 'price');

        // Insérer l'événement dans la table event
        $sqlEvent = "INSERT INTO event (eventName, category, price) VALUES (:eventName, :category, :price)";
        $stmtEvent = $pdo->prepare($sqlEvent);
        $stmtEvent->bindParam(':eventName', $eventName);
        $stmtEvent->bindParam(':category', $category);
        $stmtEvent->bindParam(':price', $price);
        $stmtEvent->execute();
        $eventId = $pdo->lastInsertId();

        // Insérer la date dans la table date
        $sqlDate = "INSERT INTO date (idevent, date, time) VALUES (:idevent, :date, :time)";
        $stmtDate = $pdo->prepare($sqlDate);
        $stmtDate->bindParam(':idevent', $eventId);
        $stmtDate->bindParam(':date', $date);
        $stmtDate->bindParam(':time', $time);
        $stmtDate->execute();

        // Insérer l'artiste dans la table artist
        $sqlArtist = "INSERT INTO artist (name) VALUES (:artistName)";
        $stmtArtist = $pdo->prepare($sqlArtist);
        $stmtArtist->bindParam(':artistName', $artistName);
        $stmtArtist->execute();
        $artistId = $pdo->lastInsertId();

        // Insérer l'association entre l'événement et l'artiste dans la table event_has_artist
        $sqlEventArtist = "INSERT INTO event_has_artist (idevent, idartist) VALUES (:idevent, :idartist)";
        $stmtEventArtist = $pdo->prepare($sqlEventArtist);
        $stmtEventArtist->bindParam(':idevent', $eventId);
        $stmtEventArtist->bindParam(':idartist', $artistId);
        $stmtEventArtist->execute();

        if (!empty($_FILES['picture']['name'])) {
            $newFilename = uniqid() . '_' . basename($_FILES['picture']['name']);
            $dossierTempo = $_FILES['picture']['tmp_name'];
            $dossierSite = 'uploads/' . $newFilename;
            if (move_uploaded_file($dossierTempo, $dossierSite)) {
                // Mettre à jour le champ 'picture' pour l'événement inséré
                $sqlUpdatePicture = "UPDATE event SET picture = :picture WHERE idevent = :idevent";
                $stmtUpdatePicture = $pdo->prepare($sqlUpdatePicture);
                $stmtUpdatePicture->bindParam(':picture', $dossierSite);
                $stmtUpdatePicture->bindParam(':idevent', $eventId);
                $stmtUpdatePicture->execute();
            }
        }

        // Si tout est ok, commit les changements
        $pdo->commit();
        header("Location: agenda.php");
        exit;
    } catch (Exception $e) {
        // En cas d'erreur, annuler toutes les opérations
        $pdo->rollBack();
        echo "Erreur lors de l'ajout de l'événement : " . $e->getMessage();
    }
}
?>

<div class="detailcontainer">
    <div class="modify-event-card">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
            Ajouter un nouvel événement :
            <input class="form-label" name="eventName" type="text" placeholder="Nom du concert"><br>
            
            Nom de l'artiste :
            <input class="form-label" name="artist" type="text" placeholder="Nom de l'artiste"><br>

            Catégorie :
            <input class="form-label" name="category" type="text" placeholder="Catégorie"><br>

            Date :
            <input class="form-label" name="date" type="date"><br>

            Heure :
            <input class="form-label" name="time" type="time"><br>
            
            Prix :
            <input class="form-label" name="price" type="text" placeholder="Prix"><br>

            <label for="upload">Envoyer une image</label><br>
            <input type="file" name="picture" id="upload"><br>

            <input type="submit" value="Ajouter">
        </form>
    </div>
</div>
