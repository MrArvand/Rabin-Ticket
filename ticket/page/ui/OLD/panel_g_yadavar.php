
<!-- add yadavari -->
<div class="container-xxl flex-grow-1 container-p-y" >
    <!-- Default -->
    <div class="row">
        <div class="card mb-4">
            <?php include('page/ui/panel_g.php');
            $name_karbar_run = $_SESSION['name'];
            ?>
        </div>
        <div>


            <div class="container-xxl flex-grow-1 container-p-y" >
                <!-- Default -->
                <div class="row">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="">افزودن یادآوری جدید</h5>
                        </div>

                        <form  method="post"   action="?page=s_panel_g_yadavar&code=<?php echo $code ?>" class="card-body">
                            <h6 class="fw-normal"></h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="name_pro">عنوان یادآوری</label>
                                    <input type="text" name="titr" id="titr" class="form-control text-start" dir="ltr" placeholder="" >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="tarikh_sabt">تاریخ یادآوری</label>
                                    <input type="text" name="tarikh_elam"  class="form-control"  id="flatpickr-date" >
                                </div>

                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="tarikh_sabt">نام کاربر ایجاد کننده</label>
                                    <input type="text"  name="karbar_sabt"  class="form-control"  id="karbar_sabt" value="<?php echo $name_karbar_run ?>" disabled >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="name_pro">نام کاربر هدف</label>
                                    <input type="text" data-bs-toggle="modal" data-bs-target="#date_select2" onclick="openmodal('badane_modal_2','list_karbar','name_karbar','0','0','0')" name="name_karbar" id="name_karbar" class="form-control text-start" dir="ltr" placeholder="" readonly>
                                </div>
                            </div>

                            <hr class="my-4 mx-n4">
                            <div class="row g-3">

                                <div class="col-md-12">
                                    <label class="form-label" for="matn">متن یادآوری</label>
                                    <textarea name="matn" class="form-control" rows="5" placeholder="  "></textarea>

                                </div>


                            </div>


                            <div class="pt-4">

                                <button type="submit"  class="btn btn-primary me-sm-3 me-1"  >افزودن یادآوری</button>
                                <button type="reset" class="btn btn-warning">انصراف</button>
                            </div>
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
                <h5 class="card-header">لیست یادآوری ها </h5>
				<?php echo $code; ?>
                <div class="card-datatable table-responsive">
                    <table class="dt-row-grouping table table-bordered">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="fw-bold">عنوان یادآوری</th>
                            <th class="fw-bold">تاریخ یادآوری</th>
                            <th class="fw-bold">نام کاربر ایجادکننده</th>
                            <th class="fw-bold">نام کاربر هدف</th>
                            <th class="fw-bold">وضعیت</th>

                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $shomare=0;
                        $Query_list="SELECT*from yadavari where (code_g = '$code' )ORDER BY i_elam DESC LIMIT 200";
                        if($Result_list=mysqli_query($Link,$Query_list)){
                            while($q_list=mysqli_fetch_array($Result_list)){
                                $shomare++;
                                ?>
                                <tr>
                                    <td><?php echo $shomare; ?></td>
                                    <td><span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $q_list['matn']; ?>"><?php echo $q_list['titr']; ?></span></td>
                                    <td><?php echo $q_list['tarikh_elam']; ?></td>
                                    <td><?php echo $q_list['karbar_sabt']; ?></td>
                                    <td><?php echo $q_list['name_karbar']; ?></td>
                                    <td>
                                        <?php  if($q_list['vaziat']=="a")echo"ثبت شد"; else echo"غیر فعال"; ?>
                                    </td>
                                </tr>

                            <?php }} ?>

                        </tbody>

                        <tfoot>
                        <tr>
                            <th></th>
                            <th class="fw-bold">عنوان یادآوری</th>
                            <th class="fw-bold">تاریخ یادآوری</th>
                            <th class="fw-bold">نام کاربر ایجادکننده</th>
                            <th class="fw-bold">نام کاربر هدف</th>
                            <th class="fw-bold">وضعیت</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            <!--/ Row grouping -->



        </div>

    </div>
</div>

        </div>


    </div>
</div>


<div class="modal" id="date_select2" tabindex="-1" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">انتخاب</h4>
            </div>
            <div id="badane_modal_2" class="modal-body">
                <p>در حال بارگزاری لیست ... </p>
            </div>
            <div class="modal-footer">

            </div>
        </div>   /.modal-content
    </div>   /.modal-dialog
</div>









