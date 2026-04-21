<?php

require_once __DIR__ . '/../Controllers/LoginController.php';
$LoginController = new LoginController();

$firstName = $_POST['firstname'] ?? '';
$lastName = $_POST['lastname'] ?? '';
$gender = $_POST['gender'] ?? '';
$username = $_POST['user'] ?? '';
$password = $_POST['pass'] ?? '';
$confirmPassword = $_POST['repass'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

try {
    echo json_encode($LoginController->handleRegister($firstName, $lastName, $gender, $username, $password, $confirmPassword, $email, $phone), JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}
