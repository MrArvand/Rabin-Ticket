<?php $kind=str_g('kind'); ?>
             <div class="col-xxl-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered m-0">
                        <thead>
                          <tr>
                            <th>نام گزارش</th>
                            <th>.ضعیت</th>
                            <th>اجرا</th>
                          </tr>
                        </thead>
                        <tbody>
<?php if( $code_p_run =="24277" ){ ?>
                          <tr>
                          <td>کارکرد های تمام پرسنل بر اساس انتخاب فیلتر</td>
                          <td>مدیریتی</td>
                          <td><a href="?page=run_gozaresh&code=112201"><span class="btm border border-success text-success">اجرا</span></a></td>
                          </tr>
 
<?php } ?> 
<?php if( $code_p_run =="24277" ){ ?>
                           <tr>
                          <td>کارکرد های تیم  bi</td>
                          <td>مدیریتی</td>
                          <td><a href="?page=run_gozaresh&code=112202"><span class="btm border border-success text-success">اجرا</span></a></td>
                          </tr>
                          
<?php } ?> 
<?php if( $code_p_run =="24277" ||  $code_p_run =="25662"   ){ ?>                          
                              <tr>
                          <td>کارکرد های تیم bpms</td>
                          <td>مدیریتی</td>
                          <td><a href="?page=run_gozaresh&code=112203"><span class="btm border border-success text-success">اجرا</span></a></td>
                          </tr>
 
 <?php } ?> 
<?php if( $code_p_run =="24277"  ){ ?>

                                      <tr>
                          <td>کارکرد های تیم نرم افزار</td>
                          <td>مدیریتی</td>
                          <td><a href="?page=run_gozaresh&code=112204"><span class="btm border border-success text-success">اجرا</span></a></td>
                          </tr>
  <?php } ?> 
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          