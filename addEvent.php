<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js">
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet" />


<?php
include "layout.php";

$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$request1 = 'SELECT idartist, name FROM artist'; // Fetch both ID and name
$statement1 = $pdo->query($request1);
$artists =  $statement1->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Démarrer une transaction pour assurer l'intégrité des données
    $pdo->beginTransaction();

    try {
        $eventName = filter_input(INPUT_POST, 'eventName');
        $selectedArtists = $_POST['selectedArtists']; // Get selected artist IDs
        $artistName = filter_input(INPUT_POST, 'artist');
        $category = filter_input(INPUT_POST, 'category');
        $date = filter_input(INPUT_POST, 'date');
        $time = filter_input(INPUT_POST, 'time');
        $price = filter_input(INPUT_POST, 'price');
        $selectedDates = $_POST['selectedDates'];
        // $newdate = str_replace("/","-",$selectedDates);
        $dates = explode(",", $selectedDates);
        $newdates=[];
        foreach($dates as $date){
            $newdates[] = date_create($date);
        }
       
        $formatteddates=[];
        foreach ($newdates as $newdate) {
            $formatteddates []= date_format($newdate,'Y-m-d');
        }
        
        // Insérer l'événement dans la table event
        $sqlEvent = "INSERT INTO event (eventName, category, price) VALUES (:eventName, :category, :price)";
        $stmtEvent = $pdo->prepare($sqlEvent);
        $stmtEvent->bindParam(':eventName', $eventName);
        $stmtEvent->bindParam(':category', $category);
        $stmtEvent->bindParam(':price', $price);
        $stmtEvent->execute();
        $eventId = $pdo->lastInsertId();

        foreach ($formatteddates as $formatteddate) {
            // Insérer la date dans la table date
            $sqlDate = "INSERT INTO date (idevent, date, time) VALUES (:idevent, :date, :time)";
            $stmtDate = $pdo->prepare($sqlDate);
            $stmtDate->bindParam(':idevent', $eventId);
            $stmtDate->bindParam(':date', $formatteddate);
            $stmtDate->bindParam(':time', $time);
            $stmtDate->execute();
        }


        // If artist name is provided, insert it
        if ($artistName) {
            $sqlArtist = "INSERT INTO artist (name) VALUES (:artistName)";
            $stmtArtist = $pdo->prepare($sqlArtist);
            $stmtArtist->bindParam(':artistName', $artistName);
            $stmtArtist->execute();
            $artistId = $pdo->lastInsertId();
            $selectedArtists[] = $artistId; // Add the new artist ID to the selected artists array
        }

        // Insert the association between the event and the selected artists
        foreach ($selectedArtists as $selectedArtist) {
            $sqlEventArtist = "INSERT INTO event_has_artist (idevent, idartist) VALUES (:idevent, :idartist)";
            $stmtEventArtist = $pdo->prepare($sqlEventArtist);
            $stmtEventArtist->bindParam(':idevent', $eventId);
            $stmtEventArtist->bindParam(':idartist', $selectedArtist);
            $stmtEventArtist->execute();
        }

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
            <h2>Ajouter un nouvel événement :</h2>
            Nom du concert:
            <input class="form-label" name="eventName" type="text" placeholder="Nom du concert"><br>

            Nom de l'artiste:

            <div class="container">
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="multiSelectDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Sélectionnez votre artiste
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="multiSelectDropdown">
                        <?php foreach ($artists as $artist) { ?>
                            <li>
                                <label>
                                    <input type="checkbox" name="selectedArtists[]" value="<?= $artist['idartist'] ?>">
                                    <?= $artist['name'] ?>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            Ou ajoutez le:
            <input class="form-label" name="artist" type="text" placeholder="Artiste"><br>
            Catégorie:
            <input class="form-label" name="category" type="text" placeholder="Catégorie"><br>

            Sélectionnez les dates:
            <input type="text" class="form-control date" name="selectedDates" /><br>
            <script>
                $('.date').datepicker({
                    multidate: true,
                });
            

                $('.date').datepicker({
                    multidate: true,
                    closeOnDateSelect: true, // Close the date picker after selecting a date
                    step: 5, // Maximum number of dates to be selected
                });
            </script>

            Heure:
            <input class="form-label" name="time" type="time"><br>

            Prix:
            <input class="form-label" name="price" type="text" placeholder="Prix"><br>

            <label for="upload">Envoyer une image</label><br>
            <input type="file" name="picture" id="upload"><br>

            <input type="submit" value="Ajouter">
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<?php
var_dump($selectedDates);
?>