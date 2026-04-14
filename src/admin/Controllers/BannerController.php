<?php
require_once "./Models/banner.php";
require_once __DIR__ . "/ImageUploadTrait.php";

class BannerController{
    use ImageUploadTrait;
    public banner $bannerModel;

    public function __construct(){
        $this->bannerModel = new banner();
    }

    public function getAll(): void
    {
        $bannerList = $this->bannerModel->getAll();
        if(isset($_GET['id']) && $_GET['act'] === "edit"){
            $id = $_GET['id'];
            $detailStuff = $this->bannerModel->view($id)->fetch_assoc();
        }
        require_once "view/index.php";
    }

    public function handleAdd(): void
    {
        $banner = $this->formatImage("url_banner", "Banner");

        if (empty($banner)) {
            setcookie('msg', 'Upload ảnh thất bại. Vui lòng chọn lại ảnh.', time() + 5, '/');
            header("location: ?mod=banner&act=add");
            return;
        }

        $status = $_POST['status'];
        $this->bannerModel->add($banner, $status);
    }

    public function viewDetail():void
    {
        $id = $_GET['id'];
        $detailStuff = $this->bannerModel->view($id)->fetch_assoc();
        require_once "view/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET['id'];
        $this->bannerModel->delete($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET['id'];
        $bannerImage = $this->formatImage("url_banner", "Banner");

        if (empty($bannerImage)) {
            setcookie('msg', 'Upload ảnh thất bại. Vui lòng chọn lại ảnh.', time() + 5, '/');
            header("location: ?mod=banner&act=edit&id=" . $id);
            return;
        }

        $this->bannerModel->update($id, $bannerImage);
    }

}