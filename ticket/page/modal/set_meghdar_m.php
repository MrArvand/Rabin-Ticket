<?php
$code_g=str_p('mpost2');
$name_mot=str_p('mpost3');
?>
<div class="row">
 
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">ویرایش مقدار متغیر <?php echo $name_mot; ?> </h3>
          <h5> در قرار داد  به کد سیستمی :  <?php echo $code_g; ?> </h5>
        </div>
        <?php
$meghdar_mo="";
$name_motaghayer="";
$Query_rrt="SELECT*from dade where (code_gharardad = '$code_g' AND  id_motaghayer = '$name_mot')";
if($Result_rrt=mysqli_query($Link,$Query_rrt)){
while($q_list_band_g=mysqli_fetch_array($Result_rrt)){
$meghdar_mo=$q_list_band_g['meghdar'];
$name_motaghayer=$q_list_band_g['name'];
}}


?>
        <form   action="?page=s_dade_g" id="editUserForm"   method="post">
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input name="name_motaghayer" value="<?php echo $name_motaghayer; ?>" type="text" id="modalEditUserFirstName" name="modalEditUserFirstName" class="form-control" placeholder="" />
              <label for="modalEditUserFirstName">نام متغیر</label>
            </div>
          </div>


          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
            <textarea name="meghdar" class="form-control" rows="7" placeholder="  "><?php echo $meghdar_mo; ?></textarea>	
              <label for="modalEditUserFirstName">مقدار دهی</label>
            </div>
          </div>


          <input value="<?php echo $code_g; ?>" type="hidden" name="code_g" >
          <input value="<?php echo $name_mot; ?>" type="hidden" name="name_m" >


          <div class="col-12 text-center">
            <button type="submit" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary me-sm-3 me-1">ثبت</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">انصراف</button>
          </div>
        </form>

</div>


		      
     