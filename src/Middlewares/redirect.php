<?php
// [VULN] HTTP Header Injection: tham số redirect chèn trực tiếp vào header()
// Khai thác: /Middlewares/redirect.php?url=http://evil.com%0d%0aSet-Cookie:%20admin=true
// CRLF injection → thêm header tùy ý, set cookie giả mạo

$url = $_GET['url'] ?? '?page=home';

// Không validate, không filter CRLF (\r\n)
header("Location: " . $url);
exit;
