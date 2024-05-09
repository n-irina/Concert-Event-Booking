<?php
session_start();
// recuperer l'identifiant du livre
$idevent=$_GET['id'];
echo "test";

// stocker dans la session
if (isset($_SESSION['cart']))  {
    
    if (in_array( $idevent ,$_SESSION['cart']  )) {
        echo "test";
        //echo "le produit est déjà dans le panier";
        // recupere le panier existant dans une variable 
        // cart
        // on va aller recuperer la clé correspondant 
        // a cette valeur
        $cart=$_SESSION['cart'];
        // 2 on recuperer la clé correspondant au produit 
        // sur lequel on veut recherche une quantité
        
        $cart[$idevent]=$cart[$idevent]+1;
        // sauvegarde en session
        $_SESSION['cart']=$cart;
    }

    else {
        $_SESSION['cart'][$idevent]=1;
            
    }
}
else {
    echo "test";
    $_SESSION['cart'][$idevent]=1;

}
echo "<pre>";
var_dump($_SESSION['cart']);
echo "</pre>";

header("Location: cart.php");