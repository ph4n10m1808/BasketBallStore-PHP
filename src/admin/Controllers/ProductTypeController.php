<?php

require_once "./Models/productType.php";
require_once __DIR__ . "/ImageUploadTrait.php";

class ProductTypeController
{
    use ImageUploadTrait;
    public productType $productTypeModel;

    public function __construct()
    {
        $this->productTypeModel = new productType();
    }

    public function handleGetAll(): void
    {
        $productTypeList = $this->productTypeModel->getAll();
        $categoryList = $this->productTypeModel->getCategory();
        if (isset($_GET['id']) && $_GET['act'] === "edit") {
            $id = $_GET['id'];
            $detailStuff = $this->productTypeModel->view($id);
        }
        require_once "view/index.php";
    }

    public function handleAdd(): void
    {
        $logoImg = $this->formatImage("logo_pt", "Logo");
        $nameProductType = $_POST["name_pt"] ?? '';
        $description = $_POST["description"] ?? '';
        $idCategory = $_POST["id_category"] ?? '';

        $this->productTypeModel->add($nameProductType, $logoImg, $description, $idCategory);
    }

    public function viewDetail(): void
    {
        $id = $_GET['id'];
        $detailStuff = $this->productTypeModel->view($id);
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET['id'];

        // Fetch product type details to get the logo URL
        $productType = $this->productTypeModel->view($id);
        if ($productType && !empty($productType['logo_pt'])) {
            $this->deleteImage($productType['logo_pt']);
        }

        $this->productTypeModel->delete($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET['id'];

        // Fetch current product type to get the old logo
        $oldProductType = $this->productTypeModel->view($id);
        $oldLogo = $oldProductType ? $oldProductType['logo_pt'] : null;

        $nameProductType = $_POST['name_pt'] ?? '';
        $logo = $this->updateImage("logo_pt", $oldLogo, "Logo");
        $description = $_POST['description'] ?? '';
        $idCategory = $_POST['id_category'] ?? '';

        $this->productTypeModel->update($id, $nameProductType, $logo, $description, $idCategory);
    }

}
