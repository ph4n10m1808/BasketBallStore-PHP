<?php

session_start();
require_once __DIR__ . '/../Controllers/LoginController.php';
$LoginController = new LoginController();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
// [VULN] Open Redirect: nhận redirect URL từ user input không validate
$redirect = $_POST['redirect'] ?? '?page=home';
try {
    $result = $LoginController->handleLogin($username, $password);
    $result['redirect'] = $redirect;
    echo json_encode($result, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}
