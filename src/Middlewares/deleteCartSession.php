<?php

require_once __DIR__ . '/../Controllers/CartController.php';
$cartObj = new CartController();

$idProduct = $_POST['id'] ?? '';
$size = $_POST['size'] ?? null;

if ($idProduct) {
    $cartObj->deleteItemSession($idProduct, $size ?: null);
    echo true;
} else {
    echo false;
}
