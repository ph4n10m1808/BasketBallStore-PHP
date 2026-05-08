<?php

require_once('model.php');
class Home extends model
{
    public function getLatestProducts($start, $end, $idCategory): array
    {
        $query = "SELECT p.*, 
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.id_category = $idCategory AND p.status >= 1 
                ORDER BY p.timestamp DESC 
                LIMIT $start, $end";
        return $this->queryWithPromotion($query);
    }

    public function getBanner(): array
    {
        $query = "SELECT id_banner, url_banner FROM banner WHERE status = 1 ORDER BY timestamp DESC";
        return $this->resultReturnArray($query);
    }

    public function getTypicalProducts($start, $end): array
    {
        $query = "SELECT id_t_product, url_image, url_event, title, description FROM typical_products LIMIT $start, $end";
        return $this->resultReturnArray($query);
    }

    public function getLastProductType($start, $end, $idProductType): array
    {
        $query = "SELECT p.*, 
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.id_product_type = $idProductType AND p.status >= 1 
                ORDER BY p.status DESC, p.n_stars DESC 
                LIMIT $start, $end";
        return $this->queryWithPromotion($query);
    }

    public function getOutstandingProduct($start, $end, $idCategory): array
    {
        $query = "SELECT p.*, 
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.id_category = $idCategory AND p.status >= 1 
                ORDER BY p.timestamp DESC, p.n_stars DESC 
                LIMIT $start, $end";
        return $this->queryWithPromotion($query);
    }

    public function getProductTypes($idCategory): mysqli_result|bool
    {
        $query = "SELECT id_product_type, name_pt FROM product_type WHERE id_category = $idCategory";
        return $this->conn->query($query);
    }

}
