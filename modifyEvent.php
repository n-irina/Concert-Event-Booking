<?php
include "layout.php";

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');
$statement = $pdo->prepare("SELECT * FROM event WHERE idevent = :id");
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$event = $statement->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updates = [];
    $params = ['id' => $id];

    $fields = ['eventName', 'artist', 'category', 'date', 'price'];
    foreach ($fields as $field) {
        if (!empty($_POST[$field]) && $_POST[$field] != $event[$field]) {
            $updates[] = "$field = :$field";
            $params[$field] = $_POST[$field];
        }
    }

    if (!empty($updates)) {
        $sql = "UPDATE event SET " . implode(', ', $updates) . " WHERE idevent = :id";
        $statement = $pdo->prepare($sql);
        foreach ($params as $key => &$val) {
            $statement->bindParam($key, $val);
        }
        if ($statement->execute()) {
            header("Location: agenda.php");
            exit;
        } else {
            echo "Erreur lors de la mise à jour de l'événement.";
        }
    }
}
?>
<div class="detailcontainer">
    <div class="modify-event-card">
        <form action="modifyEvent.php?id=<?= htmlspecialchars($id) ?>" method="POST" enctype="multipart/form-data">
            Modifier le nom du concert:
            <input class="form-label" name="eventName" type="text" value="<?= htmlspecialchars($event['eventName']) ?>"><br>
            
            Modifier le nom de l'artiste:
            <input class="form-label" name="artist" type="text" value="<?= htmlspecialchars($event['artist']) ?>"><br>

            Modifier la categorie:
            <input class="form-label" name="category" type="text" value="<?= htmlspecialchars($event['category']) ?>"><br>

            Modifier la date:
            <input class="form-label" name="date" type="date" value="<?= htmlspecialchars($event['date']) ?>"><br>

            Modifier le prix:
            <input class="form-label" name="price" type="text" value="<?= htmlspecialchars(str_replace(' €', '', $event['price'])) ?>"><br>

            <input type="submit" value="Modifier">
        </form>
    </div>
</div>