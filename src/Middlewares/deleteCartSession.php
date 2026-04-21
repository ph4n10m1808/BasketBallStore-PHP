<?php

require_once __DIR__ . '/../Controllers/CartController.php';
$cartObj = new CartController();

$idProduct = $_POST['id'] ?? '';

if ($idProduct) {
    $cartObj->deleteItemSession($idProduct);
    echo true;
} else {
    echo false;
}
