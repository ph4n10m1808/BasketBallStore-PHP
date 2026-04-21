<?php

require_once __DIR__ . "/../Models/detailProduct.php";
class ProductDetailController
{
    public DetailProduct $detailProductModel;
    public function __construct()
    {
        $this->detailProductModel = new DetailProduct();
    }

    public function viewDetail(): void
    {
        $id = $_GET['id'] ?? '';
        $productDetail = $this->detailProductModel->getData($id);

        // BUG-6 fix: Kiểm tra sản phẩm tồn tại
        if (!$productDetail) {
            require_once("Views/error/error.php");
            return;
        }

        $relatedProducts = $this->detailProductModel->getRelated($productDetail['id_category']);
        $productType = $this->detailProductModel->getProductType($productDetail['id_product_type'])['name_pt'] ?? '';
        $category = $this->detailProductModel->getCategory($productDetail['id_category']);
        require_once("Views/index.php");
    }
}
