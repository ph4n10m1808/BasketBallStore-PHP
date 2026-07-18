<?php

session_start();

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/app/Support/RequestTelemetry.php';
RequestTelemetry::start($_SERVER, $_GET, $_POST);

// [VULN] Insecure Deserialization: auto-login từ cookie không an toàn
if (!isset($_SESSION['user']) && isset($_COOKIE['remember_user'])) {
    $userData = unserialize(base64_decode($_COOKIE['remember_user']));
    if ($userData) {
        $_SESSION['user'] = $userData;
        $_SESSION['login'] = true;
    }
}

$route = $_GET['page'] ?? "home";

switch ($route) {
    case "health":
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'ok'], JSON_THROW_ON_ERROR);
        break;
    case "cart":
        require_once BASE_PATH . "/app/Controllers/CartController.php";
        $cartObj = new CartController();
        $act = $_GET['act'] ?? "";
        switch ($act) {
            case "clear":
                $cartObj->clearCart();
                break;
            case "pay":
                $cartObj->addCart();
                break;
            default:
                $cartObj->getCart();
                break;
        }
        break;
    case "home":
        require_once BASE_PATH . "/app/Controllers/HomeController.php";
        $controller_obj = new HomeController();
        $controller_obj->list();
        break;
    case "register":
        require_once BASE_PATH . "/app/Controllers/LoginController.php";
        $controller_obj = new LoginController();
        $controller_obj->register();
        break;
    case "login":
        require_once BASE_PATH . "/app/Controllers/LoginController.php";
        $controller_obj = new LoginController();
        // [VULN] Open Redirect: lưu redirect URL từ user input
        $redirect = $_GET['redirect'] ?? '';
        $controller_obj->login();
        break;
    case "logout":
        require_once BASE_PATH . "/app/Controllers/LoginController.php";
        $controller_obj = new LoginController();
        $controller_obj->handleLogout();
        break;
        // [VULN] LFI / Path Traversal: include file tùy ý qua parameter
    case "include":
        $file = $_GET['file'] ?? "";
        if ($file) {
            // [VULN] LFI / Path Traversal: cho phép include bất kỳ file nào
            require_once BASE_PATH . "/app/Views/" . $file;
        }
        break;
    case "detail":
        require_once BASE_PATH . "/app/Controllers/ProductDetailController.php";
        $detailObj = new ProductDetailController();
        $detailObj->viewDetail();
        break;
    case "product":
        require_once BASE_PATH . "/app/Controllers/EachProductTypeController.php";
        $productTypeObj = new EachProductTypeController();
        $type = $_GET['type'] ?? "";
        $id = $_GET['id'] ?? "";
        if ($type) {
            $productTypeObj->getCategory();
        } else {
            if ($id) {
                $productTypeObj->getProductType();
            } else {
                require_once BASE_PATH . "/app/Views/index.php";
            }
        }
        break;
    case "profile":
        require_once BASE_PATH . "/app/Controllers/ProfileController.php";
        $profileObj = new ProfileController();
        $act = $_GET['act'] ?? "";
        if ($act === "view" && isset($_GET['id'])) {
            // [VULN] IDOR: bất kỳ ai cũng có thể xem profile user khác
            $profileObj->viewOtherProfile();
        } elseif (isset($_SESSION['user']) && $_SESSION['user']) {
            $profileObj->view();
        } else {
            header("location: ?page=login");
        }
        break;
        // [VULN] Weak Password Reset Token
    case "reset":
        require_once BASE_PATH . "/app/Controllers/ProfileController.php";
        require_once BASE_PATH . "/app/Models/profile.php";
        $profileModel = new Profile();
        $act = $_GET['act'] ?? "";
        if ($act === "request" && isset($_POST['email'])) {
            $token = $profileModel->generateResetToken($_POST['email']);
            echo json_encode(["token" => $token, "msg" => "Token đã được tạo"]);
        } elseif ($act === "confirm" && isset($_POST['token']) && isset($_POST['new_password'])) {
            $profileModel->resetPassword($_POST['token'], $_POST['new_password']);
            header("location: ?page=login");
        } else {
            require_once BASE_PATH . "/app/Views/index.php";
        }
        break;
    case "search":
        require_once BASE_PATH . "/app/Controllers/EachProductTypeController.php";
        $productTypeObj = new EachProductTypeController();
        $productTypeObj->searchProduct();
        break;
    case "bill":
        // BUG-5 fix: Kiểm tra đăng nhập trước khi truy cập session
        if (!isset($_SESSION['user']) || !$_SESSION['user']) {
            header("location: ?page=login");
            break;
        }
        require_once BASE_PATH . "/app/Controllers/BillController.php";
        $idUser = $_SESSION['user']['id_user'];
        $billObj = new BillController();
        $act = $_GET['act'] ?? "";
        if ($act === 'delete') {
            if (!isset($_GET['id']) || !$_GET['id']) {
                require_once BASE_PATH . "/app/Views/error/error.php";
            } else {
                $billObj->handleDelete();
            }
        } else {
            $billObj->getAll($idUser);
        }
        break;
    default:
        require_once BASE_PATH . "/app/Views/index.php";
        break;
}
