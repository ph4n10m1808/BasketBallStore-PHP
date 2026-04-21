<table class="table" id="dataTable" width="100%" cellspacing="0">
    <div class="form-group">
        <label for="">Name Product Type: <?= $productType['name_pt'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Description: <?=  $productType['description'] ?> </label>
    </div>
    <div class="form-group">
        <label for="">Logo: <img style="max-width: 200px;" src="../public/<?= $productType['logo_pt'] ?>" alt=""> </label>
    </div>

</table>