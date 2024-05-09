
<?php
include "header.php";

$email = $_POST['email'];
$password = $_POST['password'];
$pdo = new \PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');

$checkStatement = $pdo->prepare("SELECT * FROM user WHERE email = :email");
$checkStatement->bindValue(':email', $email, PDO::PARAM_STR);
$checkStatement->execute();
$user = $checkStatement->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if ($user['isAdmin'] == 1) {
        $_SESSION["admin"] = $email;
        header("location:agenda.php");
        exit;
    } elseif ($user['isAdmin'] == 0) {
        $_SESSION["user"] = $email;
        header("location:agenda.php");
        exit;
    }
} else {
    $statement = $pdo->prepare("INSERT INTO user (email, password) VALUES (:email, :password)");
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':password', $password, PDO::PARAM_STR);

    if ($statement->execute()) {
        $_SESSION["user"] = $email;
        header("location:agenda.php");
        exit;
    }
}
?>

<?php
include "footer.php";
?>
