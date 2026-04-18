<?php

require_once "Models/eachProductType.php";

class EachProductTypeController
{
    private EachProductType $eachProductType;
    public function __construct()
    {
        $this->eachProductType = new EachProductType();
    }

    public function getProductType()
    {
        $idProductType = $_GET['id'];
        $data = $this->eachProductType->getProductType($idProductType);
        $productTypeName = $this->eachProductType->getNameProductType($idProductType);
        if ($data) {
            $category = $this->eachProductType->getCategoryName($data[0]['id_category']);
        }
        require_once "Views/index.php";
    }

    public function getCategory()
    {
        $idCategory = $_GET['type'];
        $data = $this->eachProductType->getCategory($idCategory);
        $categoryName = $this->eachProductType->getNameCategory($idCategory);
        require_once "Views/index.php";
    }

    public function searchProduct()
    {
        $keyword = $_POST['keyword'];
        $dataSearch = $this->eachProductType->searchProduct($keyword);
        require_once "Views/index.php";
    }
}
