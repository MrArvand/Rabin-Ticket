                                       <?php
								$shomare=0;
                    
								$Query_list="SELECT*from mohtava where (code = '$code' )ORDER BY i_mohtava DESC LIMIT 1000 ";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>  
              <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">
 
                 <span data-bs-toggle="modal" data-bs-target="#full_page"  class="btn btn-danger card-btn">
انتخاب دسته آموزشی
                  </span>
 
 <h4><?php echo $q_list['titr']; ?></h4>
   </div>
     </div>
       </div>
 
             <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">
                  <div class="row gx-3">
                 
          <div class="col-sm-12 col-12">
              <div class="card mb-3">
                <div class="card-header">
                  <h6 class="card-title mb-2"></h6>
                  <span type="button" class="btn btn-light btn-sm"></span>
                  <span class="card-subtitle text-muted mb-0"><?php echo $q_list['name_cat1']; ?> / <?php echo $q_list['name_cat2']; ?> </span>
                </div>
                <?php if($q_list['link'] !=""){ ?>
                    <video class="img-fluid text-center" controls width="auto" height="400px">
  <source src="<?php echo $q_list['link']; ?>"  poster="../files/poster/<?php echo $q_list['poster']; ?>">
<?php echo $q_list['titr']; ?>
</video>
                <?php }else{?>
                <img src="../files/poster/<?php echo $q_list['poster']; ?>" class="img-fluid" alt="<?php echo $q_list['titr']; ?>" />
                <?php } ?>
                <div class="card-body position-relative pt-4">
                  <a href="#" class="btn btn-light card-btn-floating">
<?php
if($q_list['kind']=="1"){?>عمومی<?php }
if($q_list['kind']=="2"){?>کارشناسی <?php }
if($q_list['kind']=="3"){?>تخصصی<?php }  ?>            </a>
                  <p>
                    <ul>
                        <li> محور آموزش  : <?php echo $q_list['name_daste']; ?> </li>
                        <li>نوع محتوا : <?php echo $q_list['kind_file']; ?> </li>
                        <li>گروه کاری  : <?php echo $q_list['sherkat']; ?> </li>
                        
                        <?php if($q_list['name_file'] !=""){?>
                        <li>دانلود فایل : 
                     <a href="../files/amozesh/<?php echo $q_list['name_file']; ?>.<?php echo $q_list['kind_file']; ?>" target="_blank"> <span class="btn btn-sm btn-success">دانلود</span></a> </li>
                        
                        <?php } ?>
                    </ul>  
                      
                  </p>
                  <p><?php echo nl2br( $q_list['matn']); ?> </p>
                  
                                     <?php if($q_list['link'] !=""){ ?>
                   <br>
                  <a href="<?php echo $q_list['link']; ?>" target="_blank" ><span class="text-warning"> در صورتی که آموزش تصویر برای شما اجرا نگردید اینجا کلیک نمایید </span></a>
                  <?php } ?>
                  
                  
                </div>
                
                
                <div class="card-footer">
<span class=""> تاریخ بارگزاری : <?php echo $q_list['tarikh_sabt']; ?></span><br>                    
<span class=""> تهیه کننده آموزش : <?php echo $q_list['nevisande']; ?></span>
                </div>
              </div>
            </div>       
                
           
                
                 
                 
                 </div>
                 
                  </div>
                </div>
              </div>
          
          
          
          
          
           <?php }} ?>    
          
          
            <div class="modal fade" id="full_page" tabindex="-1"
                      aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                      <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title h4" id="exampleModalFullscreenLabel">گروه بندی آموزش </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              
                                <div class="sidebarMenuScroll">
          <ul class="sidebar-menu">      
                              
                              
                                         <li class="treeview">
              <a href="#!">
                <i class="bi bi-code-square"></i>
                <span class="menu-text">گروه بندی آموزش</span>
              </a>
              <ul class="treeview-menu">
                  
<?php
$this_id_daste="666";

$Query_dep="SELECT*from  daste_mohtava where ( fader = 'y') ORDER BY name_daste ASC LIMIT 200";
if($Result_dep=mysqli_query($Link,$Query_dep)){
while($q_dep=mysqli_fetch_array($Result_dep)){
    
$t_id_daste  =  $q_dep['id_daste'];
?>

                <li>
                  <a href="#!"><?php echo $q_dep['name_daste']; ?> - [<?php echo $t_id_daste; ?>] <i class="bi bi-caret-left-fill"></i>
                  </a>
          <ul class="treeview-menu">
         
                                                     <?php

$Query_dep2="SELECT*from  daste_mohtava where ( id_f_daste = '$t_id_daste' ) ORDER BY name_daste ASC LIMIT 200";
if($Result_dep2=mysqli_query($Link,$Query_dep2)){
while($q_dep2=mysqli_fetch_array($Result_dep2)){

?>
                    <li>
                      <a href="#!"><?php echo"----------------> "; echo $q_dep2['name_daste']; ?> </a>
                    </li>
                 
                  <?php }} ?>
                 </ul></li>
                <?php }} ?>
              </ul>
            </li>
          </ul>
        </div> 
                              
                              
                              
                              
                              
                              
                              
                              
                              
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                          </div>
                        </div>
                      </div>
                    </div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          