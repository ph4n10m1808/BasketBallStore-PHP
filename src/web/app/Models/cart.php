<?php

require_once "model.php";

class Cart extends model
{
    public function addCartNotLogin($id, $quantity, $size, $restQuantity)
    {
        $query = "SELECT p.*, 
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.id_product = $id";
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $data = $this->queryWithPromotion($query);

        // BUG-3 fix: Kiểm tra sản phẩm tồn tại trước khi thêm
        if (empty($data)) {
            return;
        }

        $check = false;
        if (isset($_SESSION['carts'])) {
            $cartCount = count($_SESSION['carts']);
            for ($i = 0; $i < $cartCount; $i++) {
                if ($_SESSION['carts'][$i]['id_product'] === $id && $_SESSION['carts'][$i]['size'] === $size) {
                    $_SESSION['carts'][$i]['quantity'] += $quantity;
                    $check = true;
                    break;
                }
            }
        }
        if ($check === false) {
            $data = array_merge($data[0], ["quantity" => $quantity, "size" => $size, "restQuantity" => $restQuantity]);
            $_SESSION['carts'][] = $data;
        }
        $this->costCart();
    }

    private function costCart(): void
    {
        // BUG-7 fix: Kiểm tra giỏ hàng tồn tại và không rỗng
        if (empty($_SESSION['carts'])) {
            $_SESSION['totalCart'] = 0;
            return;
        }

        $totalProducts = 0;
        foreach ($_SESSION['carts'] as $each) {
            $totalProducts += ($each['d_price'] ?? $each['price']) * $each['quantity'];
        }
        $total = $totalProducts + 30000;
        $_SESSION['totalCart'] = $total;
    }

    public function deleteItemSession($id, $size = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['carts'])) {
            return;
        }

        // BUG-2 fix: Dùng array_filter thay vì for + unset để tránh lỗi index
        $_SESSION['carts'] = array_values(array_filter($_SESSION['carts'], function ($item) use ($id, $size) {
            if ($size !== null) {
                return !($item['id_product'] === $id && $item['size'] === $size);
            }
            return $item['id_product'] !== $id;
        }));

        // Tính lại tổng tiền sau khi xóa
        $this->costCart();
    }

    public function clearCart()
    {
        $_SESSION['carts'] = [];
        $_SESSION['totalCart'] = 0;
        header("location: ?page=cart");
    }

    public function addCart()
    {
        // BUG-4 fix: Kiểm tra user đã đăng nhập
        if (!isset($_SESSION['user']) || !$_SESSION['user']) {
            header("location: ?page=login");
            return;
        }

        $user = $_SESSION['user'];
        $name = $user['first_name']." ". $user['last_name'];
        $idUser = $user['id_user'];
        $phone = $user['phone'];
        $address = $user['address'];
        $total = $_SESSION['totalCart'] ?? 0;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $time =  date('Y-m-d H:i:s');
        $query = "INSERT INTO bill(id_user, name_user, phone, address, payment_method, total_cost, timestamp, note)
                VALUES ('$idUser', '$name', '$phone', '$address', 0, '$total', '$time', '')";
        $this->conn->query($query);
        $this->clearCart();
    }

    public function updateCart($id, $type, $size)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['carts'])) {
            return null;
        }

        $cartCount = count($_SESSION['carts']);
        for ($i = 0; $i < $cartCount; $i++) {
            if ($_SESSION['carts'][$i]['id_product'] === $id && $_SESSION['carts'][$i]['size'] === $size) {
                if ($type === 'minus' && $_SESSION['carts'][$i]['quantity'] !== 1) {
                    $_SESSION['carts'][$i]['quantity'] -= 1;
                } elseif ($type === 'plus') {
                    $_SESSION['carts'][$i]['quantity'] += 1;
                }
                $this->costCart();
                return $_SESSION['totalCart'];
            }
        }
        return null;
    }

}
