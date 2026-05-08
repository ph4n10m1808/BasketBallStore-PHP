#!/bin/bash
# /home/ph4n10m/BasketBallStore-PHP/test/nuke_stress_test.sh

URL="http://localhost:80/"

echo "=========================================================="
echo "☢️ BẮT ĐẦU NUKE STRESS TEST (DDoS SIMULATION) ☢️"
echo "Cảnh báo: Server và Database có thể bị sập (Crash/OOM)!"
echo "=========================================================="

echo "[1] Phóng 5 luồng Apache Bench song song (Multi-threaded HTTP Flood)"
echo "Mục tiêu: Đẩy CPU Web Server lên 100% và làm cạn kiệt số lượng kết nối (Max Clients)."
for i in {1..5}; do
    ab -n 5000 -c 200 -k $URL > /dev/null 2>&1 &
done
echo "Đang chờ các luồng background bắn phá..."
wait
echo "-> Hoàn thành đợt 1."

echo "----------------------------------------------------------"
echo "[2] Đánh bom diện rộng vào trang Danh Mục Sản Phẩm"
echo "Mục tiêu: Gây thắt nút cổ chai (Bottleneck) truy xuất MySQL (truy vấn DB nặng)."
ab -n 10000 -c 500 -k "${URL}?page=product&type=1"

echo "----------------------------------------------------------"
echo "[3] Slow HTTP POST (Mô phỏng chôn chân Server)"
echo "Mục tiêu: Buộc Server phải giữ các luồng xử lý cực lâu."
# Gửi POST với payload cực lớn nhưng ngắt quãng
LONG_PAYLOAD=$(printf 'A%.0s' {1..20000})
echo "data=${LONG_PAYLOAD}" > payload.txt
ab -n 5000 -c 300 -p payload.txt -T "application/x-www-form-urlencoded" "${URL}?page=search"
rm payload.txt

echo "=========================================================="
echo "☢️ KẾT THÚC NUKE STRESS TEST ☢️"
echo "Hãy kiểm tra logs của docker (docker compose logs -f) xem có lỗi 'Aborted connection' hay 'Out of memory' không!"
