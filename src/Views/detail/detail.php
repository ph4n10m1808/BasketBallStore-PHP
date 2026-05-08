<?php $_SESSION['product'] = $productDetail ?>
<input type="text" hidden id="size-selected" />
<input type="text" hidden id="id-product" value="<?= $_GET['id'] ?? '' ?>">
<input type="text" hidden id="type-product" value="<?= $productDetail['size'] ?>">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
        <li class="breadcrumb-item"><a class="text-dark"
                href="?page=product&type=<?= $category['id_category'] ?>"><?= $category['name_category'] ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Product</li>
    </ol>
</nav>
<div class="d-flex justify-content-between">
    <h4 class="d-flex"><?= $productDetail['title_product'] ?></h4>
    <p class="text-muted"><?= $productType ?></p>
</div>
<div class="d-flex">
    <div class="d-flex">
        <div class="d-flex flex-column gap-2 me-3">
            <img class="rounded-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid transparent; cursor: pointer; transition: all 0.2s ease;"
                src="public/<?= $productDetail['image1'] ?>" onclick="changeImage(this.getAttribute('src'))" alt="" onmouseover="this.style.borderColor='#4f46e5'" onmouseout="this.style.borderColor='transparent'">
            <img class="rounded-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid transparent; cursor: pointer; transition: all 0.2s ease;"
                src="public/<?= $productDetail['image2'] ?>" onclick="changeImage(this.getAttribute('src'))" alt="" onmouseover="this.style.borderColor='#4f46e5'" onmouseout="this.style.borderColor='transparent'">
            <img class="rounded-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid transparent; cursor: pointer; transition: all 0.2s ease;"
                src="public/<?= $productDetail['image3'] ?>" onclick="changeImage(this.getAttribute('src'))" alt="" onmouseover="this.style.borderColor='#4f46e5'" onmouseout="this.style.borderColor='transparent'">
            <img class="rounded-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid transparent; cursor: pointer; transition: all 0.2s ease;"
                src="public/<?= $productDetail['image4'] ?>" onclick="changeImage(this.getAttribute('src'))" alt="" onmouseover="this.style.borderColor='#4f46e5'" onmouseout="this.style.borderColor='transparent'">
        </div>
        <div>
            <img id="image-detail" class="rounded-4 shadow-sm" style="width: 100%; max-width: 500px; aspect-ratio: 1/1; object-fit: cover;"
                src="public/<?= $productDetail['image1'] ?>" alt="">
        </div>
    </div>
    <div style="margin-left: 20px">
        <hr style="margin-top: 4px">
        <h3><?= $productDetail['name_product'] ?></h3>
        <p>
            <strong class="line-through"><?= number_format($productDetail['price']) ?></strong> <strong>₫</strong>
            <?php if (isset($productDetail['d_price']) && $productDetail['d_price'] != $productDetail['price']) { ?>
                <span style="margin: 0 10px" class="label label-danger f12 border-0"><span class="fa-solid fa-heart"
                        aria-hidden="true"></span> <?= $productDetail['name_sale'] ?? 'Sale' ?> </span>
                Remaining: <strong><?= number_format($productDetail['d_price']) ?></strong> <strong>₫</strong>
            <?php } else { ?>
                Remaining: <strong><?= number_format($productDetail['price']) ?></strong> <strong>₫</strong>
            <?php } ?>
        </p>
        <hr>
        <!--        -->
        <?php $sizeList = explode("/", $productDetail['size']); ?>
        <?= $sizeList[0] ? "<h4>Size:</h4>" : "" ?>
        <ul style="display: flex; list-style: none; flex-wrap: wrap;" class="p-0">
            <?php
            if ($sizeList[0]) {
                ?>

            <?php foreach ($sizeList as $sizeValue) {
                ?>
            <li class="text-center size-button rounded-3" onclick="selectSize(this)"
                style="width: 60px;background-color: #f8fafc; border: 2px solid #e2e8f0; padding: 4px 8px; margin: 4px; cursor: pointer; height: 60px; transition: all 0.2s ease;" onmouseover="this.style.borderColor='#818cf8'" onmouseout="this.style.borderColor='#e2e8f0'">
                <a style="font-size: 14px; text-align: center; color: #1e293b; text-decoration: none;"><strong><?= $sizeValue ?></strong>
                    <?php if ($productDetail['id_category'] === "1") { ?>
                    <p class="text-muted m-0 fs-7"><strong><?= ((float) $sizeValue) - 33.5 ?></strong> <span
                            style="font-size: 8px;">US</span></p>
                    <?php } ?>
                </a>
            </li>
            <?php }
            } ?>
        </ul>


        <div id="tab" class="d-flex w-100 mt-3 mb-3 gap-3" data-toggle="buttons">
            <a href="#buy2" id="type_buy2" class="btn flex-fill text-start" style="border-radius: 12px; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #1e293b; padding: 12px;" data-toggle="tab">
                <div><strong class="f13">GIAO HÀNG COD <i class="fa-solid fa-truck-fast text-success ms-1"></i></strong></div>
                <div class="fs-7 fw-semibold text-muted mt-1">Nội thành Đà Nẵng</div>
            </a>
            <a href="#buy1" id="type_buy1" class="btn flex-fill text-start" style="border-radius: 12px; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #1e293b; padding: 12px;" data-toggle="tab">
                <div><strong class="f13">CHUYỂN KHOẢN <i class="fa-solid fa-money-bill-transfer text-primary ms-1"></i></strong></div>
                <div class="fs-7 fw-semibold text-muted mt-1">Ship toàn quốc</div>
            </a>
        </div>
        <hr>
        <div>
            <p>Số sản phẩm còn lại: <span id="quantity-product"><?= $productDetail['quantity'] ?></span></p>
        </div>
        <div class="container">
            <div class="d-inline-block mx-auto">

                <div class="input-group">
                    <button type="button" class="btn btn-outline-secondary btn-number" style="margin: 4px 0"
                        disabled="disabled">
                        Quantity:
                    </button>
                    <span class="input-group-prepend" style="margin: 4px 0">
                        <button type="button" class="btn btn-outline-secondary btn-number" disabled="disabled"
                            data-type="minus" data-field="quant[1]">
                            <span class="fa fa-minus"></span>
                        </button>
                    </span>
                    <input type="text" style="margin: 4px 0;width: 40px; max-width: 40px;" name="quant[1]"
                        class="form-control input-number text-center" value="1" min="1" max=<?= $productDetail['quantity']?> id="quantity-cart">
                    <span class="input-group-append" style="margin: 4px 0">
                        <button type="button" class="btn btn-outline-secondary btn-number" data-type="plus"
                            data-field="quant[1]" onclick="console.log(document.querySelector('#quantity-cart').value)">
                            <span class="fa fa-plus"></span>
                        </button>
                    </span>
                </div>
            </div>
            <button type="submit" name="submit-buy" id="submit-buy" value="submit-buy"
                class="btn btn-primary btn-lg px-4 fw-semibold ms-2" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4); background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none;">
                <i class="fa-solid fa-cart-plus me-2"></i>
                <span>Thêm vào giỏ hàng</span>
            </button>
            <p class="text-danger fw-semibold" id="alert-cart"></p>
        </div>

        <!--        -->
    </div>
