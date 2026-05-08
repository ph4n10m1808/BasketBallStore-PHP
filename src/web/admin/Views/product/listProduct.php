<a href="?mod=product&act=add" class="btn btn-primary">Thêm mới</a>
<hr>
<div class="table-responsive">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Image</th>
        <th>#</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Image</th>
        <th>#</th>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach ($productList as $product) { ?>
        <tr>
            <td><?= $product["id_product"] ?></td>
            <td style="max-width: 300px"><?= $product["name_product"] ?></td>
            <td><?= number_format($product["price"]) . " VND" ?></td>
            <td><?= $product["quantity"] ?></td>
            <td><img style="max-width: 100px" src="assets/<?= $product["main_image"] ?>" alt=""></td>
            <td>
                <a href="?mod=product&act=detail&id=<?= $product['id_product'] ?>" class="btn btn-success">Xem</a>
                <a href="?mod=product&act=edit&id=<?= $product['id_product'] ?>" class="btn btn-warning">Sửa</a>
                <a href="?mod=product&act=delete&id=<?= $product['id_product'] ?>" onclick="return confirm('Bạn có thật sự muốn xóa ?');" class="btn btn-danger">Xóa</a>

                <?php if (isset($_SESSION['isLogin_Admin']) && $_SESSION['isLogin_Admin'] == true) { ?>

                <?php }?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>