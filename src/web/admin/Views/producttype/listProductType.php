<a href="?mod=productType&act=add" class="btn btn-primary">Thêm mới</a>
<hr>
<div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Logo</th>
            <th>#</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Logo</th>
            <th>#</th>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($productTypeList as $productType) { ?>
            <tr>
                <td><?= $productType["id_product_type"] ?></td>
                <td><?= $productType["name_pt"] ?></td>
                <td><?= $productType["description"]?></td>
                <td class="text-center"><img style="max-width: 50px" src="assets/<?= $productType["logo_pt"] ?>" alt=""></td>
                <td>
                    <a href="?mod=productType&act=detail&id=<?= $productType['id_product_type'] ?>" class="btn btn-success">Xem</a>
                    <a href="?mod=productType&act=edit&id=<?= $productType['id_product_type'] ?>" class="btn btn-warning">Sửa</a>
                    <a href="?mod=productType&act=delete&id=<?= $productType['id_product_type'] ?>" onclick="return confirm('Bạn có thật sự muốn xóa ?');" class="btn btn-danger">Xóa</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>