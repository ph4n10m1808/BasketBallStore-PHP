<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    foreach ($data_typical_products as $each) {
        $url_id = !empty($each['url_event']) ? $each['url_event'] : $each['id_t_product'];
        ?>
        <div class="col">
            <div class="card border-0">
                <a href="?page=product&id=<?= $url_id ?>" class="nodeco">
                    <img src="public/<?php echo $each['url_image'] ?>" class="card-img-top" alt="<?php echo $each['title'] ?>"/>
                    <div class="card-body text-center">
                        <span class="fs-5 fw-bold gray-darker nodeco">
                            <?php echo $each['title'] ?>
                        </span>
                        <p class="card-text" style="min-height: 120px !important;">
                            <?php echo $each['description'] ?>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    <?php } ?>
</div>