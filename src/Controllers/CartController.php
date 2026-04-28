<?php

require_once __DIR__ . "/../Models/cart.php";
class CartController
{
    public Cart $cartModel;
    public function __construct()
    {
        $this->cartModel = new Cart();
    }

    public function getCart()
    {
        require_once "Views/index.php";
    }

    public function addCart()
    {
        $this->cartModel->addCart();
    }

    public function clearCart()
    {
        $this->cartModel->clearCart();
    }

    public function checkAdd($idProduct, $quantity, $size, $restQuantity)
    {
        $this->cartModel->addCartNotLogin($idProduct, $quantity, $size, $restQuantity);
    }

    public function deleteItemSession($id, $size = null)
    {
        $this->cartModel->deleteItemSession($id, $size);
    }

    public function updateCart($id, $type, $size)
    {
        return $this->cartModel->updateCart($id, $type, $size);
    }
}
