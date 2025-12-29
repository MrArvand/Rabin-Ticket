            <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
            <div class="card mb-4">


            <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
        <div class="button-wrapper">

           <a href="<?php echo $_SERVER['HTTP_REFERER'];?>"> <button type="button" class="btn btn-outline-success mb-3">
              <i class="mdi mdi-reload d-block d-sm-none"></i>
              <span class="d-none d-sm-block">برگشت به صفحه قبل </span>
            </button></a>
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
              </tr>
            </thead>
            
            <tbody>
            <?php


								$shomare=0;
								$Query_list="SELECT*from bandha where (code_band = '$code')ORDER BY i_band DESC LIMIT 1000";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list_band=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>

              <tr>
                <td class=""><?php echo $shomare; ?></td>
                <td class=" ">
                  
                <?php                if($q_list_band['kind_band']=="band")echo"بند";
                if($q_list_band['kind_band']=="tabsare")echo"تبصره"; ?>
                
                <br><?php echo $q_list_band['daste']; ?></td>
                <td class=" "><strong><?php echo $q_list_band['name_band']; ?></strong><br><?php echo nl2br($q_list_band['matn_band']); ?></td>              </tr>
              <?php }} ?>
            </tbody>

          </table>
<h4></h4>

<table class="table">
      <thead class="table-light">
        <tr>
          <th>متغیر</th>
          <th>مقدار</th>
          <th>ویرایش</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
                  <?php
       
								$shomare=0;
								$Query_list="SELECT*from bandha where (code_band = '$code'  )ORDER BY i_band ASC LIMIT 1";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list_band_g=mysqli_fetch_array($Result_list)){
	 $shomare++;
$matn_band1 = $q_list_band_g['matn_band'];


$pattern = '/#(.*?)#/';

preg_match_all($pattern, $matn_band1, $matches);
$sadr="mahale";
?>

  <?php
foreach ($matches[1] as $match) {

    echo "<tr><td>$match</td>";

    echo "<td>";
    $meghdar_mo="";


    if($meghdar_mo==""){echo"-";}else{echo"$meghdar_mo";} echo"</td>";
?>
<td>


    </td>
  </tr>

<?php } ?>




 <?php }} ?>
</tbody>
</table>

<?php
$tedada_estefade="0";

$Query_list5="SELECT*from band_gharardad where ( code_band = '$code')";
if($Result_list5=mysqli_query($Link,$Query_list5)){
while($q_list_band_g5=mysqli_fetch_array($Result_list5)){
  $tedada_estefade++;
}}


?>
<hr>
<span >
این بند در   <?php echo $tedada_estefade; ?>  قرارداد استفاده شده و امکان حذف یا تغییر نیست 

</span>





        </div>
          </div>
          </div>
	        </div>



                        
