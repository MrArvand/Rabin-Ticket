 <div class="row">
                <!-- کنترل های فرم -->
                <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header">ایجاد دسته بندی قرارداد ها </h5>
					<span class="">دسته بندی های مانند خرید دستگاه ، مشارکت ساختمانی ، مشارکت ساخت ، مشاوره و ... </span>
                    <form  method="post"   action="?page=s_new_daste_g" class="card-body">
                    <div
                      class="card-body demo-vertical-spacing demo-only-element"
                    >
                      <div class="form-floating form-floating-outline mb-4">
                        <input
                          type="text"
                          class="form-control"
                          id="name_daste"
                          name="name_daste"
                          placeholder="نام دسته را وارد کنید"
                        />
                        <label for="exampleFormControlInput1">نام دسته</label>
                      </div></div>
                    <button type="submit"  class="btn btn-primary me-sm-3 me-1" >ثبت   </button>
</form>
                  </div>
                </div>
	


                <!-- کنترل های فرم -->
                <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header">بخش های یک قرارداد</h5>
					<span class="">
					مانند عمومی ، مشخصات ، موضوع قرارداد ، و ... 
					</span>
                    <form  method="post"   action="?page=s_new_item_g" class="card-body">
                    <div
                      class="card-body demo-vertical-spacing demo-only-element"
                    >
					
					
					                      <div class="form-floating form-floating-outline mb-4">
                        <select
                          class="form-select"
                          id="name_kind"
                          name="name_kind"
                          aria-label="Default select example"
                        >
                        <?php
                        $Query_selc="SELECT*from kind_gharardad where (vaziat = 'y')ORDER BY i_gharardad DESC LIMIT 200";
   if($Result_selc=mysqli_query($Link,$Query_selc)){
 while($q_selc=mysqli_fetch_array($Result_selc)){ ?>
                       <option value="<?php echo $q_selc['id_gharardad']; ?>" ><?php echo $q_selc['name_gharardad']; ?></option>
                          <?php }} ?>
                        </select>
                        <label for="name_kind"
                          >انتخاب دسته</label
                        >
                      </div>
					  
					  
					  
                      <div class="form-floating form-floating-outline mb-4">
                        <input
                          type="text"
                          class="form-control"
                          id="name_bakhash"
                          name="name_bakhash"
                          placeholder=""
                        />
                        <label for="name_bakhash">نام بخش</label>
                      </div>
					  
                      <div class="form-floating form-floating-outline mb-4">
                      <label class="form-label" for="janamay"> جانمایی </label>
                      <input data-bs-toggle="modal" data-bs-target="#date_select3"  
                       onclick="openmodal('badane_modal_3','select_janamay','janamay',document.getElementById(`name_kind`).value,'0','0')" 
                       name="janamay" id="janamay" class="form-control input-sm" type="text" readonly>

                    </div>

			
                    </div>
                    <button type="submit"  class="btn btn-primary me-sm-3 me-1" >ثبت   </button>
</form>
                  </div>
                </div>

				
				
				                </div>



                        <div class="modal" id="date_select3" tabindex="-1" aria-hidden="true" >
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">انتخاب</h4>
                  </div>
                  <div id="badane_modal_3" class="modal-body">
                    <p>در حال بارگزاری لیست ... </p>
                  </div>
                  <div class="modal-footer">

                  </div>
                </div>
              </div>
            </div>	