<div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
<?php include('page/ui/panel_g.php'); ?>
              </div>
<div>
      <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
                <h5 class="">حذف قرارداد </h5>
                <?php if($vaziat_gharardad =="a"){ ?>
                <form  method="post"   action="?page=s_new_gha" class="card-body">
                  <h6 class="fw-normal">آیا از حذف این قرارداد اطمینان دارید ؟ </h6>




                  <div class="pt-4">
				  
				  										<button type="submit"  class="btn btn-primary me-sm-3 me-1" > تایید حذف قرارداد</button>

                    <button type="reset" class="btn btn-warning">انصراف</button>
                  </div>
                </form>
                <?php }else{ ?>
                  
                  <div class="alert alert-solid-warning" role="alert">قرارداد در وضعیت حذف قرار ندارد</div>
                
                <?php } ?>
              </div>

    </div>
	    </div>


   