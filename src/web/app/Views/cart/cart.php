<?php

$cartItems = $_SESSION['carts'] ?? [];
if ($cartItems) { ?>
    <?php require_once __DIR__ . "/product.php" ?>
<?php } else { ?>
    <?php require_once __DIR__ . "/empty.php" ?>
<?php } ?>