</div>
<hr>
<div class="text-center">
    <h4 class="text-center"><?= $productDetail['name_product'] ?></h4>
    <p class="text-center" style="color: #999999"><?= $productType ?></p>
    <div style="margin-left: 8rem; text-align: left !important;">
        <p><strong>Description: </strong><?= $productDetail['description'] ?></p>
        <p><strong>Reviews: </strong><?= $productDetail['n_reviews'] ?></p>
        <p><strong>Stars: </strong><?= $productDetail['n_stars'] ?></p>
        <p><strong>Some pictures: </strong></p>
    </div>
    <ul style="list-style: none; display: flex; justify-content: center; flex-wrap: wrap; padding: 0">
        <li>
            <img class="mt-2 mb-2" style="width: 90%" src="public/<?= $productDetail['image1'] ?>" alt="">
            <p class="text-center">Image 1</p>
        </li>
        <li>
            <img class="mt-2 mb-2" style="width: 90%" src="public/<?= $productDetail['image2'] ?>" alt="">
            <p class="text-center">Image 2</p>
        </li>
        <li>
            <img class="mt-2 mb-2" style="width: 90%" src="public/<?= $productDetail['image3'] ?>" alt="">
            <p class="text-center">Image 3</p>

        </li>
        <li>
            <img class="mt-2 mb-2" style="width: 90%" src="public/<?= $productDetail['image4'] ?>" alt="">
            <p class="text-center">Image 4</p>
        </li>
    </ul>
