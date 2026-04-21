<?php

require_once "./Models/product.php";
require_once __DIR__ . "/ImageUploadTrait.php";

class ProductController
{
    use ImageUploadTrait;

    public product $productModel;

    public function __construct()
    {
        $this->productModel = new product();
    }

    public function getAll(): void
    {
        $act = $_GET['act'] ?? "";

        if ($act === "add" || $act === "edit") {
            $categoryList = $this->productModel->getCategory();
            $productTypeList = $this->productModel->getProductType();
            $promotionList = $this->productModel->getPromotion();
        }

        if ($act === "edit" && isset($_GET['id'])) {
            $id = $_GET['id'];
            $detailProduct = $this->productModel->view($id);
        } elseif ($act === "") {
            $productList = $this->productModel->getAllProduct();
        }

        require_once "view/index.php";
    }

    public function handleAdd(): void
    {
        $mainImage = $this->formatImage("main_image");
        $image1 = $this->formatImage("image1");
        $image2 = $this->formatImage("image2");
        $image3 = $this->formatImage("image3");
        $image4 = $this->formatImage("image4");
        $size = $_POST['size'] ?? "";

        $titleProduct = $_POST["title_product"] ?? "";
        $nameProduct = $_POST["name_product"] ?? "";
        $price = $_POST["price"] ?? "";
        $quantity = $_POST["quantity"] ?? "";
        $idCategory = $_POST["id_category"] ?? "";
        $idProductType = $_POST["id_product_type"] ?? "";
        $idPromotion = $_POST["id_promotion"] ?? "";
        $description = $_POST["description"] ?? "";

        $this->productModel->addNewProduct($titleProduct, $nameProduct, $price, $quantity, $idCategory, $idProductType, $mainImage, $image1, $image2, $image3, $image4, $size, $idPromotion, $description);
    }

    public function handleViewDetail(): void
    {
        $id = $_GET['id'];
        $detailProduct = $this->productModel->view($id);
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET["id"];

        // Fetch product details to get image URLs
        $product = $this->productModel->view($id);
        if ($product) {
            $images = [
                $product['main_image'],
                $product['image1'],
                $product['image2'],
                $product['image3'],
                $product['image4']
            ];

            foreach ($images as $img) {
                $this->deleteImage($img);
            }
        }

        $this->productModel->deleteProduct($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET["id"];

        // Fetch current product to get old images
        $oldProduct = $this->productModel->view($id);

        // Update images using the helper method
        $mainImage = $this->updateImage("main_image", $oldProduct ? $oldProduct['main_image'] : null);
        $image1 = $this->updateImage("image1", $oldProduct ? $oldProduct['image1'] : null);
        $image2 = $this->updateImage("image2", $oldProduct ? $oldProduct['image2'] : null);
        $image3 = $this->updateImage("image3", $oldProduct ? $oldProduct['image3'] : null);
        $image4 = $this->updateImage("image4", $oldProduct ? $oldProduct['image4'] : null);

        $size = $_POST['size'] ?? "";
        $titleProduct = $_POST["title_product"] ?? "";
        $nameProduct = $_POST["name_product"] ?? "";
        $price = $_POST["price"] ?? "";
        $quantity = $_POST["quantity"] ?? "";
        $idCategory = $_POST["id_category"] ?? "";
        $idProductType = $_POST["id_product_type"] ?? "";
        $idPromotion = $_POST["id_promotion"] ?? "";
        $description = $_POST["description"] ?? "";

        $this->productModel->update($id, $mainImage, $image1, $image2, $image3, $image4, $size, $titleProduct, $nameProduct, $price, $quantity, $idCategory, $idProductType, $idPromotion, $description);
    }

    // [VULN] Command Injection: tên file không được sanitize, cho phép chèn lệnh OS
    public function exportProducts(): void
    {
        $format = $_GET['format'] ?? 'csv';
        $filename = $_GET['filename'] ?? 'products';
        $dbUser = getenv('MYSQL_USER');
        $dbPass = getenv('MYSQL_PASSWORD');
        $dbName = getenv('MYSQL_DATABASE');
        exec("mysqldump -u {$dbUser} -p{$dbPass} {$dbName} product > /tmp/" . $filename . "." . $format);
        header("location: ?mod=product");
    }

    // [VULN] SSRF: fetch URL bất kỳ do user nhập, bao gồm cả internal services
    public function importImageFromUrl(): void
    {
        $url = $_POST['image_url'];
        $imageContent = file_get_contents($url);
        // Lấy tên file sạch từ URL (bỏ query params) để dễ exploit hơn
        $urlPath = parse_url($url, PHP_URL_PATH);
        $filename = basename($urlPath) ?: 'imported_image';
        
        $publicPath = dirname(__DIR__, 2) . "/public";
        file_put_contents($publicPath . "/imgs/product/" . $filename, $imageContent);
        header("location: ?mod=product");
    }

    // [VULN] XXE: parse XML không disable external entities
    public function importXml(): void
    {
        $xmlContent = file_get_contents($_FILES['xml_file']['tmp_name']);
        // Trong PHP 8.0+, entity loader bị disable mặc định, cần bật flag để vuln hoạt động
        $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOENT | LIBXML_DTDLOAD);
        if ($xml) {
            foreach ($xml->product as $product) {
                $name = (string) $product->name;
                $title = (string) $product->title;
                $price = (string) $product->price;
                $quantity = (string) $product->quantity;
                $idc = (string) $product->id_category;
                $idpt = (string) $product->id_product_type;
                $idp = (string) $product->id_promotion;
                $des = (string) $product->description;
                $this->productModel->addNewProduct($title, $name, $price, $quantity, $idc, $idpt, '', '', '', '', '', '', $idp, $des);
            }
        }
        header("location: ?mod=product");
    }
}
