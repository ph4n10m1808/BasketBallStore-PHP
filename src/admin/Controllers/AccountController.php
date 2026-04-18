<?php

require_once "./Models/account.php";

class AccountController
{
    public account $accModel;

    public function __construct()
    {
        $this->accModel = new account();
    }

    public function getAll(): void
    {
        $accountList = $this->accModel->getAllAccount();
        if (isset($_GET['id']) && $_GET['act'] === "edit") {
            $id = $_GET['id'];
            $detailStuff = $this->accModel->view($id)->fetch_assoc();
        }
        require_once "view/index.php";
    }

    public function handleAdd(): void
    {
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = md5($_POST['password'] ?? '');
        $idAuth = $_POST['id_auth'] ?? 2;
        $this->accModel->add($firstName, $lastName, $phone, $gender, $email, $address, $username, $password, $idAuth);
    }

    public function viewDetail(): void
    {
        $id = $_GET['id'] ?? '';
        $detailStuff = $this->accModel->view($id)->fetch_assoc();
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET['id'] ?? '';
        $this->accModel->delete($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET['id'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';
        $idAuth = $_POST['id_auth'] ?? 2;
        $this->accModel->update($id, $firstName, $lastName, $phone, $gender, $email, $address, $idAuth);
    }
}
