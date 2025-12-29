<div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
<?php include('page/ui/panel_g.php'); ?>
              </div>
</div></div>
         <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Default -->
              <div class="row">
              <div class="card mb-4">
                <h5 class="">افزودن فایل و مستندات به قرار داد </h5>
                <form  method="post"   action="?page=s_file_gha" class="card-body" enctype="multipart/form-data">
<span class="text-info">هر گونه مستندات جهت بایگانی در این بخش قابل افزودن می باشد </span><br>
<span class="text-info"> pdf - jpge - jpg - rar - zip - doc - docx   تا حجم 5 مگابایت </span>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="name_file_g">عنوان فایل </label>
                     
                      <input type="text" name="name_file_g" id="name_file_g" class="form-control text-start" dir="ltr" placeholder="">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="pass_file"> پسورد فایل </label>
                     
                      <input type="password" name="pass_file" id="pass_file" class="form-control text-start" dir="ltr" placeholder="">
                    </div>

                    <div class="mb-3">
                        <label for="file_for_g" class="form-label"
                          >فایل / پیوست </label
                        >
                        <input name="file_for_g" class="form-control" type="file" id="file_for_g" />
                      </div>
          </div>

                  <hr class="my-4 mx-n4">
                  <div class="row g-3">


  </div>



                  <div class="pt-4">
				  
				  										<button type="submit"  class="btn btn-primary me-sm-3 me-1" >ثبت و بارگزاری </button>

                    <button type="reset" class="btn btn-warning">انصراف</button>
                  </div>
                  <input type="hidden" name="code_g" id="code_g" value="<?php echo $code; ?>">
                </form>
              </div>

    </div>
	    </div>


    

<!-- list yadavari -->
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Default -->
    <div class="row">
        <div class="card mb-4">

            <!-- Row grouping -->
                <h5 class="card-header"> لیست فایل ها و مستندات  مرتبط با قرار داد  </h5>
                <div class="card-datatable table-responsive">
                    <table class="dt-row-grouping table table-bordered">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="fw-bold">عنوان فایل</th>
                            <th class="fw-bold"> نوع فایل </th>
                            <th class="fw-bold">DOWNLOAD</th>
                            <th class="fw-bold">وضعیت</th>

                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $shomare=0;
                        $Query_list="SELECT*from file_gharardad where (code_g = '$code' )ORDER BY i_file DESC LIMIT 200";
                        if($Result_list=mysqli_query($Link,$Query_list)){
                            while($q_list=mysqli_fetch_array($Result_list)){
                                $shomare++;
                                ?>
                                <tr>
                                    <td><?php echo $shomare; ?></td>
                                    <td><span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $q_list['kind_file']; ?>"><?php echo $q_list['name']; ?></span></td>
                                    <td><?php echo $q_list['kind_file']; ?></td>
                                    <td><a href="files/doc/<?php echo $q_list['code'].".".$q_list['kind_file']; ?>"  target="_blank">DOWNLOAD</a></td>
                                    <td>
                                        <?php  if($q_list['vaziat']=="a")echo"ثبت شد"; else echo"غیر فعال"; ?>
                                    </td>
                                </tr>

                            <?php }} ?>

                        </tbody>

                        <tfoot>
                        <tr>
                        <th></th>
                            <th class="fw-bold">عنوان فایل</th>
                            <th class="fw-bold"> نوع فایل </th>
                            <th class="fw-bold">DOWNLOAD</th>
                            <th class="fw-bold">وضعیت</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            <!--/ Row grouping -->



        </div>

    </div>
</div>
