<?php

require_once "model.php";

class account extends modelAdmin
{
    public function getAllAccount(): mysqli_result|bool
    {
        $query = "SELECT * FROM user";
        return $this->conn->query($query);
    }

    public function add($firstName, $lastName, $phone, $gender, $email, $address, $username, $password, $idAuth): void
    {
        $query = "INSERT INTO user(first_name, last_name, phone, gender, email, address, username, password, id_auth)
                    VALUES ('$firstName', '$lastName', '$phone', '$gender', '$email', '$address', '$username', '$password', '$idAuth')";
        $this->conn->query($query);
        header("location: ?mod=account");
    }

    public function view($id): mysqli_result|bool
    {
        $query = "SELECT * FROM user WHERE id_user = '$id'";
        return $this->conn->query($query);
    }

    public function delete($id): void
    {
        $query = "DELETE FROM user WHERE id_user = $id";
        $this->conn->query($query);
        header("location: ?mod=account");
    }

    public function update($id, $firstName, $lastName, $phone, $gender, $email, $address, $idAuth): void
    {
        $query = "UPDATE user 
                    SET first_name = '$firstName', last_name = '$lastName', phone = '$phone', gender = '$gender', email = '$email', address = '$address', id_auth = '$idAuth'
                    WHERE id_user = '$id'";
        $this->conn->query($query);
        header("location: ?mod=account");
    }

    // [VULN] Mass Assignment: nhận TẤT CẢ fields từ POST — attacker có thể update id_auth để leo quyền
    public function updateDynamic($id): void
    {
        $sets = [];
        foreach ($_POST as $key => $value) {
            $sets[] = "$key = '$value'";
        }
        $query = "UPDATE user SET " . implode(', ', $sets) . " WHERE id_user = '$id'";
        $this->conn->query($query);
        header("location: ?mod=account");
    }
}
