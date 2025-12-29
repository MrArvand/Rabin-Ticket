<div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
<?php include('page/ui/panel_g.php'); ?>
              </div>
</div></div>

<div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
                <h5 class="">تغییر وضعیت قرارداد</h5>
                <form  method="post"   action="?page=s_vaziat_gha" class="card-body">
                  <h6 class="fw-normal">اطلاعات اولیه</h6>
<span class="text-info"> در مرحله تغییر به نهایی کردن قرار داد باید شماره قرارداد را وارد نماید </span>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="name_pro"> شماره / سریال قرارداد </label>
                     
                      <input type="text" name="code_sabt_g" id="code_sabt_g" class="form-control text-start" dir="ltr" placeholder=""
                      <?php if(  $vaziat_gharardad !="a"   AND  $vaziat_gharardad != "na" ) echo "readonly"; ?>
                      >
                    </div>

                    <div class="col-md-3">
                      <label class="form-label" for="vaziat_gha">انتخاب وضعیت</label>
                      <select name="vaziat_gha" id="vaziat_gha" class="select2 form-select"  >
                       <option value="na" >2 - تایید ناظر</option>
                       <option value="a" >2 -  عدم تایید ناظر و ویرایش مجدد </option>
                       <option value="y" >3 - تایید نهایی</option>
                       <option value="em" >4 - امضاء قرارداد</option>
                       <option value="p" >5 - پایان قرارداد</option>
                       <option value="v" >ویرایش</option>
                       <option value="c" >کنسل شدن</option>                      
                       <option value="t" >6 - تمدید قرارداد</option>
                      <option value="f" >7 - افزودن بند / الحاقیه</option>
                       

	
          </select>



                  </div>

                    <div class="col-md-3">
                      <label class="form-label" for="tarikh_sabt">تاریخ تغییر </label>
                      <input type="text" name="tarikh_sabt" value="<?php echo $tarikh; ?>" class="form-control" readonly >
                    </div>
          </div>

                  <hr class="my-4 mx-n4">
                  <div class="row g-3">







                    <div class="col-md-12">
                      <label class="form-label" for="matn">توضیحات تغییر وضعیت قرارداد</label>
                      <textarea name="matn" class="form-control" rows="5" placeholder="  "></textarea>				

                    </div>
  </div>



                  <div class="pt-4">
				  
				  										<button type="submit"  class="btn btn-primary me-sm-3 me-1" >  تغییر وضعیت قرارداد</button>

                    <button type="reset" class="btn btn-warning">انصراف</button>
                  </div>
                  <input type="hidden" name="code_g" id="code_g" value="<?php echo $code; ?>">
                </form>
              </div>

    </div>
	    </div>


    