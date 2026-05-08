<table class="table" id="dataTable" width="100%" cellspacing="0">
    <div class="form-group">
        <label for="">Name Promotion: <?= $promotion['name_promotion'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Type: <?= $promotion['type_promotion'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Type Sale: <?= $promotion['type_sale'] === "1" ? "Decrease in percentage" : "Direct reduction" ?> </label>
    </div>
    <div class="form-group">
        <label for="">Value: <?= $promotion['value'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Start day: <?= $promotion['start_day'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Status: <?= $promotion['status'] ?> </label>
    </div>
</table>