<?php 
$fild_hadaf=str_p('mpost2'); 
?>		


                            <div class="modal-body">
                              <div class="row">
                                <div class="col mb-0">
                                   <label class="form-label" for="sal_selc">سال</label>
                                                     <select id="sal_selc" class="form-control form-control-sm">
						  <?php for($iid_rooz=1345; $iid_rooz< $jyear+1; $iid_rooz++){?>
                            <option <?php if($iid_rooz==$jyear)echo"selected"; ?>> <?php echo $iid_rooz; ?></option>
						  <?php } ?>

                          </select>
                                </div>
                                <div class="col mb-0">
                        <label class="form-label" for="mah_selc">ماه</label>
                          <select id="mah_selc" class="form-control form-control-sm">
						  <?php for($iid_rooz=01; $iid_rooz<13; $iid_rooz++){
							  
if($iid_rooz=="1")$iid_rooz="01";
 if($iid_rooz=="2")$iid_rooz="02";
   if($iid_rooz=="3")$iid_rooz="03";
    if($iid_rooz=="4")$iid_rooz="04";
	 if($iid_rooz=="5")$iid_rooz="05";
	  if($iid_rooz=="6")$iid_rooz="06";
	   if($iid_rooz=="7")$iid_rooz="07";
	    if($iid_rooz=="8")$iid_rooz="08";
		 if($iid_rooz=="9")$iid_rooz="09";
		 
		 
							  ?>
                            <option <?php if($iid_rooz==$jmonth)echo"selected"; ?>> <?php echo $iid_rooz; ?></option>
						  <?php } ?>

                          </select>
                                </div>
                                <div class="col mb-0">
                        <label class="form-label" for="rooz_selc">روز</label>
                          <select id="rooz_selc" class="form-control form-control-sm">
						  <?php for($iid_rooz=01; $iid_rooz<32; $iid_rooz++){
							  if($iid_rooz=="1")$iid_rooz="01";
 if($iid_rooz=="2")$iid_rooz="02";
   if($iid_rooz=="3")$iid_rooz="03";
    if($iid_rooz=="4")$iid_rooz="04";
	 if($iid_rooz=="5")$iid_rooz="05";
	  if($iid_rooz=="6")$iid_rooz="06";
	   if($iid_rooz=="7")$iid_rooz="07";
	    if($iid_rooz=="8")$iid_rooz="08";
		 if($iid_rooz=="9")$iid_rooz="09";
							  ?>
                            <option <?php if($iid_rooz==$jday)echo"selected"; ?>> <?php echo $iid_rooz; ?></option>
						  <?php } ?>
  
                          </select>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
							  <button class="btn btn-danger" data-bs-dismiss="modal">بستن</button>
                              <button  onclick="setfild(document.getElementById(`sal_selc`).value + document.getElementById(`mah_selc`).value + document.getElementById(`rooz_selc`).value,'tarikh_e')"  data-bs-dismiss="modal"   type="button" class="btn btn-primary">تایید تاریخ</button>
                            </div>












					
					

					
					

		
