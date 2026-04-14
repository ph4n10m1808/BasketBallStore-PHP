<?php
require_once "./Models/product.php";
require_once __DIR__ . "/ImageUploadTrait.php";

class ProductController{
    use ImageUploadTrait;
    
    public product $productModel;

    public function __construct(){
        $this->productModel = new product();
    }

    public function getAll(): void
    {
        $productList = $this->productModel->getAllProduct();
        $categoryList = $this->productModel->getCategory();
        $productTypeList = $this->productModel->getProductType();
        $promotionList = $this->productModel->getPromotion();
        if(isset($_GET['id']) && $_GET['act'] === "edit"){
            $id = $_GET['id'];
            $detailStuff = $this->productModel->view($id);
        }
        require_once "view/index.php";
    }

    public function handleAdd(): void
    {

        $mi = $this->formatImage("main_image");
        $i1 = $this->formatImage("image1");
        $i2 = $this->formatImage("image2");
        $i3 = $this->formatImage("image3");
        $i4 = $this->formatImage("image4");
        $s = $_POST['size'];

        $tp = $_POST["title_product"];
        $np = $_POST["name_product"];
        $p = $_POST["price"];
        $q = $_POST["quantity"];
        $idc = $_POST["id_category"];
        $idPt = $_POST["id_product_type"];
        $idp = $_POST["id_promotion"];
        $des = $_POST["description"] ?? "";

        $this->productModel->addNewProduct($tp, $np, $p, $q, $idc, $idPt, $mi, $i1, $i2, $i3, $i4, $s, $idp, $des);
    }

    public function handleViewDetail():void
    {
        $id = $_GET['id'];
        $detailProduct = $this->productModel->view($id);
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET["id"];
        $this->productModel->deleteProduct($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET["id"];
        $mi = $_FILES['main_image']['name'] ? $this->formatImage("main_image") : $_POST['old_main_image'];

        $i1 = $_FILES['image1']['name'] ? $this->formatImage("image1") : $_POST['old_image1'];
        $i2 = $_FILES['image2']['name'] ? $this->formatImage("image2") : $_POST['old_image2'];
        $i3 = $_FILES['image3']['name'] ? $this->formatImage("image3") : $_POST['old_image3'];
        $i4 = $_FILES['image4']['name'] ? $this->formatImage("image4") : $_POST['old_image4'];
        $s = $_POST['size'];
        $tp = $_POST["title_product"];
        $np = $_POST["name_product"];
        $p = $_POST["price"];
        $q = $_POST["quantity"];
        $idc = $_POST["id_category"];
        $idPt = $_POST["id_product_type"];
        $idp = $_POST["id_promotion"];
        $des = $_POST["description"] ?? "";
        $this->productModel->update($id, $mi, $i1, $i2, $i3,$i4, $s,$tp,$np,$p,$q,$idc,$idPt,$idp,$des);
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
        $filename = basename($url);
        file_put_contents("../public/imgs/product/" . $filename, $imageContent);
        header("location: ?mod=product");
    }

    // [VULN] XXE: parse XML không disable external entities
    public function importXml(): void
    {
        $xmlContent = file_get_contents($_FILES['xml_file']['tmp_name']);
        $xml = simplexml_load_string($xmlContent);
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
        header("location: ?mod=product");
    }
}