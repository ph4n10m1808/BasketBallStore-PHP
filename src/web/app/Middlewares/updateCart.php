<?php

require_once __DIR__ . '/../Controllers/CartController.php';
$cartObj = new CartController();

$size = $_POST['size'] ?? "";
$idProduct = $_POST['id'] ?? "";
$typeUpdate = $_POST['type'] ?? "";

echo $cartObj->updateCart($idProduct, $typeUpdate, $size);
