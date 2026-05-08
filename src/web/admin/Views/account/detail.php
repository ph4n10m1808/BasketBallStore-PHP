<table class="table" id="dataTable" width="100%" cellspacing="0">
        <div class="form-group">
            <label for="">Họ và tên: <?= $account['first_name'] . " " . $account['last_name']  ?> </label>
        </div>
        <div class="form-group">
            <label for="">Giới tính: <?=  $account['gender'] === "1" ? "Nam" : "Nữ" ?> </label>
        </div>
        <div class="form-group">
            <label for="">Số Điện Thoại: <?= $account['phone'] ?></label>
        </div>
        <div class="form-group">
            <label for="">Địa chỉ: <?= $account['address'] ?></label>
        </div>
        <div class="form-group">
            <label for="">Username: <?= $account['username'] ?></label>
        </div>
        <div class="form-group">
            <label for="">Email: <?= $account['email'] ?></label>
        </div>
        <div class="form-group">
            <label for="">Role: <?php if ($account['id_auth'] === "1") {
                echo "Admin";
            } elseif ($account['id_auth'] === "2") {
                echo "Employee";
            } else {
                echo "User";
            } ?></label>
        </div>
</table>