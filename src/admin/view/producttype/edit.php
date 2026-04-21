<?php if (isset($_COOKIE['msg'])) { ?>
    <div class="alert alert-success">
        <strong>Thông báo</strong> <?= $_COOKIE['msg'] ?>
    </div>
<?php } ?>
<hr>
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <?php if (isset($_COOKIE['msg'])) { ?>
        <div class="alert alert-warning">
            <strong>Thông báo</strong> <?= $_COOKIE['msg'] ?>
        </div>
    <?php } ?>
    <form action="?mod=productType&act=update&id=<?= $_GET['id'] ?>" method="POST" role="form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="">ID</label>
            <input type="text" class="form-control" id="" placeholder="" disabled value="<?= $productType['id_product_type'] ?>">
        </div>
        <div class="form-group">
            <label for="">Name Product Type</label>
            <input type="text" class="form-control" id="" placeholder="" name="name_pt" value="<?= $productType['name_pt'] ?>">
        </div>
        <div class="form-group">
            <label for="">Logo</label>
            <img style="max-width: 150px" src="../public/<?= $productType['logo_pt']?>" alt="">
            <input type="file" class="form-control" id="" placeholder="" name="logo_pt">
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea name="description" id="" class="form-control" cols="30" rows="10"><?= $productType['description'] ?></textarea>
        </div>
        <div class="form-group">
            <label for="cars">Danh mục: </label>
            <select id="" name="id_category" class="form-control">
                <?php foreach ($categoryList as $category) { ?>
                    <option value="<?= $category['id_category'] ?>" <?= $category['id_category'] === $productType['id_category'] ? "selected" : "" ?>>
                        <?= $category['name_category'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</table>