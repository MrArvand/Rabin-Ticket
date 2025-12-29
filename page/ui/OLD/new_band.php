            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4"><br>
                <h5 class="">تعریف بند/تبصره جدید</h5>
                <form  method="post"   action="?page=s_new_band" class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="name_pro">بند تبصره جدید</label>
                     
                      <input type="text" name="name_band" id="name_band" class="form-control text-start" dir="ltr" placeholder="">
                    </div>

                    <div class="col-md-3">
                      <label class="form-label" for="kind_band">بند یا تبصره</label>
                      <select name="kind_band" id="kind_band" class="select2 form-select"  >
                        <option value="band">بند</option>   
                        <option value="tabsare">تبصره</option>        
                      </select>
                    </div>		


                    <div class="col-md-3">
                      <label class="form-label" for="daste">دسته</label>
                      <select name="daste" id="daste" class="select2 form-select"  >
                        <option >عمومی</option>   
                        <option >طرفین قرارداد</option>   
                        <option  >موضوع قرارداد</option>   
                        <option  >مدت قرارداد</option> 
                        <option  >مبلغ قرارداد</option> 
                        <option  >روش پرداخت</option> 
                        <option  >تعهدات طرف اول </option> 
                        <option  >تعهدات طرف دوم</option> 
                        <option  >فورث ماژور</option> 
                        <option  >حل اختلاف</option> 
                      </select>
                    </div>		




                  </div>
                  <hr class="my-4 mx-n4">
                  <div class="row g-3">







                    <div class="col-md-12">
                      <span class="small text-info">برای درج متغیر در متن باید متغیر را با کلمه لاتین و در بین دو # قرار دهید </span>
                      <br><span class="small text-info">حتما از کلمه لاتین استفاده کنید و از بکار بردن کارکتر های  $%^&#@  خوداری نمایید </span>

                      <br><label class="form-label" for="matn">محتوای بند</label>

                      <textarea name="matn" class="form-control" rows="5" placeholder="  "></textarea>				

                    </div>
  </div>


                  <div class="pt-4">
				  
				  										<button type="submit"  class="btn btn-primary me-sm-3 me-1" >ثبت و تعریف بند</button>

                    <button type="reset" class="btn btn-warning">انصراف</button>
                  </div>
                </form>
              </div>

    </div>
	    </div>
