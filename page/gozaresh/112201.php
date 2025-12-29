<?php

$faal=str_p('faal');
$daste=str_p('daste');
$sherkat=str_p('sherkat');
$tarikh_1=str_p('tarikh_1');
$tarikh_2=str_p('tarikh_2');


?>
 <div class="row gx-3">
              <div class="col-sm-12">
                <div class="card mb-3">
                  <div class="card-body">



                  <form  method="post"   action="?page=run_gozaresh&code=112201"  enctype="multipart/form-data">

                          
                    <!-- Row starts -->
                    <div class="row gx-3">
                        <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                          <label class="form-label" for="faal">فعالیت</label>
                          <select class="form-select" name="faal" id="faal">
                            <option  selected value="y">کاری</option>
                            <option  selected value="">همه</option>
							<option value="a">آموزش</option>
							<option value="j">جلسات</option>
							<option value="z">تایم آزاد</option>
							<option value="m">مرخصی</option>
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
                            <option  value="n">بدون دسته بندی</option>   
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
                            <option  value="n">غیر مرتبط</option>
                            <option selected value="">همه موارد</option>
                          </select>
                        </div>
                      </div>


                      <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                            
                          <label class="form-label" for="tarikh_1">تاریخ از</label>
                          <input  
                          type="text" value="" class="form-control" id="tarikh_1"  name="tarikh_1"  readonly>
                        </div>
                      </div>
                      
                      
                      
                                            <div class="col-lg-2 col-sm-3 col-6">
                        <div class="mb-3">
                            
                          <label class="form-label" for="tarikh_2">تاریخ تا</label>
                          <input  
                          type="text" value="" class="form-control" id="tarikh_2"  name="tarikh_2"  readonly>
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
            </div>
            
            


          
          
          
           <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered m-0">
                        <thead>
                          <tr>
                            <th>#</th>
                             <th>نام کاربر</th>
                            <th>دسته فعالیت</th>
                             <th>شرکت</th>
                            <th>تاریخ شروع</th>
                            <th>مدت زمان</th>
                            <th>فعال</th>
                            <th>متن</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
								$shomare=0;

	$shart= " i_karkerd > 0 "   ;
								if($faal!="0" AND $faal !="all" AND  $faal !="" ) {$shart= $shart."AND faal = '$faal' "   ;}
								if($daste!="0" AND $daste !="all"  AND $daste !="" ){ $shart= $shart."AND daste = '$daste' "   ;}
								if($sherkat!="0" AND $sherkat !="all"  AND $sherkat !="" ){ $shart= $shart."AND mortabet = '$sherkat' "   ;}
								if($tarikh_1!="0" AND $tarikh_1 !="all"  AND $tarikh_1 !=""){ $shart= $shart."AND tarikh_s > '$tarikh_1' "   ;}
								if($tarikh_2!="0" AND $tarikh_2 !="all"  AND $tarikh_2 !="" ){ $shart= $shart."AND tarikh_s < '$tarikh_2' "   ;}
								
								
								
								
          $Query_list="SELECT*from karkerd where ($shart)ORDER BY i_karkerd DESC LIMIT 2000";
        
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_kkk=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>
                          <tr>
                          <td><?php echo $shomare; ?></td>
                           <td><?php echo $q_kkk['name_karbar']; ?></td>
                          <td><?php echo $q_kkk['daste']; ?></td>
                          <td><?php echo $q_kkk['mortabet']; ?></td>
                          <td><?php echo $q_kkk['tarikh_s']."-".$q_kkk['saat_s']; ?></td>
                          <td><?php $all_zaman=$all_zaman+$q_kkk['zaman']; echo $q_kkk['zaman']; ?></td>
<td>
<?php if($q_kkk['faal']=="y"){?><span class="badge border border-success text-success">کاری</span>
<?php }if($q_kkk['faal']=="n"){?><span class="badge border border-info text-info">غیر کاری</span>
<?php }if($q_kkk['faal']=="a"){?><span class="badge border border-success text-success">آموزش</span>
<?php }if($q_kkk['faal']=="j"){?><span class="badge border border-warning text-warning">جلسات</span>     
<?php }if($q_kkk['faal']=="z"){?><span class="badge border border-warning text-warning">تایم آزاد</span>
<?php }if($q_kkk['faal']=="m"){?><span class="badge border border-warning text-warning">مرخصی</span>
<?php } ?>                    </td>
                            <td><?php echo $q_kkk['matn']; ?></td>
                          </tr>
                          <?php }} ?> 
							
                        </tbody>
                        
                                                                        <tbody>
                  <tr>
                          <td>#</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><?php echo $all_zaman; ?></td>
<td>
                  </td>
                            <td></td>
                          </tr>
                 
							
                        </tbody>
                        
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          