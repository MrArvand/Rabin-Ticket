            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
                <h5 class="">تعریف قرارداد جدید</h5>
                <form  method="post"   action="?page=s_new_gha" class="card-body">
                  <h6 class="fw-normal">اطلاعات اولیه</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="name_pro">تیتر / عنوان قرارداد</label>
                     
                      <input type="text" name="titr" id="titr" class="form-control text-start" dir="ltr" placeholder="">
                    </div>

                    <div class="col-md-3">
                      <label class="form-label" for="kind_gha">انتخاب نوع قرارداد</label>
                      <select name="kind_gha" id="kind_gha" class="select2 form-select"  >
                      <?php
                        $Query_selc="SELECT*from kind_gharardad where (vaziat = 'y')ORDER BY name_gharardad DESC LIMIT 200";
   if($Result_selc=mysqli_query($Link,$Query_selc)){
 while($q_selc=mysqli_fetch_array($Result_selc)){ ?>
                       <option value="<?php echo $q_selc['id_gharardad']; ?>" selected><?php echo $q_selc['name_gharardad']; ?></option>
                          <?php }} ?>
                      </select>
                    </div>		


                    <div class="col-md-3">
                      <label class="form-label" for="name_sherkat">تنظیم در شرکت </label>
                      <select name="name_sherkat" id="name_sherkat" class="select2 form-select"  >
					              <?php
								$Query_list="SELECT*from sherkatha where (1)ORDER BY i_sherkat DESC LIMIT 1000";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list_band=mysqli_fetch_array($Result_list)){
	 ?>
                        <option value="<?php echo $q_list_band['code']; ?>" ><?php echo $q_list_band['name']; ?></option>   
   <?php }} ?>
                      </select>
                    </div>		




                  </div>
                  <hr class="my-4 mx-n4">
                  <div class="row g-3">







                    <div class="col-md-3">
                      <label class="form-label" for="tarikh_sabt">تاریخ ثبت قرارداد</label>
                      <input type="text" name="tarikh_sabt"  class="form-control"  id="flatpickr-date">
                    </div>


                    <div class="col-md-3">
                      <label class="form-label" for="nazer_gha">ناظر قرارداد</label>
                      <input data-bs-toggle="modal" data-bs-target="#date_select3"   onclick="openmodal('badane_modal_3','list_karbar','nazer_gha','0','0','0')" name="nazer_gha" id="nazer_gha" class="form-control input-sm" type="text" readonly>

                    </div>

                    <div class="col-md-3">
                      <label class="form-label" for="taid_gha">تایید کننده نهایی قرارداد</label>
                      <input data-bs-toggle="modal" data-bs-target="#date_select3"   onclick="openmodal('badane_modal_3','list_karbar','taid_gha','0','0','0')" name="taid_gha" id="taid_gha" class="form-control input-sm" type="text" readonly>

                    </div>


                    <div class="col-md-3">
                      <label class="form-label" for="taid_gha">مبلغ قرارداد ( میلیون تومان )</label>
                      <input  name="mablagh_gha" id="mablagh_gha" class="form-control input-sm" type="text" >

                    </div>

<hr>

<div class="col-md-3">
                      <label class="form-label" for="taid_gha">نام طرف اول</label>
                      <input  name="name_taraf1" id="name_taraf1" class="form-control input-sm" type="text" >

                    </div>


                    <div class="col-md-3">
                      <label class="form-label" for="taid_gha">نام طرف دوم</label>
                      <input  name="name_taraf2" id="name_taraf2" class="form-control input-sm" type="text" >

                    </div>

                    <div class="col-md-12">
                      <label class="form-label" for="matn">توضیحات مختصر قرارداد</label>
                      <textarea name="matn" class="form-control" rows="5" placeholder="  "></textarea>				

                    </div>
  </div>


                  <div class="pt-4">
				  
				  										<button type="submit"  class="btn btn-primary me-sm-3 me-1" >ثبت و تعریف قرارداد</button>

                    <button type="reset" class="btn btn-warning">انصراف</button>
                  </div>
                </form>
              </div>

    </div>
	    </div>
      <div class="modal" id="date_select3" tabindex="-1" aria-hidden="true" >
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">انتخاب</h4>
                  </div>
                  <div id="badane_modal_3" class="modal-body">
                    <p>در حال بارگزاری لیست ... </p>
                  </div>
                  <div class="modal-footer">

                  </div>
                </div>
              </div>
            </div>	

                        
            <div class="modal" id="date_select2" tabindex="-1" aria-hidden="true" >
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">موقعیت مکانی</h4>
                  </div>
                  <div id="badane_modal_2" class="modal-body">
                    <p>در حال بارگزاری لیست ... </p>
                  </div>
                  <div class="modal-footer">

                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->