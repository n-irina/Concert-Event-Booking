<?php
include "layout.php";

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT event.*, date.date, date.time, artist.name AS artist_name 
        FROM event
        LEFT JOIN date ON event.idevent = date.idevent 
        LEFT JOIN event_has_artist ON event.idevent = event_has_artist.idevent 
        LEFT JOIN artist ON event_has_artist.idartist = artist.idartist 
        WHERE event.idevent = :id";


$statement = $pdo->prepare($sql);
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$event = $statement->fetch(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("SELECT * FROM artist");
$statement->execute();
$artist = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("SELECT * FROM date");
$statement->execute();
$date = $statement->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Démarrer une transaction pour assurer l'intégrité des données
    $pdo->beginTransaction();

    try {
        $updates = [];
        $params = ['id' => $id];

        $fields = ['eventName', 'category', 'price'];
        foreach ($fields as $field) {
            if (!empty($_POST[$field]) && $_POST[$field] != $event[$field]) {
                $updates[] = "$field = :$field";
                $params[$field] = $_POST[$field];
            }
        }

        if (!empty($_FILES['picture']['name'])) {
            $newFilename = uniqid() . '_' . basename($_FILES['picture']['name']);
            $dossierTempo = $_FILES['picture']['tmp_name'];
            $dossierSite = 'uploads/' . $newFilename;
            if (move_uploaded_file($dossierTempo, $dossierSite)) {
                $updates[] = "picture = :picture";
                $params['picture'] = $dossierSite;
            }
        }

        // Mise à jour de la table event
        if (!empty($updates)) {
            $sql = "UPDATE event SET " . implode(', ', $updates) . " WHERE idevent = :id";
            $statement = $pdo->prepare($sql);
            foreach ($params as $key => &$val) {
                $statement->bindParam($key, $val);
            }
            $statement->execute();
        }

        // Mise à jour de la date dans la table date
        if (!empty($_POST['date']) && $_POST['date'] != $event['date']) {
            $sqlDate = "UPDATE date SET date = :date WHERE idevent = :id";
            $stmtDate = $pdo->prepare($sqlDate);
            $stmtDate->bindParam(':date', $_POST['date']);
            $stmtDate->bindParam(':id', $id);
            $stmtDate->execute();
        }
        if (!empty($_POST['time']) && $_POST['time'] != $event['time']) {
            $sqlDate = "UPDATE date SET time = :time WHERE idevent = :id";
            $stmtDate = $pdo->prepare($sqlDate);
            $stmtDate->bindParam(':time', $_POST['time']);
            $stmtDate->bindParam(':id', $id);
            $stmtDate->execute();
        }

        // Mise à jour du nom de l'artiste dans la table artist via la table intermédiaire event_has_artist
        if (!empty($_POST['artist']) && $_POST['artist'] != $event['artist_name']) {
            $sqlArtist = "UPDATE artist SET name = :artistName WHERE idartist IN (SELECT idartist FROM event_has_artist WHERE idevent = :id)";
            $stmtArtist = $pdo->prepare($sqlArtist);
            $stmtArtist->bindParam(':artistName', $_POST['artist']);
            $stmtArtist->bindParam(':id', $id);
            $stmtArtist->execute();
        }

        // Si tout est ok, commit les changements
        $pdo->commit();
        header("Location: agenda.php");
        exit;
    } catch (Exception $e) {
        // En cas d'erreur, annuler toutes les opérations
        $pdo->rollBack();
        echo "Erreur lors de la mise à jour de l'événement : " . $e->getMessage();
    }
}
?>

<div class="detailcontainer">
    <div class="modify-event-card">
        <form action="<?= $_SERVER['PHP_SELF'] ?>?id=<?= htmlspecialchars($id) ?>" method="POST" enctype="multipart/form-data">
            Modifier le nom du concert:
            <input class="form-label" name="eventName" type="text" value="<?= htmlspecialchars($event['eventName']) ?>"><br>
            
            Modifier le nom de l'artiste:
            <input class="form-label" name="artist" type="text" value="<?= htmlspecialchars($event['artist_name']) ?>"><br>

            Modifier la catégorie:
            <input class="form-label" name="category" type="text" value="<?= htmlspecialchars($event['category']) ?>"><br>

            Modifier la date:
            <input class="form-label" name="date" type="date" value="<?= htmlspecialchars($event['date']) ?>"><br>

            Modifier l'heure:
            <input class="form-label" name="time" type="time" value="<?= htmlspecialchars(date('H:i', strtotime($event['time']))) ?>"><br>
            

            Modifier le prix:
            <input class="form-label" name="price" type="text" value ="<?= htmlspecialchars(str_replace(' €', '', $event['price'])) ?>"><br>

            <label for="upload">Envoyer une image</label><br>
            <img src="<?= $event['picture'] ?>" style="max-width: 200px;">
            <input type="file" name="picture" id="upload"><br>

            <input type="submit" value="Modifier">
        </form>
    </div>
</div>