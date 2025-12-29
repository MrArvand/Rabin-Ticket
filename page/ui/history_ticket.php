          
          
          
          
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
                            <th>بیشتر</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
								$shomare=0;
								$Query_list="SELECT*from ticket where (vaziat = 'b' || vaziat = 'c' )ORDER BY i_ticket DESC LIMIT 1000";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>
                          <tr>
                          <td><?php echo $shomare; ?></td>
                            <td><?php
							if($q_list['olaviat']=="1"){?><span class="btn btn-danger"> ضروری </span><?php }
if($q_list['olaviat']=="2"){?><span class="btn btn-warning">متوسط</span> <?php }
if($q_list['olaviat']=="3"){?><span class="btn btn-info">معمولی</span> <?php }
if($q_list['olaviat']=="4"){?><span class="btn btn-secondary">پایین</span> <?php } ?>
</td>
                            <td><span title=""><?php echo $q_list['code']; ?></span><br><?php echo $q_list['i_ticket']; ?></td>
                            <td><?php echo $q_list['titr']; ?></td>
                            <td><?php echo $q_list['name_daste']; ?></td>
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
                           <a href="?page=info_ticket&code=<?php  echo $q_list['code']; ?>"><span class="btm border border-success text-success">بیشتر</span></a>
                            </td>
                            
                          </tr>
                          <?php }} ?> 
                         
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          