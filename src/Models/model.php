<?php

require_once("connection.php");

class model
{
    public mysqli $conn;
    public function __construct()
    {
        $conn_obj = new Connection();
        $this->conn = $conn_obj->conn;
    }

    public function resultReturnArray($query): array
    {
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Thực thi query và tính giá khuyến mãi cho kết quả
     */
    public function queryWithPromotion(string $query): array
    {
        $result = $this->conn->query($query);
        if (!$result) return [];
        
        $rs = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($rs as &$row) {
            if ($row["type_p"] === "0") {
                $row["d_price"] = $row["price"] - $row['d_price'];
            } elseif ($row["type_p"] === "1") {
                $row["d_price"] = $row["price"] * (1 - $row['d_price'] / 100);
            } else {
                // Không có promotion hoặc type_p không hợp lệ → giữ nguyên giá gốc
                $row["d_price"] = $row["price"];
            }
        }
        unset($row); // Giải phóng reference sau foreach by-reference
        return $rs;
    }
}
