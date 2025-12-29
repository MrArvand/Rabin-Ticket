<?php
$name_fild=str_p('mpost2');
$code_bakhash=str_p('mpost3');
?>
<div class="row">
 <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
					  <th>#</th>
                        <th>محل بخش در قرارداد</th>
                      </tr>
                    </thead>
                    <tbody>

                    <td><?php echo "1"; ?></td>
                        <td><button 
data-bs-dismiss="modal" onclick="setfild('a','<?php echo $name_fild; ?>')" class="btn btn-success btn-xs">
ابتدای متن
</button></td>

</tr>
					 <?php
								$shomare=1;
								$Query_list="SELECT*from sakhtar where (vaziat = 'y' AND  kind_gharardad = '$code_bakhash')ORDER BY radif ASC LIMIT 200";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_model=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>
                      <tr>
                        <td><?php echo $shomare; ?></td>
                        <td><button 
data-bs-dismiss="modal" onclick="setfild('<?php echo $q_model['radif']; ?>','<?php echo $name_fild; ?>')" class="btn btn-success btn-xs">
بعد از 
<?php echo $q_model['name']; ?>
</button></td>
                      </tr>
					  
   <?php }} ?>					  
                      
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
			  
			   </div>
          </div>


		      
     