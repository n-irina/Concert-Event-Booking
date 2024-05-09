<?php
session_start();
// recuperer la clé du panier (idevent)
$idevent=$_GET['id'];
// Recuperer le panier
$cart=$_SESSION['cart'];

// je baisse la quantité uniquement si la valeur est >0
if ($cart[$idevent] == 1   ){
    // suprimer le produit
    header("location:supprimer_panier.php?id=$idevent");
}
else {

    // ajouter un a la valeur de la clé du panier

    $cart[$idevent]=$cart[$idevent]-1;
    // sauvegarde dans la session
    $_SESSION['cart']=$cart;

    header('location:cart.php');
}