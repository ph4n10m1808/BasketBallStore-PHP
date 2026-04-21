<?php

require_once "model.php";

class DetailProduct extends model
{
    public function getData($id): bool|array|null
    {
        $query = "SELECT * FROM product WHERE id_product = '$id'";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function getRelated($idCategory): array
    {
        $query = "SELECT p.*, 
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.id_category = $idCategory 
                ORDER BY p.status DESC, p.n_stars DESC 
                LIMIT 0, 15";
        return $this->queryWithPromotion($query);
    }

    public function getProductType($idProductType): bool|array|null
    {
        $query = "SELECT name_pt FROM product_type WHERE id_product_type = $idProductType";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function getCategory($idCategory): bool|array|null
    {
        $query = "SELECT name_category, id_category FROM category WHERE id_category = $idCategory";
        return $this->conn->query($query)->fetch_assoc();
    }

}
