<div class="container-xxl flex-grow-1 container-p-y">
              <div class="row gy-4 mb-4">
                <!-- Sales Overview-->
                <div class="col-lg-6">
                  <div class="card h-100">
                    <div class="card-header">
                      <div class="d-flex justify-content-between">
                        <h4 class="mb-2">تعداد قرارداد های ثبت شده </h4>
                        <div class="dropdown">
                          <button
                            class="btn p-0"
                            type="button"
                            id="salesOverview"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="mdi mdi-dots-vertical mdi-24px"></i>
                          </button>
                          <div
                            class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="salesOverview"
                          >
                            <a class="dropdown-item" href="index.php"
                              >نو سازی</a
                            >
                          </div>
                        </div>
                      </div>
                      <div class="d-flex align-items-center">
                        <small class="me-2"> مجموع کل قرار داد های ثبت شده  </small>
                        <div class="d-flex align-items-center text-success">
					 <?php
								$ttgha=0;
								$tt_takmil=0;
								$tt_ejra=0;
								$tt_end=0;
								 $tt_shekayat=0;
								$Query_list="SELECT*from gharardad where (1)ORDER BY i_gharardad DESC LIMIT 200";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list=mysqli_fetch_array($Result_list)){
	 $ttgha++;


	if($q_list['vaziat']=="a")$tt_takmil++;
	if($q_list['vaziat']=="d")$tt_ejra++;
	if($q_list['vaziat']=="y")$tt_end++;
	
	if($q_list['vaziat']=="s")$tt_shekayat++;
								
								
								
   }}
	 ?>
                          <p class="mb-0"><?php echo $ttgha; ?></p>
                        </div>
                      </div>
                    </div>
                    <div
                      class="card-body d-flex justify-content-between flex-wrap gap-3"
                    >
                      <div class="d-flex gap-3">
                        <div class="avatar">
                          <div class="avatar-initial bg-label-primary rounded">
                            <i class="mdi mdi-account-outline mdi-24px"></i>
                          </div>
                        </div>
                        <div class="card-info">
                          <h4 class="mb-0"><?php echo $tt_takmil; ?></h4>
                          <small class="text-muted">در حال تکمیل</small>
                        </div>
                      </div>
                      <div class="d-flex gap-3">
                        <div class="avatar">
                          <div class="avatar-initial bg-label-warning rounded">
                            <i class="mdi mdi-poll mdi-24px"></i>
                          </div>
                        </div>
                        <div class="card-info">
                          <h4 class="mb-0"><?php echo $tt_ejra; ?></h4>
                          <small class="text-muted">در حال اجرا</small>
                        </div>
                      </div>
                      <div class="d-flex gap-3">
                        <div class="avatar">
                          <div class="avatar-initial bg-label-info rounded">
                            <i class="mdi mdi-trending-up mdi-24px"></i>
                          </div>
                        </div>
                        <div class="card-info">
                          <h4 class="mb-0"><?php echo $tt_end; ?></h4>
                          <small class="text-muted">پایان یافته</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Sales Overview-->

                <!-- Ratings -->
				
				
				            <?php

								$Query_list="SELECT*from sherkatha where (1)ORDER BY i_sherkat DESC LIMIT 1000";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list_band=mysqli_fetch_array($Result_list)){

$code_fco=$q_list_band['code'];

	 ?>
	 

                <div class="col-lg-3 col-sm-6">
                  <div class="card h-100">
                    <div class="row">
                      <div class="col-6">
                        <div class="card-body">
                          <div class="card-info mb-3 py-2 mb-lg-1 mb-xl-3">	 <a href="?page=info_sherkat&code=<?php echo $q_list_band['code']; ?>">
                            <h5 class="mb-3 mb-lg-2 mb-xl-3 text-nowrap">
                            <?php echo $q_list_band['name']; ?>
                            </h5>
</a>
                          </div>
                          <div class="d-flex align-items-end flex-wrap gap-1">
                            <h4 class="mb-0 me-2">فعال</h4>
							<?php

								$t_sherkat=0;

								$Query_list2="SELECT*from gharardad where (sherkat = '$code_fco' )ORDER BY i_gharardad DESC LIMIT 20000";
   if($Result_list2=mysqli_query($Link,$Query_list2)){
 while($q_list=mysqli_fetch_array($Result_list2)){
$t_sherkat++;
								
								
								
   }}
	 ?>	

                            <small class="text-success"> <?php echo $t_sherkat; ?>  قرارداد </small>
                          </div>
                        </div>
                      </div>
                      <div
                        class="col-6 text-end d-flex align-items-end justify-content-center"
                      >
                        <div
                          class="card-body pb-0 pt-3 position-absolute bottom-0"
                        >
                          <img
                            src="../../assets/img/illustrations/misc-error-object.png"
                            alt="Ratings"
                            width="95"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Ratings -->
   <?php }} ?>

                <!--/ جلسات -->

              

                <!--/ Total Visits -->

                <!-- Sales This Months -->
                <div class="col-lg-6 col-sm-12">
                  <div class="card">
                    <div class="card-header">
                      <h5 class="mb-0">قرارداد های حقوقی شده</h5>
                    </div>
                    <div class="card-body">
                      <div class="card-info">
                        <p class="text-muted mb-2">دارای پرونده</p>
                        <h5 class="mb-0">
						<?php echo $tt_shekayat; ?>
						</h5>
                      </div>
                      <div id="saleThisMonth"></div>
                    </div>
                  </div>
                </div>
				
				
				
				</div>
				</div>