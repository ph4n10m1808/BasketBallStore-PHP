<div class="mt-4">
    <div class="d-flex justify-content-between flex-wrap">
        <a href="?page=product&type=3" class="gray-darker fw-bold fs-4">PHỤ KIỆN</a>
        <ul class="d-flex" style="flex-wrap: nowrap">
            <?php foreach ($listProductTypeAccessories as $productType) { ?>
                <li class="me-3"><a href="?page=product&id=<?= $productType["id_product_type"] ?>"><?= $productType['name_pt'] ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div id="latest-product-container" class="bg-white row compare mt-2" style="margin: 0 2px">
        <div class="owl-carousel owl-theme owl-gbr owl-gbr-hot mrg-normal">
            <?php foreach ($data_newest_accessories as $product) { ?>
                <div class="item mt-3 position-relative">
                    <div style="position: relative;">
                        <a class="product_img_link pro_img_home" title="<?php echo $product['name_product'] ?>"
                           href="?page=detail&id=<?= $product['id_product'] ?>">
                            <img src="assets/<?php echo $product['main_image'] ?>" alt="<?php echo $product['name_product'] ?>"
                                 class="img-responsive image-new-product">
                            <div class="b_dis_home">
                            <span class="discounts">
                                <strong>
                                    <i class="fa-solid fa-heart"></i> <?php echo $product['name_sale'];?> </strong>
                            </span>
                            </div>
                        </a>
                    </div>
                    <div class="caption description-container">
                        <a class="gray-darker nodeco fw-semibold" title="<?php echo $product['name_product'] ?>"
                                                     href="?page=detail&id=<?= $product['id_product'] ?>">
                                    <?php echo $product['title_product'] ?>
                        </a>
                        <div class="gray-light f11 line-height-normal" style="color: #999999; font-size: 12px"><?php echo $product['p_type_name'];?></div>
                        <div class="content_price">
                            <span class="price text-danger"> <span><?php echo number_format($product['d_price']);?> ₫</span></span>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-decoration-line-through fs-7" style="color: #999999"><?php echo number_format($product['price']) ?><span
                                    class="f13">₫</span></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>