            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
                <h5 class="">تعریف شرکت جدید</h5>
                <form  method="post"   action="?page=s_new_sherkat" class="card-body">
                  <h6 class="fw-normal">اطلاعات اولیه</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="name_co">نام شرکت</label>
                     
                      <input type="text" name="name_co" id="name_co" class="form-control text-start" dir="ltr" placeholder="">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="modir_co">انتخاب مدیر عامل ( حق امضا ) </label>
                      <input data-bs-toggle="modal" data-bs-target="#date_select3"   onclick="openmodal('badane_modal_3','list_karbar','modir_co','0','0','0')" name="modir_co" id="modir_co" class="form-control input-sm" type="text" readonly>

                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="sn_co">شماره ثبت  </label>
                     
                      <input type="text" name="sn_co" id="sn_co" class="form-control text-start" dir="ltr" placeholder="">
                    </div>


	




                  </div>
                  <hr class="my-4 mx-n4">


                  <div class="pt-4">
				  
<button type="submit"  class="btn btn-primary me-sm-3 me-1" >ثبت شرکت جدید</button>
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

                        
