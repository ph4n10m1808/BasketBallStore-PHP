<?php
// [VULN] Stored XSS: bình luận được lưu thẳng vào DB không sanitize
// Khi hiển thị lại sẽ thực thi script trong trình duyệt user khác

require_once __DIR__ . "/../Models/connection.php";

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$conn_obj = new Connection();
$conn = $conn_obj->conn;

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProduct = $_POST['id_product'];
    $comment = $_POST['comment']; // Không sanitize — XSS payload sẽ được lưu
    $username = $_SESSION['user']['username'] ?? 'anonymous';
    $idUser = $_SESSION['user']['id_user'] ?? 0;
    
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $time = date('Y-m-d H:i:s');
    
    // [VULN] SQLi + Stored XSS: cả input đều không được escape
    $query = "INSERT INTO product_reviews(id_product, id_user, username, comment, timestamp) 
              VALUES ('$idProduct', '$idUser', '$username', '$comment', '$time')";
    $conn->query($query);
    
    echo json_encode(["status" => "success", "msg" => "Đã thêm bình luận"]);
    
} elseif ($action === 'list') {
    $idProduct = $_GET['id_product'] ?? '';
    // [VULN] SQLi trong tham số GET
    $query = "SELECT * FROM product_reviews WHERE id_product = '$idProduct' ORDER BY timestamp DESC";
    $result = $conn->query($query);
    $reviews = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }
    echo json_encode($reviews);
}
