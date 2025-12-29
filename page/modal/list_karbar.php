<?php
$name_fild=str_p('mpost2');
$code_salon=str_p('mpost3');
?>
<div class="row">
 <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
					  <th>#</th>
                        <th>نام کاربر</th>
                        <th>دسته</th>
                      </tr>
                    </thead>
                    <tbody>
					 <?php
								$shomare=0;
								$Query_list="SELECT*from karbar where (vaziat = 'y')ORDER BY i_karbar DESC LIMIT 200";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_model=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>
                      <tr>
                        <td><?php echo $shomare; ?></td>
                        <td><?php $meghdar_model =$q_model['code_p']; echo $q_model['name']; ?></td>
                        <td><button 
data-bs-dismiss="modal" onclick="setfild('<?php echo $meghdar_model; ?>','<?php echo $name_fild; ?>')" class="btn btn-success btn-xs">انتخاب</button></td>
                      </tr>
					  
   <?php }} ?>					  
                      
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
			  
			   </div>
          </div>


		      
     