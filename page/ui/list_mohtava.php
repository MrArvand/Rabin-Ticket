<?php $kind=str_g('kind'); ?>
<a href="?page=new_mohtava"><span class="btn btn-success">ثبت محتوای جدید</span></a>
             <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered m-0">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>نوع محتوا</th>
                            <th>دسته بندی</th>
                            <th>شرکت</th>
                            <th>دپارتمان</th>
                            <th>عنوان</th>
                            <th>بیشتر</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
								$shomare=0;
                    
								$Query_list="SELECT*from mohtava where (1)ORDER BY i_mohtava DESC LIMIT 1000 ";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>
                          <tr>
                          <td><?php echo $shomare; ?></td>
                            <td><?php
if($q_list['kind']=="1"){?><span class="btn btn-danger"> عمومی </span><?php }
if($q_list['kind']=="2"){?><span class="btn btn-warning">کارشناسی</span> <?php }
if($q_list['kind']=="3"){?><span class="btn btn-info">تخصصی</span> <?php }  ?>
</td>
                            <td><span title=""><?php echo $q_list['name_cat1']; ?></span><br><?php echo $q_list['name_cat2']; ?></td>
                            <td><?php echo $q_list['sherkat']; ?></td>
                            <td><?php echo $q_list['daste']; ?></td>
                            <td><?php echo $q_list['titr']; ?></td>
                            <td>
                           <a href="?page=info_mohtava&code=<?php  echo $q_list['code']; ?>"><span class="btm border border-success text-success">بیشتر</span></a>
                            </td>
                            
                          </tr>
                          <?php }} ?> 
                         
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          