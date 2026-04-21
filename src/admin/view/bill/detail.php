<table class="table" id="dataTable" width="100%" cellspacing="0">
    <div class="form-group">
        <label for="" class="text-danger">Total Cost: <?= $bill['total_cost'] . " VND" ?></label>
    </div>
    <div class="form-group">
        <label for="">ID Bill: <?= $bill['id_bill'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">ID User: <?= $bill['id_user'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Name User: <?= $bill['name_user'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Phone: <?=  $bill['phone'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Address: <?= $bill['address'] ?></label>
    </div>
    <div class="form-group">
        <label for="">Payment Method: <?= $bill['payment_method'] === "1" ? "Credit Card" : "COD" ?></label>
    </div>
    <div class="form-group">
        <label for="">Add Day: <?= $bill['timestamp'] ?></label>
    </div>
    <div class="form-group">
        <label for="">Status: <?= $bill['status'] === "1" ? "Done" : "Unpaid" ?></label>
    </div>
    <div class="form-group">
        <label for="">Note: <?= $bill['note'] ?></label>
    </div>
    <?php if ($bill['status'] === "0") { ?>
        <a href="?mod=bill&act=confirm&id=<?= $_GET['id'] ?>" type="button" class="btn btn-success">Done</a>

    <?php } ?>
</table>