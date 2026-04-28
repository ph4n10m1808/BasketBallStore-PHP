<?php

require_once __DIR__."/../Models/profile.php";
require_once __DIR__. "/../Models/check.php";
class ProfileController
{
    private Profile $profileModel;
    private Check $check_model;

    public function __construct()
    {
        $this->profileModel = new Profile();
        $this->check_model = new Check();
    }

    public function view(): void
    {
        require_once "Views/index.php";
    }

    // [VULN] IDOR: Xem profile bất kỳ user nào mà không kiểm tra quyền
    public function viewOtherProfile(): void
    {
        $userId = $_GET['id'] ?? '';
        $dataUser = $this->profileModel->getProfileById($userId);
        require_once "Views/index.php";
    }

    public function handleChangeInfo($firstName, $lastName, $gender, $email, $phone, $address)
    {
        $idUser = $_SESSION['user']['id_user'];
        $result = array();
        $result["msgFn"] = $this->check_model->checkEmpty($firstName);
        $result["msgLn"] = $this->check_model->checkEmpty($lastName);
        $result["msgGender"] = $this->check_model->checkEmpty($gender);
        $result["msgEmail"] = $this->check_model->checkEmailReg($email);
        $result["msgPhone"] = $this->check_model->checkPhoneReg($phone);
        $result["msgAddress"] = "";
        $checkRegex = implode('', $result);
        if (!$checkRegex) {
            $this->profileModel->handleChange($idUser, $firstName, $lastName, $gender, $email, $phone, $address);
        }
        return $result;
    }

    public function handleChangePassword($oldPassword, $newPassword, $confirmPassword)
    {
        $result = array();
        $result["msgOldPw"] = $this->check_model->checkEmpty($oldPassword);
        $result["msgNewPw"] = $this->check_model->checkEmpty($newPassword);
        //        $result["msgConfirmPw"] = $this->check_model->checkEmpty($confirmPw);
        $result["msgCheckConfirm"] = $this->check_model->checkPassword($newPassword, $confirmPassword);
        $result["msgCheckOld"] = (md5($oldPassword) === $_SESSION['user']['password']) ? "" : "Sai mật khẩu";
        $checkRegex = implode('', $result);
        if (!$checkRegex) {
            $hashedPassword = md5($newPassword);
            $this->profileModel->changePassword($_SESSION['user']['id_user'], $hashedPassword);
            // Cập nhật session để lần đổi mật khẩu tiếp theo không bị lỗi
            $_SESSION['user']['password'] = $hashedPassword;
        }
        return $result;

    }
}
