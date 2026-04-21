<?php

require_once __DIR__ . "/../Controllers/ProfileController.php";

$profileObj = new ProfileController();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$firstName = $_POST['firstname'] ?? $_SESSION['user']['first_name'];
$lastName = $_POST['lastname'] ?? $_SESSION['user']['last_name'];
$gender = $_POST['gender'] ?? $_SESSION['user']['gender'];
$email = $_POST['email'] ?? $_SESSION['user']['email'];
$phone = $_POST['phone'] ?? $_SESSION['user']['phone'];
$address = $_POST['address'] ?? $_SESSION['user']['address'];

try {
    echo json_encode($profileObj->handleChangeInfo($firstName, $lastName, $gender, $email, $phone, $address), JSON_THROW_ON_ERROR);
} catch (JsonException $ex) {
}
