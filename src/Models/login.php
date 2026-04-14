<?php
require_once("model.php");

class Login extends model
{

    public function handleRegister($fn, $ln, $g, $us, $pw, $e, $p): void
    {
        $sql = "INSERT INTO user(last_name, first_name, phone, gender, email, address, username, password) 
                values ('{$ln}', '{$fn}', '{$p}', $g, '{$e}', '', '{$us}', '{$pw}')";
        $this->conn->query($sql);
    }

    public function handleLogin($username, $password): bool|array|null
    {
        $sql = "SELECT * FROM user where username = '{$username}' AND password = '{$password}'";
        $rs = $this->conn->query($sql)->fetch_assoc();
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if ($rs) {
            $_SESSION['login'] = true;
            $_SESSION['user'] = $rs;
            if ($rs['id_auth'] === "1") {
                $_SESSION['auth'] = true;
            } elseif ($rs['id_auth'] === "2") {
                $_SESSION['employee'] = true;
            }
            // [VULN] Insecure Deserialization: serialize toàn bộ user data vào cookie
            $userData = serialize($rs);
            setcookie('remember_user', base64_encode($userData), time() + 86400, '/');
        }
        return $rs;
    }

    public function handleLogout(): void
    {
        unset($_SESSION['login'], $_SESSION['user'], $_SESSION["auth"], $_SESSION["employee"]);
        // Xóa cookie remember_user khi logout
        setcookie('remember_user', '', time() - 3600, '/');
        header("location: ?page=home");
    }
}
