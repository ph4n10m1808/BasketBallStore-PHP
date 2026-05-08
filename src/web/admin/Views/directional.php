<?php

$mod = $_GET["mod"] ?? "dashboard";

switch ($mod) {
    case "dashboard":
        require_once(BASE_PATH . "/admin/Views/dashboard/dashboard.php");
        break;
    case "account":
        require_once(BASE_PATH . "/admin/Views/account/account.php");
        break;
    case "banner":
        require_once BASE_PATH . "/admin/Views/banner/banner.php";
        break;
    case "bill":
        require_once BASE_PATH . "/admin/Views/bill/bill.php";
        break;
    case "product":
        require_once BASE_PATH . "/admin/Views/product/product.php";
        break;
    case "productType":
        require_once BASE_PATH . "/admin/Views/producttype/productype.php";
        break;
    case "category":
        require_once BASE_PATH . "/admin/Views/category/category.php";
        break;
    case "promotion":
        require_once BASE_PATH . "/admin/Views/promotion/promotion.php";
        break;
    default:
        require_once BASE_PATH . "/admin/Views/error/error.php";
        break;
}
