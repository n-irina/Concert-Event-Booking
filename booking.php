<?php
include "layout.php";

$iddate = $_GET['id'];
$iduser = $_SESSION['iduser'];
$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');

$statement = $pdo->prepare("SELECT *, DATE_FORMAT(date, '%M %d %Y') AS date_formatted, time FROM event 
                            JOIN date ON event.idevent = date.idevent
                            WHERE date.iddate = :id");
$statement->bindParam(':id', $iddate, PDO::PARAM_INT);
$statement->execute();
$event = $statement->fetch(PDO::FETCH_ASSOC);

$price_json = json_encode($event['price']);
?>

<h1 class="justify-content-center align-items-center text-align-center m-5">Votre réservation pour: <?= $event['eventName'] ?></h1>
<h2 class="justify-text-center text-align-center m-5">Le <?= $event['date_formatted'] . ' à ' . $event['time'] ?></h2>
<section class='infoResa'>
    <div class='container bg-light border border-light-subtle'>
        <div class="row my-custom-row m-10">
            <div class="col-4 border border-light-subtle align-self-center p-3">
                <p><strong>Tarif</strong></p>
            </div>
            <div class="col-4 border border-light-subtle align-self-center p-3">
                <p><strong>Montant</strong></p>
            </div>
            <div class="col-4 border border-light-subtle align-self-center p-3">
                <p><strong>Nb Places</strong></p>
            </div>
        </div>
        <div class="row my-custom-row">
            <div class="col-4 border border-light-subtle align-self-center p-3">
                <p>PLACEMENT DEBOUT</p>
            </div>
            <div class="col-4 border border-light-subtle align-self-center p-3">
                <p><?= $event['price'] ?> €</p>
            </div>
            <div class="col-4 border border-light-subtle align-self-center p-3">
                <form class="form" method="POST" action="">
                    <select id="select" class="form-select" aria-label="Default select example" name="nbPlaces">
                        <option selected>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>




            </div>
        </div>
        <div class="row my-custom-row justify-content-right">
            <div class="col-4 border border-light-subtle align-self-center p-3">
                <p><strong>TOTAL</strong></p>
            </div>
            <div class="col-4 border border-light-subtle align-self-center p-3" id="priceOfPlaces">0 €</div>
            <div class="col-4 border border-light-subtle align-self-center p-3" id="selectedPlaces">0 place(s)</div>
        </div>
    </div>
    <div class="button">
        <button type="submit" class="btn btn-lg btn-primary" name="addCart"><a href='agenda.php'>Autre commande</a></button>
        <button type="submit" class="btn btn-lg btn-success" name="submit">Commander</button>
        <button type="button" class="btn btn-lg btn-danger"><a href='detailEvent.php?id=<?=$event['idevent']?>'>Annuler</a></button>
    </div>
    </form>

    <script>
        let price = <?php echo $price_json; ?>;
        // Get the select element
        const select = document.getElementById('select');
        // Get the element where the selected places will be displayed
        const selectedPlaces = document.getElementById('selectedPlaces');
        // Get the element where the price of places will be displayed
        const priceOfPlaces = document.getElementById('priceOfPlaces');

        // Add event listener to the select element
        select.addEventListener('change', function() {
            // Get the selected value
            const selectValue = select.options[select.selectedIndex].value;
            // Update the text content of selectedPlaces
            selectedPlaces.textContent = selectValue + " place(s)";
            // Update the total price based on the number of places selected
            let realprice = selectValue * price;
            priceOfPlaces.textContent = realprice + " €";
        });
    </script>

</section>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if nbPlaces is set
    if(isset($_POST['nbPlaces'])) {
        $nbplace = $_POST['nbPlaces'];
        $status = 'disponible';
        $idevent = $event['idevent'];

        if (isset($_POST['submit'])) {
            // Insert booking into database
            $request2 = 'INSERT INTO booking (quantity, status, iduser, idevent) VALUES (:quantity, :status, :iduser, :idevent)';
            $statement2 = $pdo->prepare($request2);
            $statement2->bindParam(':quantity', $nbplace, \PDO::PARAM_INT);
            $statement2->bindParam(':status', $status, \PDO::PARAM_STR);
            $statement2->bindParam(':iduser', $iduser, \PDO::PARAM_INT);
            $statement2->bindParam(':idevent', $idevent, \PDO::PARAM_INT);
            $statement2->execute();

            $idbooking = $pdo->lastInsertId();
            // Redirect to cart.php with booking ID
            header("location:cart.php?id=".$idbooking);
            exit; // Add this to stop further execution
        } elseif (isset($_POST['addCart'])) {
            // Insert booking into database
            $request2 = 'INSERT INTO booking (quantity, status, iduser, idevent) VALUES (:quantity, :status, :iduser, :idevent)';
            $statement2 = $pdo->prepare($request2);
            $statement2->bindParam(':quantity', $nbplace, \PDO::PARAM_INT);
            $statement2->bindParam(':status', $status, \PDO::PARAM_STR);
            $statement2->bindParam(':iduser', $iduser, \PDO::PARAM_INT);
            $statement2->bindParam(':idevent', $idevent, \PDO::PARAM_INT);
            $statement2->execute();
        }
    }
}
else {
    
    echo "Veuillez renseigner le nombre de places s'il vous plaît";
  }
?>