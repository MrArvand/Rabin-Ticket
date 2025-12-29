<div class="m-10" >




</div>
 <div class="card mb-4">
<h5> تغییر کلمه عبور برای کاربری  <?php
 echo $name_karbar_run;
?></h5>
                <form  method="post"   action="?page=s_ch_pass" class="card-body">
                  <h6 class="fw-normal">اطلاعات اولیه</h6>
                  <div class="row g-3">
                    <div class="col-md-3">
                      <label class="form-label" for="pass1">کلمه عبور فعلی</label>
                     
                      <input type="password" name="pass1" id="pass1" class="form-control text-start" dir="ltr" placeholder="" required>
                    </div>

                    <div class="col-md-3">
                      <label class="form-label" for="pass2">کلمه عبور جدید</label>
                     
                      <input type="password" name="pass2" id="pass2" class="form-control text-start" dir="ltr" placeholder="" required>
                    </div>

                    <div class="col-md-3">
                      <label class="form-label" for="pass3">تکرار کلمه عبور جدید</label>
                     
                      <input type="password" name="pass3" id="pass3" class="form-control text-start" dir="ltr" placeholder="" required>
                    </div>


	






                  </div>



                  <div class="pt-4">
				  
				  	<button type="submit"  class="btn btn-primary me-sm-3 me-1" >تغییر کلمه عبور تایید است</button>
                    <button type="reset" class="btn btn-warning">انصراف</button>
                  </div>
                </form>
              </div>