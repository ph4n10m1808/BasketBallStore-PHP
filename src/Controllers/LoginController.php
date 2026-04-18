<?php

require_once __DIR__ . "/../Models/login.php";
require_once __DIR__ . "/../Models/check.php";

class LoginController
{
    private Login $login_model;
    private Check $check_model;

    public function __construct()
    {
        $this->login_model = new Login();
        $this->check_model = new Check();
    }

    public function register(): void
    {
        require_once("Views/index.php");
    }

    public function login(): void
    {
        require_once("Views/index.php");
    }

    public function handleRegister($firstName, $lastName, $gender, $username, $password, $confirmPassword, $email, $phone)
    {
        $result = array();
        $result["msgFn"] = $this->check_model->checkEmpty($firstName);
        $result["msgLn"] = $this->check_model->checkEmpty($lastName);
        $result["msgGender"] = $this->check_model->checkEmpty($gender);
        $result["msgUsername"] = $this->check_model->checkUsernameReg($username);
        $result["msgEmail"] = $this->check_model->checkEmailReg($email);
        $result["msgPhone"] = $this->check_model->checkPhoneReg($phone);
        $result["msgPassword"] = $this->check_model->checkPassword($password, $confirmPassword);

        $checkRegex = implode('', $result);
        if (!$checkRegex) {
            $this->login_model->handleRegister($firstName, $lastName, $gender, $username, md5($password), $email, $phone);

        } else {
            return $result;
        }
    }

    public function handleLogin($username, $password): array
    {
        $result = array();
        $result["msgUsername"] = $this->check_model->checkEmpty($username);
        $result["msgPassword"] = $this->check_model->checkEmpty($password);
        $result["msgLogin"] = "";

        $checkRegex = implode('', $result);
        if (!$checkRegex) {
            $rs = $this->login_model->handleLogin($username, md5($password));
            if (!$rs) {
                $result["msgLogin"] = "Sai tên đăng nhập hoặc mật khẩu";
            }
            return $result;
        } else {
            return $result;
        }
    }

    public function handleLogout(): void
    {
        $this->login_model->handleLogout();
    }
}
