<div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
                <h5 class="">تعریف کاربر جدید</h5>
                <form  method="post"   action="?page=s_new_karbar" class="card-body">
                  <h6 class="fw-normal">اطلاعات اولیه</h6>
                  <div class="row g-3">
                    <div class="col-md-3">
                      <label class="form-label" for="name_karbar">نام</label>
                     
                      <input type="text" name="name_karbar" id="name_karbar" class="form-control text-start" dir="ltr" placeholder="" require>
                    </div>

                    <div class="col-md-3">
                      <label class="form-label" for="code_p_karbar">کد پرسنلی</label>
                     
                      <input type="text" name="code_p_karbar" id="code_p_karbar" class="form-control text-start" dir="ltr" placeholder="" require>
                    </div>

                    <div class="col-md-3">
                      <label class="form-label" for="tel_karbar">شماره تماس</label>
                     
                      <input type="text" name="tel_karbar" id="tel_karbar" class="form-control text-start" dir="ltr" placeholder="" require>
                    </div>


                    <div class="col-md-3">
                      <label class="form-label" for="ok_set_login">قابلیت لاگین و ورود </label>
                      <select name="ok_set_login" id="ok_set_login" class="select2 form-select"  >
                        <option value="n">نیاز نیست</option>   
                        <option value="y">بله جزء تیم حقوقی هستند</option>        
                      </select>
                    </div>		


                    <div class="col-md-3">
                      <label class="form-label" for="daste">دسته</label>
                      <select name="daste" id="daste" class="select2 form-select"  >
                        <option >مدیران ارشد</option>   
                        <option >مدیران شرکت ها</option>   
                        <option  >معاونین</option>   
                        <option  >مدیران میانی</option> 
                        <option  >کارشناس</option> 
                        <option  >مشاور</option> 
                      </select>
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


      <div class="container-xxl flex-grow-1 container-p-y">
    <!-- Default -->
    <div class="row">
        <div class="card mb-4">

            <!-- Row grouping -->
                <h5 class="card-header">لیست کاربران </h5>
                <div class="card-datatable table-responsive">
                    <table class="dt-row-grouping table table-bordered">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="fw-bold">نام کاربر</th>
                            <th class="fw-bold">سمت</th>
                            <th class="fw-bold">شماره تلفن</th>
                            <th class="fw-bold">login</th>
                            <th class="fw-bold">وضعیت</th>

                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $shomare=0;
                        $Query_list="SELECT*from karbar where (1)ORDER BY i_karbar DESC LIMIT 500";
                        if($Result_list=mysqli_query($Link,$Query_list)){
                            while($q_list=mysqli_fetch_array($Result_list)){
                                $shomare++;
                                ?>
                                <tr>
                                    <td><?php echo $shomare; ?></td>
                                    <td><span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $q_list['code_p']; ?>"><?php echo $q_list['name']; ?></span></td>
                                    <td><?php echo $q_list['semat']; ?></td>
                                    <td><?php echo $q_list['tel']; ?></td>
                                    <td><?php echo $q_list['set_login']; ?></td>
                                    <td>
                                        <?php  if($q_list['vaziat']=="a")echo"ثبت شد"; else echo"غیر فعال"; ?>
                                    </td>
                                </tr>

                            <?php }} ?>

                        </tbody>

                        <tfoot>
                        <tr>
                        <th></th>
                            <th class="fw-bold">نام کاربر</th>
                            <th class="fw-bold">سمت</th>
                            <th class="fw-bold">شماره تلفن</th>
                            <th class="fw-bold">login</th>
                            <th class="fw-bold">وضعیت</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            <!--/ Row grouping -->



        </div>

    </div>
</div>