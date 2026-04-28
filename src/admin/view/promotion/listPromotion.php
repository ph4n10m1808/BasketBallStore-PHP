<a href="?mod=promotion&act=add" class="btn btn-primary">Thêm mới</a>
<hr>
<div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name Promotion</th>
            <th>Type</th>
            <th>Value</th>
            <th>Start day</th>
            <th>#</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Name Promotion</th>
            <th>Type</th>
            <th>Value</th>
            <th>Start day</th>
            <th>#</th>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($promotionList as $promotion) { ?>
            <tr>
                <td><?= $promotion["id_promotion"] ?></td>
                <td><?= $promotion["name_promotion"] ?></td>
                <td><?= $promotion["type_promotion"] ?></td>
                <td><?= $promotion["value"] ?></td>
                <td><?= $promotion["start_day"] ?></td>
                <td>
                    <a href="?mod=promotion&act=detail&id=<?= $promotion['id_promotion'] ?>" class="btn btn-success">Xem</a>
                    <a href="?mod=promotion&act=edit&id=<?= $promotion['id_promotion'] ?>" class="btn btn-warning">Sửa</a>
                    <a href="?mod=promotion&act=delete&id=<?= $promotion['id_promotion'] ?>" onclick="return confirm('Bạn có thật sự muốn xóa ?');" class="btn btn-danger">Xóa</a>

                    <?php if (isset($_SESSION['isLogin_Admin']) && $_SESSION['isLogin_Admin'] == true) { ?>

                    <?php }?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>