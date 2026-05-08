<?php

require_once "model.php";

class promotion extends modelAdmin
{
    public function getAll(): mysqli_result|bool
    {
        $query = "SELECT * FROM promotion";
        return $this->conn->query($query);
    }

    public function add($namePromotion, $typePromotion, $typeSale, $value): void
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $time =  date('Y-m-d H:i:s');
        $query = "INSERT INTO promotion(name_promotion, type_promotion, type_sale, value, start_day) VALUES ('$namePromotion', '$typePromotion', '$typeSale', '$value', '$time')";
        $this->conn->query($query);
        header("location: ?mod=promotion");
    }

    public function view($id): bool|array|null
    {
        $query = "SELECT * FROM promotion WHERE id_promotion = '$id'";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function delete($id): void
    {
        $query = "DELETE FROM promotion WHERE id_promotion = $id";
        $this->conn->query($query);
        header("location: ?mod=promotion");
    }

    public function update($id, $namePromotion, $typePromotion, $typeSale, $value, $status): void
    {
        $query = "UPDATE promotion
                    SET name_promotion = '$namePromotion', type_promotion = '$typePromotion', type_sale = '$typeSale', value = '$value', status = '$status'
                    WHERE id_promotion = '$id'";
        $this->conn->query($query);
        header("location: ?mod=promotion");
    }
}
