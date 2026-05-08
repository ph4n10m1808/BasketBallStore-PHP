<?php

require_once BASE_PATH . "/admin/Models/banner.php";
require_once __DIR__ . "/ImageUploadTrait.php";

class BannerController
{
    use ImageUploadTrait;
    public banner $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new banner();
    }

    public function getAll(): void
    {
        $act = $_GET['act'] ?? "";

        if ($act === "edit" && isset($_GET['id'])) {
            $id = $_GET['id'];
            $banner = $this->bannerModel->view($id)->fetch_assoc();
        } elseif ($act === "") {
            $bannerList = $this->bannerModel->getAll();
        }

        require_once BASE_PATH . "/admin/Views/index.php";
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

    public function viewDetail(): void
    {
        $id = $_GET['id'];
        $banner = $this->bannerModel->view($id)->fetch_assoc();
        require_once BASE_PATH . "/admin/Views/index.php";
    }

    public function handleDelete(): void
    {
        $id = $_GET['id'];

        // Fetch banner details to get the image URL
        $banner = $this->bannerModel->view($id)->fetch_assoc();
        if ($banner && !empty($banner['url_banner'])) {
            $this->deleteImage($banner['url_banner']);
        }

        $this->bannerModel->delete($id);
    }

    public function handleUpdate(): void
    {
        $id = $_GET['id'];

        // Fetch current banner to get old image
        $oldBanner = $this->bannerModel->view($id)->fetch_assoc();
        $oldImage = $oldBanner ? $oldBanner['url_banner'] : null;

        // Use updateImage helper (pass subDir as "Banner" because formatImage expects it)
        $bannerImage = $this->updateImage("url_banner", $oldImage, "Banner");

        if (empty($bannerImage)) {
            setcookie('msg', 'Upload ảnh thất bại hoặc không có ảnh nào. Vui lòng chọn lại ảnh.', time() + 5, '/');
            header("location: ?mod=banner&act=edit&id=" . $id);
            return;
        }

        $status = $_POST['status'] ?? 1;
        $this->bannerModel->update($id, $bannerImage, $status);
    }

}
