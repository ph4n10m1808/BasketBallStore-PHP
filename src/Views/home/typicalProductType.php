<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    foreach ($data_typical_products as $product) {
        $url_id = !empty($product['url_event']) ? $product['url_event'] : $product['id_t_product'];
        ?>
        <div class="col">
            <div class="card border-0">
                <a href="?page=product&id=<?= $url_id ?>" class="nodeco">
                    <img src="public/<?php echo $product['url_image'] ?>" class="card-img-top" alt="<?php echo $product['title'] ?>"/>
                    <div class="card-body text-center">
                        <span class="fs-5 fw-bold gray-darker nodeco">
                            <?php echo $product['title'] ?>
                        </span>
                        <p class="card-text" style="min-height: 120px !important;">
                            <?php echo $product['description'] ?>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    <?php } ?>
</div>