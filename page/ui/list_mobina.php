<?php
$kind=str_g('kind'); 
$faal=str_p('faal');
$daste=str_p('daste');
$sherkat=str_p('sherkat');
$tarikh_1=str_p('tarikh_1');
$tarikh_2=str_p('tarikh_2');
$sn_ticket=str_p('sn_ticket'); ?>
 <div class="row gx-3">
              <div class="col-sm-12">
                <div class="card mb-3">
                  <div class="card-body">



                  <form  method="post"   action="?page=list_mobina">

                          
                    <!-- Row starts -->
                    <div class="row gx-3">
                        <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                          <label class="form-label" for="faal">وضعیت</label>
                          <select class="form-select" name="faal" id="faal">
                            <option  selected value="">همه</option>
                            <option value="a">ثبت اولیه</option>
                            <option value="m">درحال بررسی</option
                            <option value="k">انجام شده</option>
							<option value="b">بسته شده</option>
							<option value="c">کنسل شده</option>
							<option value="t">بررسی مجدد</option>
                          </select>
                        </div>
                      </div>


                     <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                          <label class="form-label" for="daste">دپارتمان فعالیت</label>
                          <select class="form-select" name="daste" id="daste">
                          <?php

$Query_dep="SELECT*from departman where (vaziat = 'y') ORDER BY name ASC LIMIT 200";
if($Result_dep=mysqli_query($Link,$Query_dep)){
while($q_dep=mysqli_fetch_array($Result_dep)){

?>
                            <option value="<?php echo $q_dep['id']; ?>"><?php echo $q_dep['name']; ?> - [<?php echo $q_dep['id']; ?>]</option>
<?php }} ?>       
                            <option  selected  value="">همه موارد </option>
                          </select>
                        </div>
                      </div>

                           <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                          <label class="form-label" for="sherkat">مرتبط با شرکت</label>
                          <select class="form-select" name="sherkat" id="sherkat">
                          <?php

								$Query_sherkat="SELECT*from sherkatha where (1)ORDER BY name DESC LIMIT 200";
   if($Result_sherkat=mysqli_query($Link,$Query_sherkat)){
 while($q_sherkat=mysqli_fetch_array($Result_sherkat)){

	 ?>
                            <option value="<?php echo $q_sherkat['code']; ?>"><?php echo $q_sherkat['name']; ?></option>
<?php }} ?>
                            <option selected value="">همه موارد</option>
                          </select>
                        </div>
                      </div>


                      <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                            
                          <label class="form-label" for="tarikh_1"> تاریخ از</label>
                          <input  
                          type="text" value="" class="form-control" id="tarikh_1"  name="tarikh_1" >
                        </div>
                      </div>
                      
                      
                      
                                            <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                            
                          <label class="form-label" for="tarikh_2"> تاریخ تا</label>
                          <input  
                          type="text" value="" class="form-control" id="tarikh_2"  name="tarikh_2" placeholder="<?php echo $tarikh; ?>">
                        </div>
                      </div>
                      
                                                           <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                            
                          <label class="form-label" for="tarikh_2">شماره تیکت</label>
                          <input  
                          type="text" value="" class="form-control" id="sn_ticket"  name="sn_ticket">
                        </div>
                      </div>       



                    <!-- Row ends -->

                  </div>
                  <div class="card-footer">
                    <div class="d-flex gap-2 justify-content-end">
                      <button  type="submit"  class="btn btn-primary">اعمال فیلتر</button>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            </div>

            
     
             <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered m-0">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>اولویت</th>
                            <th>شماره تیکت</th>
                            <th>عنوان</th>
                            <th>دپارتمان</th>
                            <th>کاربر ارسال</th>
                            <th>وضعیت</th>
                            <th>کاربر پذیرش</th>
                            <th>تاریخ ارسال</th>
                             <th></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                        
                        
                        							$shomare=0;
  

	 
	 

	      	$shart= " ( code_p_karbar_anjam = '25662' || code_p_karbar_anjam = '26519'  || code_p_karbar_anjam = '26383' || 
	      	 code_p_karbar_anjam = '1100116'   ||
	      	  code_p_karbar_anjam = '1100113'   ||
	      	   code_p_karbar_anjam = '1100119'   ||
	      	code_p_karbar_anjam = '26621'   ||   code_p_karbar_anjam = '1100100' ) AND i_ticket > 0 "   ;
	      
			
								if($faal!="0" AND $faal !="all" AND  $faal !="" ) {$shart= $shart."AND vaziat = '$faal' "   ;}
								if($daste!="0" AND $daste !="all"  AND $daste !="" ){ $shart= $shart."AND daste = '$daste' "   ;}
								if($sherkat!="0" AND $sherkat !="all"  AND $sherkat !="" ){ $shart= $shart."AND code_sherkat = '$sherkat' "   ;}
								if($tarikh_1!="0" AND $tarikh_1 !="all"  AND $tarikh_1 !=""){ $shart= $shart."AND tarikh_sabt > '$tarikh_1' "   ;}
								if($tarikh_2!="0" AND $tarikh_2 !="all"  AND $tarikh_2 !="" ){ $shart= $shart."AND tarikh_sabt < '$tarikh_2' "   ;}
								if($sn_ticket!="0" AND $sn_ticket !="all"  AND $sn_ticket !="" ){ $shart= $shart."AND code like '%$sn_ticket%' "   ;}
								
								if($kind!="0" AND $kind !="all" AND  $kind !="" ) {$shart= $shart."AND daste = '$kind' "   ;}							
          $Query_list="SELECT*from ticket where ($shart)ORDER BY i_ticket DESC LIMIT 2000";
	 
	 
	    if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 
	 $cod_tiket_in = $q_list['code'];
	 
	 
	 
	 
	 
	 
	 
	 ?>
                          <tr >
                          <td><?php echo $shomare; ?></td>
                            <td><?php
							if($q_list['olaviat']=="1"){?><span class="btn btn-danger"> ضروری </span><?php }
