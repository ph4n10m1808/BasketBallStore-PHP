<?php
//    $totalProducts = 0;
//    foreach ($listProducts as $each){
//        $totalProducts += $each['d_price'];
//    }
//    $total = $totalProducts + 30000;
//    $_SESSION['totalCart'] = $total;
?>
<div>
    <a class="text-danger fw-semibold text-decoration-none mb-3 d-inline-block" id="clear-cart" href="?page=cart&act=clear"><i class="fa-solid fa-trash-can me-1"></i>Xoá toàn bộ giỏ hàng</a>
    <ul class="list-group list-group-light">
        <li class="list-group-item d-flex justify-content-between align-items-center mt-4 mb-4">

            <div class="d-flex align-items-center" style="width: 100% !important;">
                <div class="ms-3 w-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="fw-bold m-0">Tổng giá sản phẩm: </p>
                        <p class="fw-bold m-0 float-end" id="total-cost-product"><?= number_format($_SESSION['totalCart'] - 30000) ?> đ</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="fw-bold ">Phí vận chuyển: </p>
                        <p class="fw-bold float-end"><?= number_format(30000) ?> đ</p>
                    </div>
                    <hr class="m-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="fw-bold">Tổng tiền: </p>
                        <p class="fw-bold text-danger float-end" id="total-cost"><?= number_format($_SESSION['totalCart']) ?> đ</p>
                    </div>
                    <?php if (isset($_SESSION['user'])) { ?>
                        <?php if ($_SESSION['user']['address']) { ?>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold">Address:</p>
                                <p class="fw-bold float-end"><?= $_SESSION['user']['address'] ?></p>
                            </div>
                            <a href="?page=cart&act=pay" class="btn btn-primary float-end" style="border-radius: 10px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);" onclick="alert('Thanh toán thành công')"><i class="fa-solid fa-credit-card me-2"></i>Thanh toán</a>
                        <?php } else { ?>
                            <div class="float-end">
                                <p class="text-danger">Bạn cần cập nhập địa chỉ để tiến hành thanh toán</p>
                                <a href="?page=profile" class="float-end">Cập nhập ở đây</a>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="float-end d-flex align-content-center justify-content-center">
                            <p class="text-danger fw-bold m-0 m-2">Bạn cần đăng nhập để tiến hành thanh toán</p>
                            <a href="?page=login&act=pay" class="btn btn-primary" style="border-radius: 10px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none;"><i class="fa-solid fa-right-to-bracket me-2"></i>Đăng nhập</a>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </li>


        <?php foreach ($cartItems as $item) { ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center" style="width: 100% !important;">
                    <a href="?page=detail&id=<?= $item['id_product'] ?>">
                        <img src="assets/<?= $item['main_image'] ?>" alt="" style="width: 100px; height: 100px; object-fit: cover;"
                             class="rounded-3 shadow-sm"/>
                    </a>

                    <div class="ms-3">
                        <a href="?page=detail&id=<?= $item['id_product'] ?>">
                            <p class="fw-bold mb-1"><?= $item['title_product'] ?></p>
                        </a>
                        <?php if ($item['size']) { ?>
                            <p class="text-muted mb-0"><strong>Size: </strong><?= $item["size"] ?></p>
                        <?php } ?>
                        <div class="text-muted mb-0">
                            <div class="d-inline-block mx-auto">
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary btn-number"
                                            style="margin: 4px 0"
                                            disabled="disabled">
                                        Quantity:
                                    </button>
                                    <span class="input-group-prepend" style="margin: 4px 0">
                                <button type="button" id="minus-<?= $item['id_product'].'-'.$item['size'] ?>" class="btn btn-outline-secondary btn-number btn-update-cart"
                                        data-type="minus" data-field="<?= 'quant_'. $item['id_product'].$item['size'] .'[1]' ?>">
                                    <span class="fa fa-minus"></span>
                                </button>
                            </span>
                                    <input type="text" disabled style="margin: 4px 0;width: 44px;padding: 0; max-width: 40px;"
                                           name="<?= 'quant_'. $item['id_product'].$item['size'] .'[1]' ?>"
                                           class="form-control input-number text-center"
                                           value="<?= $item['quantity'] ?>" min="1"
                                           max=<?= $item['restQuantity']?> id="quantity-cart-<?= $item['id_product'] ?>">
                                    <span class="input-group-append" style="margin: 4px 0">
                                <button type="button" id="plus-<?= $item['id_product'].'-'.$item['size']  ?>" class="btn btn-outline-secondary btn-number btn-update-cart" data-type="plus"
                                        data-field="<?= 'quant_'. $item['id_product'].$item['size'] .'[1]' ?>">
                                    <span class="fa fa-plus"></span>
                                </button>
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="badge rounded-pill badge-success d-flex flex-column align-items-end justify-content-center bg-transparent" style="color: black; font-size: 13px">
                    <p class="line-through mb-1 text-muted"><del><?= number_format($item['price']) ?> ₫</del></p>
                    <span class="discounts position-static mb-2" style="transform: none;"><strong><span class="fa-solid fa-heart"
                                                                                         aria-hidden="true"></span> <?php echo $item['name_sale']; ?> </strong></span>
                    <p class="text-danger fw-bold fs-6 mb-3"><?= number_format($item['d_price']) ?> ₫</p>
                    <button class="border-0 bg-transparent text-danger fw-semibold button-delete-item d-flex align-items-center"
                            value="<?= $item['id_product'] ?>" data-size="<?= $item['size'] ?? '' ?>"><i class="fa-solid fa-trash-can me-1"></i>Xoá</button>
                </span>

            </li>
        <?php } ?>

    </ul>
</div>