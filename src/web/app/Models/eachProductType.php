<?php

require_once "model.php";

class EachProductType extends model
{
    public function getProductType($idPT): array
    {
        $query = "SELECT p.*, 
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.id_product_type = $idPT 
                ORDER BY p.status DESC, p.n_stars DESC";
        return $this->queryWithPromotion($query);
    }

    public function getCategory($id): array
    {
        $query = "SELECT p.*, 
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.id_category = $id 
                ORDER BY p.status DESC, p.n_stars DESC";
        return $this->queryWithPromotion($query);
    }

    public function getNameCategory($id): bool|array|null
    {
        $query = "SELECT name_category, id_category FROM category WHERE id_category = $id";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function getNameProductType($idProductType): bool|array|null
    {
        $query = "SELECT name_pt, id_product_type FROM product_type WHERE id_product_type = $idProductType";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function searchProduct($keyword)
    {
        $query = "SELECT p.id_product, p.main_image, p.title_product, p.price, p.status, p.n_stars,
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.name_product LIKE '%$keyword%' OR p.title_product LIKE '%$keyword%' 
                ORDER BY p.status DESC, p.n_stars DESC 
                LIMIT 0, 15";
        return $this->queryWithPromotion($query);
    }

}
