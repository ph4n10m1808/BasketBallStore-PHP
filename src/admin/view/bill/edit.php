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

    <form action="?mod=bill&act=update&id=<?= $_GET['id'] ?>" method="POST" role="form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="">User: </label>
            <select id="" name="id_user" class="form-control">
                <?php foreach ($userList as $user) { ?>
                    <option value="<?= $user['id_user'] ?>" <?= $bill['id_user'] === $user['id_user'] ? "selected" : "" ?>>
                        <?= $user['username'].": (". $user['first_name'] . " " . $user['last_name'].")" ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="">Name User</label>
            <input type="text" class="form-control" id="" placeholder="" name="name_user" value="<?= $bill['name_user'] ?>">
        </div>
        <div class="form-group">
            <label for="">Phone</label>
            <input type="text" class="form-control" id="" placeholder="" name="phone" value="<?= $bill['phone'] ?>">
        </div>

        <div class="form-group">
            <label for="">Address</label>
            <input type="text" class="form-control" id="" placeholder="" name="address" value="<?= $bill['address'] ?>">
        </div>
        <div class="form-group">
            <label for="">Payment Method</label>
            <select id="" name="payment_method" class="form-control">
                <option value="1">Credit Cast</option>
                <option value="0" <?= $bill['payment_method'] === "0" ? "selected" : "" ?>>COD</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Total Cost</label>
            <input type="text" class="form-control" id="" placeholder="" name="total_cost" value="<?= number_format($bill['total_cost']) ?>">
        </div>
        <div class="form-group">
            <label for="">Status</label>
            <select id="" name="status" class="form-control">
                <option value="0">Unpaid</option>
                <option value="1" <?= $bill['status'] === "1" ? "selected" : "" ?>>Done</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Note</label>
            <textarea name="note" class="form-control"><?= $bill['note'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</table>