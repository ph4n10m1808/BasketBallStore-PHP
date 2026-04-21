<div>
    <h3>Danh sách hoá đơn của bạn:</h3>
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên người nhận</th>
            <th>SĐT</th>
            <th>Địa chỉ</th>
            <th>Tổng tiền</th>
            <th>Ngày thanh toán</th>
            <th>Trạng thái</th>
            <th>#</th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0?>
        <?php foreach ($billList as $bill) { ?>
            <tr>
                <?php $count++?>
                <td><?= $count ?></td>
                <td><?= $bill["name_user"] ?></td>
                <td><?= $bill["phone"] ?></td>
                <td><?= $bill["address"] ?></td>
                <td><?= number_format($bill["total_cost"]) . " VND" ?></td>
                <td style="max-width: 100px"><?= $bill["timestamp"] ?></td>
                <td><?= $bill["status"] === "1" ? "Đã giao" : "Đang xử lý" ?></td>
                <td>
                    <a href="?page=bill&act=delete&id=<?= $bill['id_bill'] ?>" onclick="return confirm('Bạn có thật sự muốn xóa ?');" type="button" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>