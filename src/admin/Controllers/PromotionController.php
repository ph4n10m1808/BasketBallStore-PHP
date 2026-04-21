<?php

require_once "./Models/promotion.php";
class PromotionController
{
    public promotion $promotionModel;

    public function __construct()
    {
        $this->promotionModel = new promotion();
    }

    public function handleGetAll(): void
    {
        $act = $_GET['act'] ?? "";

        if ($act === "edit" && isset($_GET['id'])) {
            $id = $_GET['id'];
            $promotion = $this->promotionModel->view($id);
        } elseif ($act === "") {
            $promotionList = $this->promotionModel->getAll();
        }

        require_once "view/index.php";
    }

    public function handleAdd(): void
    {
        $namePromotion = $_POST["name_promotion"] ?? "";
        $typePromotion = $_POST["type_promotion"] ?? "";
        $value = $_POST["value"] ?? "";
        $this->promotionModel->add($namePromotion, $typePromotion, $value);
    }

    public function viewDetail(): void
    {
        $id = $_GET['id'];
        $promotion = $this->promotionModel->view($id);
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET['id'];
        $this->promotionModel->delete($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET['id'];
        $namePromotion = $_POST['name_promotion'];
        $typePromotion = $_POST['type_promotion'];
        $typeSale = $_POST['type_sale'];
        $value = $_POST['value'];
        $status = $_POST['status'];
        $this->promotionModel->update($id, $namePromotion, $typePromotion, $typeSale, $value, $status);
    }
}
