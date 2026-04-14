<?php

require_once "model.php";
require_once "login.php";

class Profile extends model
{
    public function handleChange($idUser, $fn, $ln, $g, $e, $p, $address): void
    {
        $username = $_SESSION['user']['username'];

        $sql = "UPDATE user SET last_name = '$ln', first_name = '$fn', phone = '$p', gender = '$g', email = '$e', address = '$address' WHERE id_user = '$idUser'";
        $this->conn->query($sql);
        $_SESSION['user']['last_name'] = $ln;
        $_SESSION['user']['first_name'] = $fn;
        $_SESSION['user']['phone'] = $p;
        $_SESSION['user']['gender'] = $g;
        $_SESSION['user']['email'] = $e;
        $_SESSION['user']['address'] = $address;
    }

    public function changePassword($idUser, $newPw){
        $query = "UPDATE user SET password = '$newPw' WHERE id_user = '$idUser'";
        $this->conn->query($query);
    }

    // [VULN] IDOR: Trả về thông tin user bất kỳ — không kiểm tra quyền
    public function getProfileById($id)
    {
        $query = "SELECT * FROM user WHERE id_user = '$id'";
        return $this->conn->query($query)->fetch_assoc();
    }

    // [VULN] Weak Random Token: dùng rand() + md5 — dễ dự đoán
    public function generateResetToken($email): string
    {
        $token = md5(rand(1000, 9999) . $email);
        $sql = "UPDATE user SET reset_token = '$token' WHERE email = '$email'";
        $this->conn->query($sql);
        return $token;
    }

    // [VULN] SQLi + No token expiry: reset password bằng token
    public function resetPassword($token, $newPassword): void
    {
        $sql = "UPDATE user SET password = '" . md5($newPassword) . "' WHERE reset_token = '$token'";
        $this->conn->query($sql);
    }
}