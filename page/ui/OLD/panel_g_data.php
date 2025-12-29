
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
                <br>
<?php include('page/ui/panel_g.php'); ?>
<br>
              </div>
<div>


<div class="container-xxl flex-grow-1 container-p-y" len="13520">
            
            

            <div class="row invoice-preview" len="8865">
              <!-- Invoice -->
              <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-4" len="7514">
                <div class="card invoice-preview-card" len="7461">
                  <div class="card-body" len="3428">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column" len="3311">
                      <div class="mb-xl-0 pb-3" len="2944">
                        <div class="d-flex svg-illustration align-items-center gap-2 mb-4" len="2644">
                          <span class="app-brand-logo demo" len="2497">logo </span>
                          <span class="h4 mb-0 app-brand-text fw-bold" len="11" lang="fa" style="">بدنه و متن قرار داد </span>
                        </div>
                      </div>
                      <div len="293">
                        <h5 len="14" lang="fa" style="">شماره قرارداد  # <?php echo $sn_gharardad; ?> </h5>
                        <div class="mb-1" len="95">
                        <span len="12">آخرین تغییر:</span> <span len="14">اوریل 25، 2021</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr class="my-0" len="0">







                  <?php
                        $Query_selc="SELECT*from sakhtar where (kind_gharardad = '$kind_gharardad' )ORDER BY radif ASC LIMIT 200";
   if($Result_selc=mysqli_query($Link,$Query_selc)){
 while($q_selc=mysqli_fetch_array($Result_selc)){ 
  $kind_bandg=$q_selc['id_radif'];
  ?>

<strong ><?php echo $q_selc['name']; ?>
<a href="?page=add_band&code_g=<?php echo $code; ?>&kind=<?php echo $q_selc['id_radif']; ?>"><button type="button" class="btn btn-icon btn-label-success btn-sm ">
              <span class="tf-icons mdi mdi-plus mdi-24px"></span>
            </button></a>                     

</strong>

<br>
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
								$Query_list="SELECT*from band_gharardad where (code_gharardad = '$code' AND  code_sakhtar = '$kind_bandg' )ORDER BY radif,i_band_g ASC LIMIT 1000";
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

    $Query_rrt="SELECT*from dade where (code_gharardad = '$code' AND  id_motaghayer = '$match')";
   if($Result_rrt=mysqli_query($Link,$Query_rrt)){
 while($q_list_band_g=mysqli_fetch_array($Result_rrt)){
  $meghdar_mo=$q_list_band_g['meghdar'];
 }}

    if($meghdar_mo==""){echo"-";}else{echo"$meghdar_mo";} echo"</td>";
?>
<td>
<button  data-bs-toggle="modal" data-bs-target="#editmeghdar"  
 onclick="openmodal('badane_modal_3','set_meghdar_m','<?php echo $code; ?>','<?php echo $match; ?>','0','0')"
 type="button" class="btn btn-icon btn-label-success btn-sm ">
              <span class="tf-icons mdi mdi-pen mdi-24px"></span>
            </button>

    </td>
  </tr>

<?php } ?>




 <?php }} ?>
</tbody>
</table>

<?php }}?>
<br>

                </div>
              </div>
            </div>
            




        </div>
        <!-- Content wrapper -->


</div>


    </div>
	    </div>



      <div class="modal fade" id="editmeghdar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div id="badane_modal_3" class="modal-body py-3 py-md-0">
در حال بارگزاری ... 
      </div>
    </div>
  </div>
</div>