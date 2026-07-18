<?php

session_start();

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/app/Support/RequestTelemetry.php';
RequestTelemetry::start($_SERVER, $_GET, $_POST);

$auth = $_SESSION['auth'] ?? 0;
// [VULN] Type Juggling: dùng == thay vì === — có thể bypass bằng session manipulation
if (isset($_SESSION["auth"]) && $_SESSION["auth"] ==  true) {
    $mod = $_GET['mod'] ?? "dashboard";
    switch ($mod) {
        case "dashboard":
            require_once BASE_PATH . "/admin/Controllers/DashboardController.php";
            $dashboardM = new DashboardController();
            $dashboardM->getData();
            break;
        case "account":
            require_once BASE_PATH . "/admin/Controllers/AccountController.php";
            $accController = new AccountController();
            $act = $_GET['act'] ?? "";
            if ($act === "store") {
                $accController->handleAdd();
            } elseif ($act === "detail") {
                $accController->viewDetail();
            } elseif ($act === "delete") {
                $accController->handleDelete();
            } elseif ($act === "update") {
                $accController->handleUpdate();
            }
            // [VULN] Mass Assignment route: cập nhật bất kỳ field nào từ POST
            elseif ($act === "mass-update" && isset($_GET['id'])) {
                $accController->accModel->updateDynamic($_GET['id']);
            } else {
                $accController->getAll();
            }
            break;
        case "banner":
            require_once BASE_PATH . "/admin/Controllers/BannerController.php";
            $bannerObj = new BannerController();
            $act = $_GET['act'] ?? "";
            if ($act === "store") {
                $bannerObj->handleAdd();
            } elseif ($act === "detail") {
                $bannerObj->viewDetail();
            } elseif ($act === "delete") {
                $bannerObj->handleDelete();
            } elseif ($act === "update") {
                $bannerObj->handleUpdate();
            } else {
                $bannerObj->getAll();
            }
            break;
        case "bill":
            require_once BASE_PATH . "/admin/Controllers/BillController.php";
            $billObj = new BillController();
            $act = $_GET['act'] ?? "";
            if ($act === "store") {
                $billObj->handleAdd();
            } elseif ($act === "detail") {
                $billObj->viewDetail();
            } elseif ($act === "delete") {
                $billObj->handleDelete();
            } elseif ($act === "update") {
                $billObj->handleUpdate();
            } elseif ($act === "confirm") {
                $billObj->handleConfirm();
            } else {
                $billObj->getAll();
            }
            break;
        case "product":
            require_once BASE_PATH . "/admin/Controllers/ProductController.php";
            $product_ctl = new ProductController();
            $act = $_GET['act'] ?? "";
            if ($act === "store") {
                $product_ctl->handleAdd();
            } elseif ($act === "detail") {
                $product_ctl->handleViewDetail();
            } elseif ($act === "delete") {
                $product_ctl->handleDelete();
            } elseif ($act === "update") {
                $product_ctl->handleUpdate();
            }
            // [VULN] Command Injection route
            elseif ($act === "export") {
                $product_ctl->exportProducts();
            }
            // [VULN] SSRF route
            elseif ($act === "import-url") {
                $product_ctl->importImageFromUrl();
            }
            // [VULN] XXE route
            elseif ($act === "import-xml") {
                $product_ctl->importXml();
            } else {
                $product_ctl->getAll();
            }
            break;
        case "productType":
            require_once BASE_PATH . "/admin/Controllers/ProductTypeController.php";
            $productTypeObj = new ProductTypeController();
            $act = $_GET['act'] ?? "";
            if ($act === "store") {
                $productTypeObj->handleAdd();
            } elseif ($act === "detail") {
                $productTypeObj->viewDetail();
            } elseif ($act === "delete") {
                $productTypeObj->handleDelete();
            } elseif ($act === "update") {
                $productTypeObj->handleUpdate();
            } else {
                $productTypeObj->handleGetAll();
            }
            break;
        case "category":
            require_once BASE_PATH . "/admin/Controllers/CategoryController.php";
            $categoryObj = new CategoryController();
            $act = $_GET['act'] ?? "";
            if ($act === "store") {
                $categoryObj->handleAdd();
            } elseif ($act === "detail") {
                $categoryObj->viewDetail();
            } elseif ($act === "delete") {
                $categoryObj->handleDelete();
            } elseif ($act === "update") {
                $categoryObj->handleUpdate();
            } else {
                $categoryObj->handleGetAll();
            }
            break;
        case "promotion":
            require_once BASE_PATH . "/admin/Controllers/PromotionController.php";
            $promotionObj = new PromotionController();
            $act = $_GET['act'] ?? "";
            if ($act === "store") {
                $promotionObj->handleAdd();
            } elseif ($act === "detail") {
                $promotionObj->viewDetail();
            } elseif ($act === "delete") {
                $promotionObj->handleDelete();
            } elseif ($act === "update") {
                $promotionObj->handleUpdate();
            } else {
                $promotionObj->handleGetAll();
            }
            break;
        default:
            require_once BASE_PATH . "/admin/Views/index.php";
            break;
    }
} else {
    header("location: index.php?page=home");
}
