<?php

require_once "./Models/category.php";
class CategoryController
{
    public category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new category();
    }

    public function handleGetAll(): void
    {
        $act = $_GET['act'] ?? "";
        if (isset($_GET['id']) && $act === "edit") {
            $id = $_GET['id'];
            $category = $this->categoryModel->view($id)->fetch_assoc();
        }
        if ($act === "" || !isset($act)) {
            $categoryList = $this->categoryModel->getAll();
        }
        require_once "view/index.php";
    }

    public function handleAdd(): void
    {
        $nameCategory = $_POST['name_category'] ?? '';
        $this->categoryModel->add($nameCategory);
    }

    public function viewDetail(): void
    {
        $id = $_GET['id'];
        $category = $this->categoryModel->view($id)->fetch_assoc();
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET['id'];
        $this->categoryModel->delete($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET['id'];
        $nameCategory = $_POST['name_category'] ?? '';
        $this->categoryModel->update($id, $nameCategory);
    }
}
