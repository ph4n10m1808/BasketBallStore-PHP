<?php
require_once "./Models/productType.php";
require_once __DIR__ . "/ImageUploadTrait.php";

class ProductTypeController{
    use ImageUploadTrait;
    public productType $productTypeModel;

    public function __construct(){
        $this->productTypeModel = new productType();
    }

    public function handleGetAll(): void
    {
        $productTypeList = $this->productTypeModel->getAll();
        $categoryList = $this->productTypeModel->getCategory();
        if(isset($_GET['id']) && $_GET['act'] === "edit"){
            $id = $_GET['id'];
            $detailStuff = $this->productTypeModel->view($id);
        }
        require_once "view/index.php";
    }

    public function handleAdd(): void
    {
        $LogoImg = $this->formatImage("logo_pt", "Logo");
        $npt = $_POST["name_pt"];
        $des = $_POST["description"];
        $idc = $_POST["id_category"];

        $this->productTypeModel->add($npt, $LogoImg, $des, $idc);
    }

    public function viewDetail():void
    {
        $id = $_GET['id'];
        $detailStuff = $this->productTypeModel->view($id);
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET['id'];
        $this->productTypeModel->delete($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET['id'];
        $namePT = $_POST['name_pt'];
        $logo = $this->formatImage("logo_pt", "Logo");
        $des = $_POST['description'];
        $idC = $_POST['id_category'];
        $this->productTypeModel->update($id, $namePT, $logo, $des, $idC);
    }

}