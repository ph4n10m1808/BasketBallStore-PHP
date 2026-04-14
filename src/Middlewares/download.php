<?php
// [VULN] Arbitrary File Download: tải bất kỳ file nào trên server
// Khai thác: /Middlewares/download.php?file=../Models/connection.php
//            /Middlewares/download.php?file=/etc/passwd

$file = $_GET['file'] ?? '';
if ($file) {
    $filePath = $file;
    
    // Không validate path — cho phép path traversal
    if (file_exists($filePath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo "File not found";
    }
}
