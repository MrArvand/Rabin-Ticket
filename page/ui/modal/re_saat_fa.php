<?php
$fild_hadaf = str_p('mpost2');
?>


<div class="modal-body">
  <div class="row">
    <div class="col mb-0">
      <label class="form-label" for="sa_selc">ساعت</label>
      <select id="sa_selc" class="form-control form-control-sm">
        <?php for ($iid_sa = 00; $iid_sa < 24; $iid_sa++) { ?>
          <option> <?php echo $iid_sa; ?></option>
        <?php } ?>

      </select>
    </div>


    <div class="col mb-0">
      <label class="form-label" for="da_selc">دقیقه</label>
      <select id="da_selc" class="form-control form-control-sm">
        <?php for ($iid_da = 00; $iid_da < 60; $iid_da++) { ?>
          <option> <?php echo $iid_da; ?></option>
        <?php } ?>

      </select>
    </div>

  </div>
</div>
<div class="modal-footer">
  <button class="btn btn-danger" data-bs-dismiss="modal">بستن</button>
  <button
    onclick="setfild(document.getElementById(`sa_selc`).value +':'+ document.getElementById(`da_selc`).value,'saat_e')"
    data-bs-dismiss="modal" type="button" class="btn btn-primary">تایید ساعت</button>
</div>