


                   <!-- Row starts -->
            <div class="row gx-3">
              <div class="col-sm-12">
                <div class="card mb-3">
                  <div class="card-header">
                    <h5 class="card-title">ایجاد یک تیکت پشتیبانی جدید   </h5>
                  </div>
                  <div class="card-body">



                  <form  method="post"   action="?page=s_new_ticket"  enctype="multipart/form-data">
                    <!-- Row starts -->
                    <div class="row gx-3">
                      <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="karbar_darkhast">درخواست دهنده</label>
                          <input      type="text" class="form-control" id="karbar_darkhast"  name="karbar_darkhast"  value="<?php echo $name_karbar_run; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="olaviat">ضرورت</label>
                          <select class="form-select" name="olaviat" id="olaviat">
                            <option value="1">ضروری</option>
                            <option selected value="2">متوسط</option>
                            <option value="3">معمولی</option>
                            <option value="4">پایین</option>
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
                          <label class="form-label" for="sherkat">شرکت درخواست دهنده</label>
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

                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="titr">تیتر درخواست</label>
                          <input type="text" class="form-control" id="titr" name="titr" placeholder="تیتر درخواست" required >
                        </div>
                      </div>

                      <div class="col-sm-12 col-12">
                        <div class="mb-3">
                          <label class="form-label" for="matn">پیام</label>
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
                      <button  type="submit"  class="btn btn-primary">ثبت</button>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            </div>
            </div>


          

          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          