 <?php
 $coded=str_g('coded');
 ?>
 
              <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">
                      
<form  method="post"   action="?page=search_academi" class="card-body">                        
                   <div class="row g-3">

                    <div class="col-md-3">
                 <span data-bs-toggle="modal" data-bs-target="#full_page"  class="btn btn-light btn-sm">
انتخاب دسته آموزشی
                  </span>
   </div>              

               
   <div class="col-md-6"> <input type="text"  name="search_t" id="search_t" class="form-control" dir="ltr" placeholder="" required > </div>
     <div class="col-md-3"><button type="submit"  class="btn btn-primary me-sm-3 me-1" >جستجو</button> </div>

               
 </div></form>
 <h4 class="text-center">محتوای آموزشی در گروه صنعتی رهباریان</h4>
 
 <strong class="text-success text-center">
     محتوای بارگزاری شد در : 
 <?php if($coded=="" || $coded==0 ){ 
     echo" تمام دسته بندی ها به ترتیب بارگزاری ";
 }else{ 
 $Query_dep25="SELECT*from  daste_mohtava where ( id_daste = '$coded' ) ORDER BY name_daste ASC LIMIT 200";
if($Result_dep25=mysqli_query($Link,$Query_dep25)){
while($q_dep25=mysqli_fetch_array($Result_dep25)){
    echo $q_dep25['name_f_daste']."   >   ".$q_dep25['name_daste'];
}}
 } ?>
 </strong>
 
 
 
 
   </div>
     </div>
       </div>
 
             <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">
                  <div class="row gx-3">
                 
                 
                                      <?php
								$shomare=0;
                    if($coded=="" || $coded==0 ){
								$Query_list="SELECT*from mohtava where (vaziat = 'y')ORDER BY i_mohtava DESC LIMIT 1000 ";
                    }else{
                       	$Query_list="SELECT*from mohtava where (vaziat = 'y' AND cat1 = '$coded')ORDER BY i_mohtava DESC LIMIT 1000 "; 
                    }
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>  
                 
                 
                 
                 
          <div class="col-sm-3 col-12">
              <div class="card mb-3">
                <div class="card-header">
<a href="?page=info_mohtava&code=<?php echo $q_list['code']; ?>" ><strong class="card-title mb-2"><?php echo $q_list['titr']; ?></strong></a><br>
             
                  <span class="small card-subtitle  mb-0"><?php echo $q_list['name_cat2']; ?> / <?php echo $q_list['name_cat1']; ?> </span>
                </div>
                  <a href="?page=info_mohtava&code=<?php echo $q_list['code']; ?>" ><img src="../files/poster/<?php echo $q_list['poster']; ?>" class="img-fluid" alt="<?php echo $q_list['titr']; ?>" />
                  </a>
                <div class="card-body position-relative pt-4">
                  <span class="btn btn-light card-btn-floating">
<?php
if($q_list['kind']=="1"){?>عمومی<?php }
if($q_list['kind']=="2"){?>کارشناسی <?php }
if($q_list['kind']=="3"){?>تخصصی<?php }  ?>            </span>
                  <p>
                    <ul>
                        <li> محور آموزش  : <?php echo $q_list['name_daste']; ?> </li>
                        <li>نوع محتوا : <?php
                        if($q_list['link']==""){
                         echo $q_list['kind_file'];}else{
                         echo"ویدئو";
                         }?> </li>
                    </ul>  
                      
                  </p>
                  <hr>
                </div>
              </div>
            </div>       
                
             <?php }} ?>   
                
                 
                 
                 </div>
                 
                  </div>
                </div>
              </div>
          
          
          
          
          
          
          
          
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
                      <a href="?page=academi&coded=<?php echo $q_dep2['id_daste']; ?>"><?php echo"----------------> "; echo $q_dep2['name_daste']; ?> </a>
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
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          