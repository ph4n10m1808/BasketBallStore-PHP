<?php

require_once "./Models/bill.php";

class BillController
{
    public bill $billModel;

    public function __construct()
    {
        $this->billModel = new bill();
    }

    public function getAll(): void
    {
        $billList = $this->billModel->getAll();
        $userList = $this->billModel->getUser();
        if (isset($_GET['id']) && $_GET['act'] === "edit") {
            $id = $_GET['id'];
            $detailStuff = $this->billModel->view($id)->fetch_assoc();
        }
        require_once "view/index.php";
    }

    public function handleAdd(): void
    {
        $idUser = $_POST['id_user'] ?? '';
        $nameUser = $_POST['name_user'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? 0;
        $totalCost = $_POST['total_cost'] ?? 0;
        $status = $_POST['status'] ?? 0;
        $note = $_POST['note'] ?? '';
        $this->billModel->add($idUser, $nameUser, $phone, $address, $paymentMethod, $totalCost, $status, $note);
    }

    public function viewDetail(): void
    {
        $id = $_GET['id'];
        $detailStuff = $this->billModel->view($id)->fetch_assoc();
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET['id'];
        $this->billModel->delete($id);
    }

    public function handleConfirm(): void
    {
        $id = $_GET['id'];
        $this->billModel->confirm($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET['id'] ?? '';
        $idUser = $_POST['id_user'] ?? '';
        $nameUser = $_POST['name_user'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? 0;
        $totalCost = $_POST['total_cost'] ?? 0;
        $status = $_POST['status'] ?? 0;
        $note = $_POST['note'] ?? '';
        $this->billModel->update($id, $idUser, $nameUser, $phone, $address, $paymentMethod, $totalCost, $status, $note);
    }
}
