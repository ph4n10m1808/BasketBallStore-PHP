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
        $act = $_GET['act'] ?? "";

        if ($act === "edit" && isset($_GET['id'])) {
            $id = $_GET['id'];
            $account = $this->accModel->view($id)->fetch_assoc();
        } elseif ($act === "") {
            $accountList = $this->accModel->getAllAccount();
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
        $account = $this->accModel->view($id)->fetch_assoc();
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
