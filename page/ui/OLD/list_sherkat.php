
            <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
            <div class="card mb-4">


            <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
        <div class="button-wrapper">

           <a href="?page=new_sherkat"> <button type="button" class="btn btn-outline-success mb-3">
              <i class="mdi mdi-reload d-block d-sm-none"></i>
              <span class="d-none d-sm-block">ثبت شرکت جدید</span>
            </button></a>
</div>

          
        </div>
      </div>


            <div class="table-responsive border rounded my-4">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th class="  fw-medium">#</th>
                <th class="  fw-medium">نام شرکت</th>
                <th class="  fw-medium">کد مدیر</th>
                <th class="  fw-medium">شماره ثبت</th>
                <th class="  fw-medium">...</th>
              </tr>
            </thead>
            
            <tbody>
            <?php



								$shomare=0;
								$Query_list="SELECT*from sherkatha where (1)ORDER BY i_sherkat DESC LIMIT 1000";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list_band=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>

              <tr>
                <td class=""><?php echo $shomare; ?></td>
                <td class=" "><?php echo $q_list_band['name']; ?></td>
                <td class=" "><?php echo $q_list_band['code_modiramel']; ?></td>
                <td class=" "><?php echo $q_list_band['sn_sabt_sherkat']; ?></td>
                <td class=" "><a class="btn btn-outline-secondary" href="?page=info_sherkat&code=<?php echo $q_list_band['code']; ?>"> ...</a></td>
              </tr>
              <?php }} ?>
            </tbody>

          </table>
        </div>

          </div>
          </div>
	        </div>



                        
