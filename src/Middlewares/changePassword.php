<?php

require_once __DIR__ . "/../Controllers/ProfileController.php";

$profileObj = new ProfileController();
$oldPassword = $_POST['oldPassword'] ?? "";
$newPassword = $_POST['newPassword'] ?? "";
$confirmPassword = $_POST['confirmPassword'] ?? "";

try {
    echo json_encode($profileObj->handleChangePassword($oldPassword, $newPassword, $confirmPassword), JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}
