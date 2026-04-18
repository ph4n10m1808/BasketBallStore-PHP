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
        $query = "SELECT *, (SELECT value from promotion where id_promotion = product.id_promotion) as d_price,
       (SELECT type_sale from promotion WHERE id_promotion = product.id_promotion) as type_p,
       (SELECT type_promotion from promotion WHERE id_promotion = product.id_promotion) as name_sale,
       (SELECT name_pt from product_type WHERE id_product_type = product.id_product_type) as p_type_name
       FROM product WHERE id_category = $idCategory ORDER BY status DESC , n_stars DESC LIMIT 0, 15";
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
