<?php
require_once ("connection.php");

class model{
    public mysqli $conn;
    public function __construct(){
        $conn_obj = new Connection();
        $this->conn = $conn_obj->conn;
    }

    public function resultReturnArray($query): array
    {
        $result = $this->conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Thực thi query và tính giá khuyến mãi cho kết quả
     * Gộp từ các method extracted() trùng lặp trong Home, Cart, DetailProduct, EachProductType
     */
    public function queryWithPromotion(string $query): array
    {
        $data = $this->conn->query($query);
        $rs = array();
        while ($row = $data->fetch_assoc()) {
            $rs[] = $row;
        }
        for ($i = 0, $iMax = count($rs); $i < $iMax; $i++) {
            if ($rs[$i]["type_p"] === "0") {
                $rs[$i]["d_price"] = $rs[$i]["price"] - $rs[$i]['d_price'];
            } elseif ($rs[$i]["type_p"] === "1") {
                $rs[$i]["d_price"] = $rs[$i]["price"] * (1 - $rs[$i]['d_price'] / 100);
            }
        }
        return $rs;
    }
}