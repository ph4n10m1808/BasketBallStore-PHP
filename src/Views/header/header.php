<header class="fixed-top shadow-sm" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
    <nav class="navbar navbar-expand-sm fs-7 py-1" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <div class="container-fluid w-100 d-flex justify-content-start mx-1">
            <div class="fw-light">
                <a href="https://www.google.com/maps/place/Vietnam+-+Korea+University+of+Information+and+Communication+Technology./@15.9752982,108.250161,17z/data=!3m1!4b1!4m6!3m5!1s0x3142108997dc971f:0x1295cb3d313469c9!8m2!3d15.9752931!4d108.252355!16s%2Fg%2F1yjg80dyy" class="text-decoration-none text-muted me-3" style="font-size: 12px; transition: color 0.2s;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color=''" target="_blank"><i class="fa-solid fa-location-dot me-1" style="color: #4f46e5;"></i>Address:
                    470 Tran Dai Nghia</a>
                <a href="#" class="text-decoration-none text-muted" style="font-size: 12px; transition: color 0.2s;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color=''"><i class="fa-solid fa-phone me-1" style="color: #4f46e5;"></i>Phone:
                    0123456789</a>
            </div>
        </div>
        <div class="container-fluid w-100 d-flex justify-content-end mx-2">
            <?php
            $login = isset($_SESSION['login']);
            if (!$login) {
                echo '
                    <a href="?page=login" class="text-decoration-none me-3 fw-semibold" style="font-size: 12.5px; color: #4f46e5; transition: opacity 0.2s;" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                        <i class="fa-solid fa-user me-1"></i>
                        <span>Đăng nhập</span>
                    </a>
                    <a href="?page=register" class="text-decoration-none fw-semibold" style="font-size: 12.5px; color: #4f46e5; transition: opacity 0.2s;" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                        <i class="fa-solid fa-user-plus me-1"></i>
                        <span>Đăng kí</span>
                    </a>';
            } else {
                $userAccount = $_SESSION["user"] ?? "";
                $displayName = "";
                if ($userAccount) {
                    $displayName = $userAccount["first_name"] . " " . $userAccount["last_name"];
                }
                $authMenu = "";
                switch ($userAccount['id_auth']) {
                    case 1:
                        $authMenu = '<li><a class="dropdown-item text-success" href="admin/?mod=dashboard" target="_blank"><i class="fas fa-cog me-2" style="width:16px;"></i>Trang quản lý</a></li>';
                        break;
                    case 2:
                        $authMenu = '<li class="border"><a class="dropdown-item text-info" href="#">Trang nhân viên</a></li>';
                        break;
                }
                echo '
                        <span class="dropdown p-8 me-3 d-flex align-items-center" style="font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-user-circle me-1" style="color: #4f46e5; font-size: 16px;"></i>
                            <span class="nav-link dropdown-toggle position-relative fw-semibold" role="button" aria-expanded="false" style="padding: 4px 0; color: #1e293b;">
                              ' . $displayName . '
                            </span>
                            <ul class="dropdown-menu dropdown-content p-0 position-absolute border-0 shadow rounded-3" style="right: 0; top: 100%; margin-top: 8px;">
                                <li><a class="dropdown-item py-2" href="?page=profile"><i class="fas fa-id-card me-2" style="color:#64748b; width:16px;"></i>Tài khoản</a></li>
                                ' . $authMenu . '
                                <li><a class="dropdown-item py-2" href="?page=bill"><i class="fas fa-box-open me-2" style="color:#64748b; width:16px;"></i>Đơn hàng của bạn</a></li>
                                <li><hr class="dropdown-divider my-0"></li>
                                <li><a class="dropdown-item text-danger py-2" href="?page=logout"><i class="fas fa-sign-out-alt me-2" style="width:16px;"></i>Đăng xuất</a></li>
                            </ul>
                        </span>
                ';
            }
            ?>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg bg-transparent p-0 mx-0">
        <div class="container-fluid mx-3 py-2">
            <a class="navbar-brand fw-bold" style="color: #4f46e5; font-size: 1.5rem; letter-spacing: -0.5px;" href="?page=home"><i class="fas fa-basketball me-2"></i>BasketBallStore</a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold" style="color: #334155;">
                    <li class="nav-item position-relative d-flex align-items-center mx-2">
                        <span class="dropdown">
                            <a class="nav-link dropdown-toggle" href="?page=product&type=1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Giày bóng rổ
                        </a>
                            <ul class="dropdown-menu dropdown-content p-0 position-absolute border-0 shadow rounded-3">
                            <li><a class="dropdown-item py-2 border-bottom" href="?page=product&id=1">Nikes</a></li>
                            <li><a class="dropdown-item py-2 border-bottom" href="?page=product&id=3">Adidas</a></li>
                            <li><a class="dropdown-item py-2 border-bottom" href="?page=product&id=2">Anta</a></li>
                            <li><a class="dropdown-item py-2 border-bottom" href="?page=product&id=4">Peak</a></li>
                            <li><a class="dropdown-item py-2 border-bottom" href="?page=product&type=1">Lining</a></li>
                            <li><a class="dropdown-item py-2" href="?page=product&type=1">Other</a></li>
                        </ul>
                        </span>
                    </li>
                    <li class="nav-item position-relative mx-2">
                        <span class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Áo quần bóng rổ
                        </a>
                        <ul class="dropdown-menu dropdown-content p-0 position-absolute border-0 shadow rounded-3">
                            <li><a class="dropdown-item py-2 border-bottom" href="?page=product&id=5">Quần bóng rổ</a></li>
                            <li><a class="dropdown-item py-2" href="?page=product&id=6">Áo bóng rổ</a></li>
                        </ul>
                        </span>
                    </li>
                    <li class="nav-item position-relative mx-2">
                        <span class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Phụ kiện
                        </a>
                        <ul class="dropdown-menu dropdown-content p-0 position-absolute border-0 shadow rounded-3">
                            <li><a class="dropdown-item py-2 border-bottom" href="?page=product&id=11">Bóng rổ</a></li>
                            <li><a class="dropdown-item py-2 border-bottom" href="?page=product&id=12">Balo</a></li>
                            <li><a class="dropdown-item py-2" href="?page=product&id=10">Tất</a></li>
                        </ul>
                        </span>
                    </li>
                </ul>
                <form action="?page=search" method="post" class="d-flex align-items-center" role="search" >
                    <div class="position-relative me-3 d-flex align-items-center" style="width: 250px;">
                        <input class="form-control rounded-pill pe-5 bg-light border-0 shadow-sm w-100" type="text" placeholder="Tìm kiếm..."
                               aria-label="Search" name="keyword" style="padding: 0.6rem 1rem;">
                        <button type="submit" class="button fa-solid fa-magnifying-glass position-absolute bg-transparent border-0"
                           style="right: 15px !important; color: #64748b;"></button>
                    </div>
                    <a href="?page=cart" class="btn rounded-pill px-4 py-2 d-flex align-items-center gap-2 text-white fw-semibold shadow-sm" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fa-sharp fa-solid fa-cart-shopping"></i>
                        <span class="m-0">Giỏ hàng</span>
                    </a>
                </form>

            </div>
        </div>
    </nav>
</header>