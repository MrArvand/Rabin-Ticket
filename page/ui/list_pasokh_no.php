    <div class="card mb-3">
                  <div class="card-header">
                    <h5 class="card-title">لیست پاسخ های خوانده نشده </h5>
                  </div>
                  <div class="card-body">
                    <div class="">
                      <div class="my-2">

                      <?php
                      $monhh="مسئول پاسخگویی به";
								$shomare=0;
								$Query_pasokh="SELECT*from pasokh where ((code_karbar2 = '$code_p_run' || code_karbar_sabt = '$code_p_run' ) AND code_karbar2 != '' )ORDER BY i_pasokh DESC LIMIT 200";
   if($Result_pasokh=mysqli_query($Link,$Query_pasokh)){
 while($q_ticket2=mysqli_fetch_array($Result_pasokh)){
	 $shomare++;
	 $code_pasokh=$q_ticket2['code'];

   ?>

                        <div class="activity-block d-flex position-relative">
                          <img src="assets/images/user3.png" class="img-4x me-3 rounded-circle activity-user"
                            alt="Admin Dashboard" />
                          <div class="mb-3">
                            <h5>
                            <?php if($q_ticket2['oksee']=="y"){ ?>  
                            <i class="fs-3 bi bi-eye"></i><?php }else{ ?>
                            <i class="fs-3 bi bi-eye-slash"></i>
                            <?php } ?><?php echo $q_ticket2['name_karbar_sabt']; ?> -  <?php echo $q_ticket2['name_karbar2']; ?></h5>
                            <p><?php echo nl2br($q_ticket2['matn']); ?></p>
                            

                            <span class="badge bg-primary"><?php echo $q_ticket2['tarikh_sabt']; ?> - <?php echo $q_ticket2['saat_sabt']; ?> </span>
                            <a href="?page=info_ticket&code=<?php  echo $q_ticket2['code']; ?>"><span class="btn btn-success">بررسی</span>
                          </div>  
                        </div><hr>
<?php }} ?>

                      </div>
                    </div>
                  </div>
                </div>
               