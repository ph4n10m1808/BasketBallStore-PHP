<a href="?mod=bill&act=add" class="btn btn-primary">Thêm mới</a>
<hr>
<div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Total</th>
            <th>Timestamp</th>
            <th>Status</th>
            <th>#</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Total</th>
            <th>Timestamp</th>
            <th>Status</th>
            <th>#</th>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($billList as $bill) { ?>
            <tr>
                <td><?= $bill["id_bill"] ?></td>
                <td><?= $bill["name_user"] ?></td>
                <td><?= $bill["phone"] ?></td>
                <td><?= $bill["address"] ?></td>
                <td><?= number_format($bill["total_cost"]) . " VND" ?></td>
                <td style="max-width: 100px"><?= $bill["timestamp"] ?></td>
                <td><?= $bill["status"] === "1" ? "Done" : "Unpaid" ?></td>
                <td>
                    <a href="?mod=bill&act=detail&id=<?= $bill['id_bill'] ?>" class="btn btn-primary">View</a>
                    <a href="?mod=bill&act=edit&id=<?= $bill['id_bill'] ?>" class="btn btn-warning">Edit</a>
                    <a href="?mod=bill&act=delete&id=<?= $bill['id_bill'] ?>" onclick="return confirm('Bạn có thật sự muốn xóa ?');" class="btn btn-danger">Delete</a>
                    <?php if ($bill['status'] === "0") { ?>
                        <a href='?mod=bill&act=confirm&id=<?= $bill['id_bill'] ?>' type='button' class='btn btn-success'>Done</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>