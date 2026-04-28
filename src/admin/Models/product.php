<?php

require_once "model.php";

class product extends modelAdmin
{
    public function getAllProduct(): mysqli_result|bool
    {
        $query = "SELECT * FROM product";
        $rs = $this->conn->query($query);
        return $rs;
    }

    public function getProductType(): mysqli_result|bool
    {
        $query = "SELECT * FROM product_type";
        return $this->conn->query($query);
    }

    public function getCategory(): mysqli_result|bool
    {
        $query = "SELECT * FROM category";
        return $this->conn->query($query);
    }

    public function getPromotion(): mysqli_result|bool
    {
        $query = "SELECT * FROM promotion";
        return $this->conn->query($query);
    }

    public function addNewProduct($titleProduct, $nameProduct, $price, $quantity, $idCategory, $idProductType, $mainImage, $image1, $image2, $image3, $image4, $size, $idPromotion, $description): void
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $time =  date('Y-m-d H:i:s');
        $query = "INSERT INTO product(title_product, name_product, price, quantity, id_category, id_product_type, main_image, image1, image2, image3, image4, size, id_promotion, description, timestamp ) 
                    VALUES ('$titleProduct', '$nameProduct', $price, $quantity, $idCategory, $idProductType, '$mainImage', '$image1', '$image2', '$image3', '$image4', '$size', '$idPromotion', '$description', '$time')";
        $this->conn->query($query);
        header("location: ?mod=product");
    }

    public function view($id): bool|array|null
    {
        $query = "SELECT * FROM product WHERE id_product = '$id'";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function deleteProduct($id): void
    {
        $query = "DELETE FROM product WHERE id_product = $id";
        $this->conn->query($query);
        header("location: ?mod=product");
    }

    public function update($id, $mainImage, $image1, $image2, $image3, $image4, $size, $titleProduct, $nameProduct, $price, $quantity, $idCategory, $idProductType, $idPromotion, $description, $status = 0): void
    {
        $query = "UPDATE product 
                    SET title_product = '$titleProduct', name_product = '$nameProduct', price = '$price', quantity = '$quantity', id_category = '$idCategory', id_product_type = '$idProductType', main_image = '$mainImage', image1 = '$image1', image2 = '$image2', image3 = '$image3', image4 = '$image4', size = '$size', id_promotion = '$idPromotion', description = '$description', status = '$status'
                    WHERE id_product = '$id'";
        $this->conn->query($query);
        header("location: ?mod=product");
    }
}
