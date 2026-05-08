<div class="container my-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h4 class="fw-bold" style="color: #1e293b;"><i class="fas fa-file-invoice-dollar me-2" style="color: #4f46e5;"></i>Danh sách hoá đơn của bạn</h4>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light" style="border-radius: 10px;">
                    <tr>
                        <th class="border-0 rounded-start">ID</th>
                        <th class="border-0">Tên người nhận</th>
                        <th class="border-0">SĐT</th>
                        <th class="border-0">Địa chỉ</th>
                        <th class="border-0">Tổng tiền</th>
                        <th class="border-0">Ngày thanh toán</th>
                        <th class="border-0">Trạng thái</th>
                        <th class="border-0 rounded-end text-center">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 0?>
                    <?php foreach ($billList as $bill) { ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <?php $count++?>
                            <td class="fw-bold text-muted"><?= $count ?></td>
                            <td><span class="fw-semibold" style="color: #334155;"><?= $bill["name_user"] ?></span></td>
                            <td style="color: #475569;"><?= $bill["phone"] ?></td>
                            <td><small class="text-muted"><?= $bill["address"] ?></small></td>
                            <td class="fw-bold text-danger"><?= number_format($bill["total_cost"]) . " đ" ?></td>
                            <td style="max-width: 120px"><small class="text-muted"><i class="far fa-clock me-1"></i><?= $bill["timestamp"] ?></small></td>
                            <td>
                                <?php if ($bill["status"] === "1") { ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;"><i class="fas fa-check-circle me-1"></i>Đã giao</span>
                                <?php } else { ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important; color: white !important;"><i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý</span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <a href="?page=bill&act=delete&id=<?= $bill['id_bill'] ?>" onclick="return confirm('Bạn có thật sự muốn xóa hoá đơn này?');" class="btn btn-sm btn-outline-danger" title="Xoá hoá đơn" style="border-radius: 8px; transition: all 0.2s;"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>