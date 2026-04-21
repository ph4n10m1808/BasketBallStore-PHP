<?php

$cartItems = $_SESSION['carts'] ?? [];
if ($cartItems) { ?>
    <?php require_once "product.php" ?>
<?php } else { ?>
    <?php require_once "empty.php" ?>
<?php } ?>