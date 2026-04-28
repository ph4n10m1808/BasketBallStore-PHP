<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <form action="?mod=product&act=update&id=<?= $_GET['id'] ?>" method="POST" role="form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="">ID</label>
            <input type="text" class="form-control" id="" placeholder="" disabled value="<?= $detailProduct['id_product'] ?>">
        </div>
        <div class="form-group">
            <label for="cars">Danh mục: </label>
            <select id="" name="id_category" class="form-control">
                <?php foreach ($categoryList as $category) { ?>
                    <option value="<?= $category['id_category'] ?>"  <?= $category['id_category'] === $detailProduct['id_category'] ? "selected" : "" ?>><?= $category['name_category'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="cars">Loại sản phẩm: </label>
            <select id="" name="id_product_type" class="form-control">
                <?php foreach ($productTypeList as $productType) { ?>
                    <option value="<?= $productType['id_product_type'] ?>" <?= $productType['id_product_type'] === $detailProduct['id_product_type'] ? "selected" : "" ?>><?= $productType['name_pt'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="">Tiêu đề sản phẩm</label>
            <input type="text" class="form-control" id="" placeholder="" name="title_product" value="<?= $detailProduct['title_product'] ?>">
        </div>
        <div class="form-group">
            <label for="">Tên sản phẩm</label>
            <input type="text" class="form-control" id="" placeholder="" name="name_product" value="<?= $detailProduct['name_product'] ?>">
        </div>
        <div class="form-group">
            <label for="">Đơn giá</label>
            <input type="text" class="form-control" id="" placeholder="" name="price" value="<?= $detailProduct['price'] ?>">
        </div>
        <div class="form-group">
            <label for="">Số lượng</label>
            <input type="text" class="form-control" id="" placeholder="" name="quantity" value="<?= $detailProduct['quantity'] ?>">
        </div>
        <div class="form-group">
            <label for="">Hình ảnh chính </label>
            <img style="max-width: 100px" src="../public/<?= $detailProduct['main_image'] ?>" alt="">
            <input type="file" class="form-control" id="" placeholder="" name="main_image">
            <input type="text" hidden name="old_main_image" value="<?= $detailProduct['main_image'] ?>">
        </div>
        <div class="form-group">
            <label for="">Hình ảnh 1 </label>
            <img style="max-width: 100px" src="../public/<?= $detailProduct['image1'] ?>" alt="">
            <input type="file" class="form-control" id="" placeholder="" name="image1">
            <input type="text" hidden name="old_image1" value="<?= $detailProduct['image1'] ?>">
        </div>
        <div class="form-group">
            <label for="">Hình ảnh 2</label>
            <img style="max-width: 100px" src="../public/<?= $detailProduct['image2'] ?>" alt="">
            <input type="file" class="form-control" id="" placeholder="" name="image2">
            <input type="text" hidden name="old_image2" value="<?= $detailProduct['image2'] ?>">

        </div>
        <div class="form-group">
            <label for="">Hình ảnh 3</label>
            <img style="max-width: 100px" src="../public/<?= $detailProduct['image3'] ?>" alt="">
            <input type="file" class="form-control" id="" placeholder="" name="image3">
            <input type="text" hidden name="old_image3" value="<?= $detailProduct['image3'] ?>">

        </div>
        <div class="form-group">
            <label for="">Hình ảnh 4</label>
            <img style="max-width: 100px" src="../public/<?= $detailProduct['image4'] ?>" alt="">
            <input type="file" class="form-control" id="" placeholder="" name="image4">
            <input type="text" hidden name="old_image4" value="<?= $detailProduct['image4'] ?>">

        </div>
        <div class="form-group">
            <label for="">Size</label>
            <input type="text" class="form-control" id="" placeholder="" name="size" value="<?= $detailProduct['size'] ?>">
        </div>
        <div class="form-group">
            <label for="cars">Mã khuyến mãi </label>
            <select id="" name="id_promotion" class="form-control">
                <?php foreach ($promotionList as $promotion) { ?>
                    <option value="<?= $promotion['id_promotion']?>"  <?= $promotion['id_promotion'] === $detailProduct['id_promotion'] ? "selected" : "" ?>>
                        <?= $promotion['name_promotion'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <label for="">Mô tả</label>
        <div class="form-group">
            <textarea class="form-control" id="summernote" placeholder="" name="description"><?= $detailProduct['description'] ?></textarea>
        </div>
        <div class="form-group">
            <label for="">Trạng thái</label>
            <input type="checkbox" id="" placeholder="" value="1" name="status" <?= (isset($detailProduct['status']) && $detailProduct['status'] >= 1) ? "checked" : "" ?>><em>(Check cho phép hiện thị sản phẩm)</em>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    </script>
</table>