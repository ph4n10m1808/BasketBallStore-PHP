<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    foreach ($data_typical_products as $product) {
        $url_id = !empty($product['url_event']) ? $product['url_event'] : $product['id_t_product'];
        ?>
        <div class="col">
            <div class="card border-0 h-100" style="border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 35px rgba(79, 70, 229, 0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.05)';">
                <a href="?page=product&id=<?= $url_id ?>" class="nodeco text-decoration-none d-flex flex-column h-100">
                    <img src="assets/<?php echo $product['url_image'] ?>" class="card-img-top" alt="<?php echo $product['title'] ?>" style="height: 250px; object-fit: cover; border-bottom: 3px solid #4f46e5;"/>
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <h5 class="fs-4 fw-bold mb-3" style="color: #1e293b;">
                            <?php echo $product['title'] ?>
                        </h5>
                        <p class="card-text text-muted mb-4" style="flex-grow: 1; line-height: 1.6;">
                            <?php echo $product['description'] ?>
                        </p>
                        <div class="mt-auto">
                            <span class="btn rounded-pill px-4 py-2 text-white fw-semibold" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">Khám phá ngay <i class="fas fa-arrow-right ms-2"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php } ?>
</div>