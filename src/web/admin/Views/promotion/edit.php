<?php if (isset($_COOKIE['msg'])) { ?>
    <div class="alert alert-success">
        <strong>Thông báo</strong> <?= $_COOKIE['msg'] ?>
    </div>
<?php } ?>
<hr>
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <form action="?mod=promotion&act=update&id=<?= $_GET['id'] ?>" method="POST" role="form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="">ID</label>
            <input type="text" class="form-control" id="" placeholder="" disabled value="<?= $promotion['id_promotion'] ?>">
        </div>
        <div class="form-group">
            <label for="">Name Promotion</label>
            <input type="text" class="form-control" id="" placeholder="" name="name_promotion" value="<?= $promotion['name_promotion'] ?>">
        </div>
        <div class="form-group">
            <label for="">Type Promotion</label>
            <input type="text" class="form-control" id="" placeholder="" name="type_promotion" value="<?= $promotion['type_promotion'] ?>">
        </div>
        <div class="form-group">
            <label for="cars">Type sale: </label>
            <select id="" name="type_sale" class="form-control">
                <option value="0" <?= $promotion['type_sale'] === "0" ? "selected" : "" ?>>Direct reduction</option>
                <option value="1" <?= $promotion['type_sale'] === "1" ? "selected" : "" ?>>Decrease in percentage</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Value</label>
            <input type="text" class="form-control" id="" placeholder="" name="value" value="<?= $promotion['value'] ?>">
        </div>
        <div class="form-group">
            <label for="">Start day</label>
            <input type="text" class="form-control" id="" placeholder="" disabled value="<?= $promotion['start_day'] ?>">
        </div>
        <div class="form-group">
            <label for="cars">Status: </label>
            <select id="" name="status" class="form-control">
                <option value="0" <?= $promotion['status'] === "0" ? "selected" : "" ?>>No Active</option>
                <option value="1" <?= $promotion['status'] === "1" ? "selected" : "" ?>>Active</option>
            </select>
        </div>


        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</table>