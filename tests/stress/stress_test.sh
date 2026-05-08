#!/bin/bash
# /home/ph4n10m/BasketBallStore-PHP/test/stress_test.sh
# Requires: sudo apt-get install apache2-utils

URL="http://localhost:80/"

echo "🔥 BẮT ĐẦU BIZARRE STRESS TEST 🔥"
echo "Lưu ý: Các bài test này cố tình đẩy server đến giới hạn bằng các luồng dữ liệu dị thường."
echo "--------------------------------------------------------"

echo "1. 🌊 Cơn bão truy cập trang chủ (1000 request, 100 kết nối đồng thời)"
ab -n 1000 -c 100 $URL
echo "--------------------------------------------------------"

echo "2. 🐛 URL khổng lồ (Buffer Overflow Test)"
# Tạo một chuỗi chữ A lặp lại 5000 lần
LONG_STR=$(printf 'A%.0s' {1..5000})
ab -n 500 -c 50 "${URL}?page=search&keyword=${LONG_STR}"
echo "--------------------------------------------------------"

echo "3. 📁 Stress test với payload Path Traversal"
# Liên tục đọc file hệ thống /etc/passwd qua lỗ hổng LFI (nếu có)
ab -n 500 -c 50 "${URL}?page=../../../../../../../../etc/passwd"
echo "--------------------------------------------------------"

echo "4. 💥 SQL Injection Stress (Phá huỷ Database connection)"
# Bắn truy vấn Sleep() cực nặng vào database để gây treo DB
ENCODED_SQL="%27%20OR%20SLEEP%285%29--%20"
ab -n 100 -c 20 "${URL}?page=product&id=${ENCODED_SQL}"
echo "--------------------------------------------------------"

echo "✅ HOÀN THÀNH BIZARRE STRESS TEST"
