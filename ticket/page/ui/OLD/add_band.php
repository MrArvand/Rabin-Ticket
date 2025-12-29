           <?php
           $code_g=str_g('code_g');
           $kind=str_g('kind');
           $kind_band=str_p('kind_band');
           $daste=str_p('daste');
           $kalame=str_p('kalame');
           ?>
            <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
            <div class="card mb-4">


            <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
        <div class="button-wrapper">

           <a href="?page=new_band"> <button type="button" class="btn btn-outline-success mb-3">
              <i class="mdi mdi-reload d-block d-sm-none"></i>
              <span class="d-none d-sm-block">بند و تبصره جدید</span>
            </button></a>
</div>

          
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
        <div class="button-wrapper">
        <form  method="post"   action="?page=add_band&code_g=<?php echo $code_g; ?>&kind=<?php echo $kind; ?>" class="card-body">
        <div class="row g-3">

      <div class="col-md-3">
        <div class="form-floating form-floating-outline">
                <select name ="kind_band" id="kind_band" class="select2 form-select select2-hidden-accessible" data-select2-id="kind_band">
                  <option <?php if($kind_band =="0")echo"selected"; ?> value="0">بند و تبصره</option>
                  <option  <?php if($kind_band =="band")echo"selected"; ?>  value="band">بند</option>
                  <option  <?php if($kind_band =="tabsare")echo"selected"; ?>  value="tabsare">تبصره</option>

                </select>
        </div>
      </div>

      
      <div class="col-md-3">
        <div class="form-floating form-floating-outline">
          <input name="kalame" valye="<?php echo $kalame; ?>" type="text" id="multicol-first-name" class="form-control"  />
          <label for="multicol-first-name">کلمه کلیدی</label>
        </div>
      </div>


      <div class="col-md-3">
        <div class="form-floating form-floating-outline">
        <select id="daste" name="daste"  class="select2 form-select select2-hidden-accessible" data-select2-id="kind_band">
                  <option value="0">تمام دسته بندی ها </option>
                       <option   <?php if($daste =="عمومی")echo"selected"; ?>  >عمومی</option>   
                        <option    <?php if($daste =="طرفین قرارداد")echo"selected"; ?>  >طرفین قرارداد</option>   
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


      <div class="col-md-3">
      <button type="submit"  class="btn btn-primary me-1" >نمایش  </button>

      </div>

          </div>
          </form>
</div>

          
        </div>
      </div>


            <div class="table-responsive border rounded my-4">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th class="  fw-medium">#</th>
                <th class="  fw-medium">نوع</th>
                <th class="  fw-medium">متن</th>
                <th class="  fw-medium">انتخاب</th>
              </tr>
            </thead>
            
            <tbody>
            <?php
$shart = 'i_band > 0';


if($kind_band != "0" AND $kind_band != "")  $shart=$shart." AND "."kind_band='$kind_band'";
if($daste != "0" AND $daste != "")$shart=$shart." AND "."daste='$daste'";
if($kalame != "0" AND $kalame != "")$shart=$shart." AND "."matn_band like '%$kalame%'";


								$shomare=0;
								$Query_list="SELECT*from bandha where ($shart)ORDER BY i_band DESC LIMIT 1000";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list_band=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>

              <tr>
                <td class=""><?php echo $shomare; ?></td>
                <td class=" "><?php echo $q_list_band['kind_band']; ?><br><?php echo $q_list_band['daste']; ?></td>
                <td class=" "><?php echo $q_list_band['name_band']; ?><br><?php echo nl2br($q_list_band['matn_band']); ?></td>
                <td class=" "><a class="btn btn-outline-success" href="?page=s_add_band&code_g=<?php echo $code_g; ?>&code=<?php echo $q_list_band['code_band']; ?>&kind=<?php echo $kind; ?>">افزودن به قرارداد</a></td>
              </tr>
              <?php }} ?>
            </tbody>

          </table>
        </div>
          </div>
          </div>
	        </div>



                        