if($q_list['olaviat']=="2"){?><span class="btn btn-warning">متوسط</span> <?php }
if($q_list['olaviat']=="3"){?><span class="btn btn-info">معمولی</span> <?php }
if($q_list['olaviat']=="4"){?><span class="btn btn-secondary">پایین</span> <?php } ?>
</td>
                            <td><span title=""><?php echo $q_list['code']; ?></span><br><?php echo $q_list['i_ticket']; ?></td>
                            <td  
<?php 
$pasokh=0;
$monhh="مسئول پاسخگویی به";
$Query_pasokh="SELECT*FROM pasokh where ( code_ticket = '$cod_tiket_in'   AND matn NOT like '%$monhh%'  AND oksee = 'n' )";
   if($Result_pasokh=mysqli_query($Link,$Query_pasokh)){
 while($q_ticket2=mysqli_fetch_array($Result_pasokh)){
$pasokh++;

}}

 if($pasokh > 1){?>
  class="text-warning" <?php } ?> >
                                <?php echo $q_list['titr']; ?><br>
                            <small class="text-info"><?php echo $q_list['name_sherkat']; ?></small>
                            </td>
                            <td><?php echo $pasokh; ?> - <?php echo $q_list['name_daste']; ?></td>
                            <td><?php echo $q_list['name_karbar']; ?></td>
                            <td>
<?php if($q_list['vaziat']=="a"){?><span class="badge border border-danger text-danger">ثبت اولیه</span>
<?php }if($q_list['vaziat']=="m"){?><span class="badge border border-info text-info">درحال بررسی</span>
<?php }if($q_list['vaziat']=="w"){?><span class="badge border border-primary text-primary">روی میز</span>
<?php }if($q_list['vaziat']=="b"){?><span class="badge border border-success text-success">بسته شده</span>
<?php }if($q_list['vaziat']=="k"){?><span class="badge border border-success text-success">انجام شد</span>
<?php }if($q_list['vaziat']=="t"){?><span class="badge border border-warning text-warning">بررسی مجدد</span>     
<?php }if($q_list['vaziat']=="c"){?><span class="badge border border-warning text-warning">کنسل شده</span>  <?php } ?>                    </td>
                            <td><?php echo $q_list['name_karbar_anjam']; ?></td>
                            <td><?php echo $q_list['tarikh_sabt']; ?> - <?php echo $q_list['saat_sabt']; ?> </td>
                            <td>
                                 <div class="my-2">

                      <?php
				
								$code700 = $q_list['code'];
								$Query_pasokh="SELECT*from pasokh where (code_ticket = '$code700' )ORDER BY i_pasokh DESC LIMIT 200";
   if($Result_pasokh=mysqli_query($Link,$Query_pasokh)){
 while($q_ticket2=mysqli_fetch_array($Result_pasokh)){

	 $code_pasokh=$q_ticket2['code'];

   ?>

                        <div class="activity-block d-flex position-relative">
                          <img src="assets/images/user3.png" class="img-4x me-3 rounded-circle activity-user"
                            alt="Admin Dashboard" />
                          <div class="mb-3">
                            <h5>
                            <?php if($q_ticket2['name_karbar_sabt']=="y"){ ?>  
                            <i class="fs-3 bi bi-eye"></i><?php }else{ ?>
                            <i class="fs-3 bi bi-eye-slash"></i>
                            <?php } ?><?php echo $q_ticket2['name_karbar_sabt']; ?></h5>
                            <p><?php echo nl2br($q_ticket2['matn']); ?></p>
                            
                            <?php
                            
    	$Query_fpasokh="SELECT*from file_pasokh where (code_ticket = '$code' AND code_pasokh = '$code_pasokh' )ORDER BY i_file DESC LIMIT 10";
                                            if($Result_fpasokh=mysqli_query($Link,$Query_fpasokh)){
                                          while($q_fticket=mysqli_fetch_array($Result_fpasokh)){
                                            ?>
                            <p><a href="files/peyvast/<?php echo $q_fticket['code_file'].".".$q_fticket['kind']; ?>" class=" text-info"><i
                            class="fs-3 bi bi-paperclip 2h-1"></i> <?php echo $q_fticket['titr']; ?> </a> </p>
                            <?php }} ?>
                            <span class="badge bg-primary"><?php echo $q_ticket2['tarikh_sabt']; ?> - <?php echo $q_ticket2['saat_sabt']; ?> </span>
                          
                          </div>  
                        </div><hr>
<?php }} ?>

                      </div>
                           </td>   
                           
                           
                           
                           <td>
                           <a href="?page=info_ticket&code=<?php  echo $q_list['code']; ?>"><span class="btm border border-success text-success">بیشتر</span></a>
                            </td>
                            
                          </tr>
                          <tr>
                              
                              
                              
                              
                          </tr>
                          <?php }} 
                      
                          
                          ?> 
                         
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          