</div>

<hr>

<div class="d-flex justify-content-center flex-wrap">
    <h4 class="text-center">Sản phẩm liên quan</h4>
    <div class="mt-4">
        <div class="bg-white mt-2">
            <ul class="gridpro d-flex flex-wrap">
                <?php foreach ($relatedProducts as $product) { ?>
                <li class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-gr grid li-normal">
                    <a class="product_img_link pro_img_home gray-darker nodeco " title="<?= $product['title_product'] ?>"
                        href="?page=detail&id=<?= $product['id_product'] ?>">
                        <img src="public/<?php echo $product['main_image'] ?>" alt="<?= $product['title_product'] ?>"
                            class="img-responsive front">
                        <div class="b_dis_home">
                            <span class="discounts"><strong><span class="fa-solid fa-heart" aria-hidden="true"></span>
                                    <?php echo $product['name_sale']; ?> </strong></span>
                        </div>
                        <div class="caption padpro">
                            <h4 class="f13 nomargin"><strong><?php echo $product['title_product'] ?></strong></h4>
                            <div class="gray-light f11 line-height-normal mb-0"><?php echo $product['p_type_name']; ?>
                            </div>
                            <div class="content_price">
                                <span class="price"><?php echo number_format($product['d_price']); ?> ₫</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span
                                    class="gray-light line-through f13"><?php echo number_format($product['price']) ?>
                                    ₫</span>
                            </div>
                        </div>
                    </a>
                </li>

                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<hr>
<!-- [VULN] Stored XSS: bình luận hiển thị bằng innerHTML — không escape HTML -->
<div class="container mt-4">
    <h4 class="text-center">Đánh giá sản phẩm</h4>
    
    <?php if (isset($_SESSION['login']) && $_SESSION['login']) { ?>
    <div class="mb-3">
        <textarea id="review-comment" class="form-control" rows="3" placeholder="Viết bình luận..."></textarea>
        <button id="submit-review" class="btn btn-primary mt-2" onclick="submitReview()">Gửi đánh giá</button>
    </div>
    <?php } else { ?>
    <p class="text-muted text-center"><a href="?page=login">Đăng nhập</a> để viết bình luận</p>
    <?php } ?>
    
    <div id="reviews-container"></div>
</div>

<script>
function submitReview() {
    const comment = document.getElementById('review-comment').value;
    const idProduct = document.getElementById('id-product').value;
    
    fetch('Middlewares/review.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=add&id_product=${idProduct}&comment=${encodeURIComponent(comment)}`
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('review-comment').value = '';
        loadReviews();
    });
}

function loadReviews() {
    const idProduct = document.getElementById('id-product').value;
    fetch(`Middlewares/review.php?action=list&id_product=${idProduct}`)
    .then(r => r.json())
    .then(reviews => {
        const container = document.getElementById('reviews-container');
        container.innerHTML = '';
        reviews.forEach(review => {
            // [VULN] Stored XSS: innerHTML render trực tiếp comment từ DB không escape
            container.innerHTML += `
                <div class="card mb-2">
                    <div class="card-body">
                        <strong>${review.username}</strong> <small class="text-muted">${review.timestamp}</small>
                        <p class="mt-1 mb-0">${review.comment}</p>
                    </div>
                </div>`;
        });
    });
}

// Load reviews khi trang detail được mở
document.addEventListener('DOMContentLoaded', loadReviews);
</script>