<?php
session_start();

$idevent = $_GET['id'];
echo "test";

if (isset($_SESSION['cart'])) {
    if (in_array($idevent, $_SESSION['cart'])) {
        echo "test";
        $cart = $_SESSION['cart'];
        $cart[$idevent] = $cart[$idevent] + 1;
        $_SESSION['cart'] = $cart;
    } else {
        $_SESSION['cart'][$idevent] = 1;
    }
} else {
    echo "test";
    $_SESSION['cart'][$idevent] = 1;
}

echo "<pre>";
var_dump($_SESSION['cart']);
echo "</pre>";
?>
