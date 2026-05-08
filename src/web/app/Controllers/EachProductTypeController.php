<?php

require_once __DIR__ . "/../Models/eachProductType.php";

class EachProductTypeController
{
    private EachProductType $eachProductType;
    public function __construct()
    {
        $this->eachProductType = new EachProductType();
    }

    public function getProductType()
    {
        $idProductType = $_GET['id'] ?? '';
        $productList = $this->eachProductType->getProductType($idProductType);
        $productTypeName = $this->eachProductType->getNameProductType($idProductType);
        // BUG-8 fix: Khởi tạo $category để tránh undefined variable trong view
        $category = null;
        if ($productList) {
            $category = $this->eachProductType->getNameCategory($productList[0]['id_category']);
        }
        require_once BASE_PATH . "/app/Views/index.php";
    }

    public function getCategory()
    {
        $idCategory = $_GET['type'] ?? '';
        $productList = $this->eachProductType->getCategory($idCategory);
        $categoryName = $this->eachProductType->getNameCategory($idCategory);
        require_once BASE_PATH . "/app/Views/index.php";
    }

    public function searchProduct()
    {
        $keyword = $_POST['keyword'] ?? '';
        $searchResults = $this->eachProductType->searchProduct($keyword);
        require_once BASE_PATH . "/app/Views/index.php";
    }
}
