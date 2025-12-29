


                   <!-- Row starts -->
            <div class="row gx-3">
              <div class="col-sm-12">
                <div class="card mb-3">
                  <div class="card-header">
                    <h5 class="card-title">ثبت محتوای آموزشی </h5>
                  </div>
                  <div class="card-body">



                  <form  method="post"   action="?page=s_new_mohtava"  enctype="multipart/form-data">
                    <!-- Row starts -->
                    <div class="row gx-3">
                        
                                              <div class="input-group mb-5">
                      <label class="input-group-text" for="poster">بارگزاری پوستر</label>
                      <input name="poster" type="file" class="form-control" id="poster" />
                    </div>
                    
                    
                      <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="name_w">نام تهیه کننده</label>
                          <input   type="text" class="form-control" id="name_w"  name="name_w"  placeholder="نام " required>
                        </div>
                      </div>
                      
                                            <div class="col-lg-6 col-sm-8 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="titr">عنوان / تیتر آموزش</label>
                          <input   type="text" class="form-control" id="titr"  name="titr"  placeholder=" " required>
                        </div>
                      </div>
                      
                     <div class="col-lg-12 col-sm-12 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="link_vi"> آدرس فایل تصویری در آپارات </label>
                          <input   type="text" class="form-control" id="link_vi"  name="link_vi"  placeholder=" " >
                        </div>
                      </div>
                      
                      <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="kind_u">دسته بندی کاربران</label>
                          <select class="form-select" name="kind_u" id="kind_u">
                            <option value="1">عمومی</option>
                            <option value="2">کارشناسی</option>
                            <option value="3">تخصصی</option>
                          </select>
                        </div>
                      </div>


                      <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="daste">دپارتمان پشتیبانی</label>
                          <select class="form-select" name="daste" id="daste">
                          <?php

$Query_dep="SELECT*from departman where (vaziat = 'y') ORDER BY name ASC LIMIT 200";
if($Result_dep=mysqli_query($Link,$Query_dep)){
while($q_dep=mysqli_fetch_array($Result_dep)){

?>
                            <option value="<?php echo $q_dep['id']; ?>"><?php echo $q_dep['name']; ?> - [<?php echo $q_dep['id']; ?>]</option>
<?php }} ?>                            
                          </select>
                        </div>
                      </div>

                      <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="sherkat">شرکت پشتیبان</label>
                          <select class="form-select" name="sherkat" id="sherkat">
                          <?php

								$Query_sherkat="SELECT*from sherkatha where (1)ORDER BY name DESC LIMIT 200";
   if($Result_sherkat=mysqli_query($Link,$Query_sherkat)){
 while($q_sherkat=mysqli_fetch_array($Result_sherkat)){

	 ?>
                            <option value="<?php echo $q_sherkat['code']; ?>"><?php echo $q_sherkat['name']; ?></option>
<?php }} ?>
                          </select>
                        </div>
                      </div>

                      
                      
                      
                                            <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="cat2">دسته بندی موضوعی : </label>
                          <select class="form-select" name="cat2" id="cat2">
                         
                          <?php

$Query_dep="SELECT*from daste_mohtava where (fader = 'n') ORDER BY name_f_daste ASC LIMIT 200";
if($Result_dep=mysqli_query($Link,$Query_dep)){
while($q_dep=mysqli_fetch_array($Result_dep)){

?>
                            <option value="<?php echo $q_dep['id_daste']; ?>"><?php echo $q_dep['name_f_daste']; ?> > <?php echo $q_dep['name_daste']; ?></option>
<?php }} ?>                            
                          </select>
                        </div>
                      </div>
                      
                      
                      
                      
                      <div class="col-sm-12 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="matn">توضیحات</label>
                          <textarea type="text" class="form-control"  name="matn"  id="matn" placeholder="متن درخواست"
                            rows="3" required ></textarea>
                        </div>
                      </div>
                      <div class="input-group mb-5">
                      <label class="input-group-text" for="file_peyvast">بارگذاری</label>
                      <input name="file_peyvast" type="file" class="form-control" id="file_peyvast" />
                    </div>
                    <!-- Row ends -->

                  </div>
                  <div class="card-footer">
                    <div class="d-flex gap-2 justify-content-end">
                      <button type="reset" class="btn btn-outline-secondary">لغو</button>
                      <button  type="submit"  class="btn btn-primary">ثبت محتوا</button>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            </div>
            </div>


          
       
          
          
          
          
          
          