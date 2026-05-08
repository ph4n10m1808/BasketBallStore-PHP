import requests
import json
import time

BASE_URL = 'http://localhost'
HEADERS = {
    'User-Agent': 'BizarreTester/1.0',
    # Giả sử đã có cookie session hợp lệ, người dùng có thể tự thay bằng session của họ khi test
    'Cookie': 'PHPSESSID=test_session_id'
}

print("🧪 BẮT ĐẦU KIỂM THỬ NHỮNG CASE QUÁI LẠ NHẤT (BIZARRE LOGIC TESTS) 🧪\n")

def test_add_cart(payload, description):
    print(f"[*] Đang test: {description}")
    try:
        start_time = time.time()
        res = requests.post(f"{BASE_URL}/Middlewares/addCart.php", data=payload, headers=HEADERS, timeout=5)
        elapsed = time.time() - start_time
        
        print(f"   -> Status: {res.status_code} (Mất {elapsed:.2f}s)")
        # Cắt ngắn phản hồi để tránh rác màn hình
        short_res = res.text[:100].replace('\n', ' ')
        print(f"   -> Phản hồi: {short_res}...\n")
    except Exception as e:
        print(f"   -> LỖI CONNECTION / TIMEOUT: {e}\n")

# Danh sách các case dị thường cho tính năng Thêm vào giỏ hàng
bizarre_cases = [
    (
        {"id": "1", "size": "42", "quantity": "9999999999999999999999999999999", "typeSize": "1"},
        "Số lượng là số nguyên khổng lồ (Kiểm tra Integer Overflow)"
    ),
    (
        {"id": "1", "size": "42", "quantity": "1.0000000000001", "typeSize": "1"},
        "Số lượng là số thập phân (Phá vỡ cấu trúc int, ví dụ mua 1.5 đôi giày)"
    ),
    (
        {"id": "1", "size": "42", "quantity": "-999999", "typeSize": "1"},
        "Số lượng âm cực lớn (Thao túng tổng tiền về số âm)"
    ),
    (
        {"id": "1", "size": "42", "quantity": "0x1A", "typeSize": "1"},
        "Số lượng viết dạng Hexadecimal (0x1A)"
    ),
    (
        {"id": "1", "size": "42", "quantity": "DROP TABLE users;", "typeSize": "1"},
        "Số lượng chứa lệnh SQL Injection"
    ),
    (
        {"id": "1", "size": "42", "quantity": "<script>alert('XSS')</script>", "typeSize": "1"},
        "Số lượng chứa mã độc XSS"
    ),
    (
        # Gửi 'size' dưới dạng mảng thay vì chuỗi đơn
        {"id": "1", "size[]": ["42", "43", "44"], "quantity": "1", "typeSize": "1"},
        "Truyền tham số size dưới dạng MẢNG (Gây lỗi PHP Warning: Array to string conversion)"
    ),
    (
        {"id": "1", "size": "42", "quantity": "1", "typeSize": "1", "unexpected_param": "A" * 10000},
        "Gửi kèm tham số rác khổng lồ (Gây cạn kiệt bộ nhớ RAM)"
    ),
]

for payload, desc in bizarre_cases:
    test_add_cart(payload, desc)


def test_login(username, password, description):
    print(f"[*] Đang test Login: {description}")
    try:
        start_time = time.time()
        res = requests.post(f"{BASE_URL}/Middlewares/login.php", data={"username": username, "password": password}, headers=HEADERS, timeout=5)
        elapsed = time.time() - start_time
        
        print(f"   -> Status: {res.status_code} (Mất {elapsed:.2f}s)")
        short_res = res.text[:100].replace('\n', ' ')
        print(f"   -> Phản hồi: {short_res}...\n")
    except Exception as e:
        print(f"   -> LỖI CONNECTION / TIMEOUT: {e}\n")

# Danh sách các case dị thường cho tính năng Đăng nhập
login_cases = [
    ("' OR 1=1 --", "anything", "SQL Injection cơ bản vào Username (Bypass auth)"),
    ("admin", "' OR '1'='1", "SQL Injection vào Password (Bypass auth)"),
    ("a" * 10000, "b" * 10000, "Chuỗi đăng nhập khổng lồ (Gây Hash DOS)"),
    ("admin\x00", "password", "Ký tự Null Byte (Bypass bộ lọc chuỗi)"),
    ("'", "'", "Ký tự nháy đơn (Kiểm tra xem hệ thống có báo lỗi SQL Syntax không)")
]

for uname, pwd, desc in login_cases:
    test_login(uname, pwd, desc)

print("✅ Đã hoàn thành các case quái lạ. Kiểm tra log phía trên để xem hệ thống crash hay xử lý an toàn.")
