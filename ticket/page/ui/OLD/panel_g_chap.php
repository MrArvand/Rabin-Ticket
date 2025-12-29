
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
                <br>
<?php include('page/ui/panel_g.php'); ?>
<br>
<a onclick="printdiv('date_print');" class="btn btn-primary">
              <i class='mdi mdi-printer me-1'></i> ارسال به پرینتر
            </a>
              </div>
<div>


<div id="date_print" class="container-xxl flex-grow-1 container-p-y" len="13520">
            
            

            <div class="row invoice-preview" len="8865">
              <!-- Invoice -->
              <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-4" len="7514">
                <div class="card invoice-preview-card" len="7461">

                  <div class="card-body" len="3428">

                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column" len="3311">
                     
                    <div class="mb-xl-0 pb-3" len="2944">
                        <div class="d-flex svg-illustration align-items-center gap-2 mb-4" len="2644">

                          <span class="h4 mb-0 app-brand-text fw-bold" len="11" lang="fa" style="">.</span>
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

                  <div class="card-body" len="3428">

<div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column" len="3311">
 
<div class="mb-xl-0 pb-3 text-center " len="2944">
    <div class="d-flex svg-illustration  align-items-center gap-2 mb-4" len="2644">
      <span class="h2 mb-0 app-brand-text  fw-bold" len="11" lang="fa" style="">قرارداد</span>
    </div>
  </div>


</div>

</div>


                  <hr class="my-0" len="0">







                  <?php
                  $shomare_sakhtar = 0;
                        $Query_selc="SELECT*from sakhtar where (kind_gharardad = '$kind_gharardad' )ORDER BY radif ASC LIMIT 200";
   if($Result_selc=mysqli_query($Link,$Query_selc)){
 while($q_selc=mysqli_fetch_array($Result_selc)){ 
  $kind_bandg=$q_selc['id_radif'];
  $shomare_sakhtar++;
  ?>

<br>
<strong ><?php echo $shomare_sakhtar."-". $q_selc['name']; ?></strong>

<br>

                  <?php
                $yes_mota="n";
								$shomare=0;
								$Query_list="SELECT*from band_gharardad where (code_gharardad = '$code' AND  code_sakhtar = '$kind_bandg' )ORDER BY radif,i_band_g ASC LIMIT 1000";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list_band_g=mysqli_fetch_array($Result_list)){
	 $shomare++;
$matn_band1 = $q_list_band_g['matn_band']."<br>";
$matn_band_m = preg_replace_callback('/#(.*?)#/', function($mmoots)use($code,$Link){

//------------------------------------------------
$replacement="";

$Query_list="SELECT*from dade where ( code_gharardad = '$code' AND id_motaghayer = '$mmoots[1]'  )ORDER BY i_dade DESC LIMIT 1";
if($Result_list=mysqli_query($Link,$Query_list)){
while($q_list_band_g=mysqli_fetch_array($Result_list)){
  $yes_mota="y";
  $replacement=$q_list_band_g['meghdar'];
}}
  if($replacement=="" ){$replacement= "#".$mmoots[1]."#";}
//------------------------------------------------
return $replacement;



},$matn_band1);
echo $shomare_sakhtar."-".$shomare."   " ; 
echo $matn_band_m;
	 ?>



 <?php }} }}?>
<br><br><br>



<div class="card-body" len="3428">

<div class="text-center d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column" len="3311">
 
<div len="1610">
<h5 len="14" lang="fa" style="">طرف اول <br> امضا </h5>
  </div>

  <div len="1610">
<h5 len="14" lang="fa" style="">طرف دوم <br> امضا </h5>
  </div>

</div>

</div>



                </div>
              </div>
            </div>
            




        </div>
        <!-- Content wrapper -->


</div>


    </div>
	    </div>



                        
