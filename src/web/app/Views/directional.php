<?php

$route = $_GET["page"] ?? "home";

switch ($route) {
    case "cart":
        require_once BASE_PATH . "/app/Views/cart/cart.php";
        break;
    case "home":
        require_once BASE_PATH . "/app/Views/home/home.php";
        break;
    case "register":
        $login = $_SESSION['login'] ?? "";
        if (!$login) {
            require_once BASE_PATH . "/app/Views/login/register.php";
        } else {
            echo '<div class="text-center py-5"><h4>Bạn đã đăng nhập</h4><p class="text-muted">Bạn đang đăng nhập rồi.</p><a href="?page=home" class="btn btn-primary mt-2">Về trang chủ</a></div>';
        }
        break;
    case "login":
        $login = $_SESSION['login'] ?? "";
        if (!$login) {
            require_once BASE_PATH . "/app/Views/login/login.php";
        } else {
            echo '<div class="text-center py-5"><h4>Bạn đã đăng nhập</h4><p class="text-muted">Bạn đang đăng nhập rồi.</p><a href="?page=home" class="btn btn-primary mt-2">Về trang chủ</a></div>';
        }
        break;
    case "detail":
        require_once BASE_PATH . "/app/Views/detail/detail.php";
        break;
    case "product":
        if (isset($productList) && $productList) {
            require_once BASE_PATH . "/app/Views/productType/productType.php";
        } else {
            require_once BASE_PATH . "/app/Views/error/error.php";
        }
        break;
    case "profile":
        if (isset($_SESSION['user']) && $_SESSION['user']) {
            require_once BASE_PATH . "/app/Views/profile/profile.php";
        }
        break;
    case "search":
        if (isset($searchResults)) {
            require_once BASE_PATH . "/app/Views/search/search.php";
        }
        break;
    case "bill":
        if (isset($_SESSION['user']) && $_SESSION['user']) {
            require_once BASE_PATH . "/app/Views/bill/bill.php";
        }
        break;
    default:
        require_once BASE_PATH . "/app/Views/error/error.php";
        break;
}
