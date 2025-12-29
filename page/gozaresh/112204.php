<?php

// دریافت مقادیر فیلترها
$faal      = str_p('faal');
$tarikh_1  = str_p('tarikh_1');
$tarikh_2  = str_p('tarikh_2');
$code_ppp  = str_p('code_ppp');

// شرایط اولیه – فقط کاربران با kind = 'bi'
$shart = " kb.kind = 'software' ";

// فیلتر نوع فعالیت
if ($faal != "" && $faal != "all") {
    $shart .= " AND k.faal = '$faal' ";
}

// فیلتر تاریخ از
if ($tarikh_1 != "") {
    $shart .= " AND k.tarikh_s >= '$tarikh_1' ";
}

// فیلتر تاریخ تا
if ($tarikh_2 != "") {
    $shart .= " AND k.tarikh_s <= '$tarikh_2' ";
}


// فیلتر تاریخ تا
if ($code_ppp  != "") {
    $shart .= " AND k.code_p = '$code_ppp' ";
}



// کوئری اصلی با JOIN
$Query_list = "
    SELECT 
        k.*,
        kb.name AS karbar_name,
        kb.kind AS karbar_kind
    FROM karkerd k
    JOIN karbar kb 
        ON kb.code_p = k.code_p
    WHERE $shart
    ORDER BY k.i_karkerd DESC
    LIMIT 2000
";

$Result_list = mysqli_query($Link, $Query_list);
$shomare = 0;
$all_zaman = 0;

?>

<div class="row gx-3">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-body">

                <form method="post" action="?page=run_gozaresh&code=112204">

                    <div class="row gx-3">

                        <!-- فعالیت -->
                        <div class="col-lg-2 col-sm-3 col-6">
                            <div class="mb-3">
                                <label class="form-label" for="faal">فعالیت</label>
                                <select class="form-select" name="faal" id="faal">
                                    <option selected value="">همه</option>
                                    <option value="y">کاری</option>
                                    <option value="a">آموزش</option>
                                    <option value="j">جلسات</option>
                                    <option value="z">تایم آزاد</option>
                                    <option value="m">مرخصی</option>
                                </select>
                            </div>
                        </div>

                        <!-- تاریخ از -->
                        <div class="col-lg-2 col-sm-3 col-6">
                            <div class="mb-3">
                                <label class="form-label" for="tarikh_1">تاریخ از</label>
                                <input type="text" class="form-control" id="tarikh_1" name="tarikh_1" >
                            </div>
                        </div>

                        <!-- تاریخ تا -->
                        <div class="col-lg-2 col-sm-3 col-6">
                            <div class="mb-3">
                                <label class="form-label" for="tarikh_2">تاریخ تا</label>
                                <input type="text" class="form-control" id="tarikh_2" name="tarikh_2" >
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-6">
                            <div class="mb-3">
                                <label class="form-label" for="tarikh_2">کد پرسنلی</label>
                                <input type="text" class="form-control" id="code_ppp" name="code_ppp" >
                            </div>
                        </div>
                        
                        
                    </div>

                    <div class="card-footer">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary">اعمال فیلتر</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<!-- جدول نمایش -->
<div class="col-xxl-12">
    <div class="card mb-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام کاربر</th>
                            <th>دسته فعالیت</th>
                            <th>شرکت</th>
                            <th>تاریخ شروع</th>
                            <th>مدت زمان</th>
                            <th>فعال</th>
                            <th>متن</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        
        $Query_list = "
    SELECT 
        k.*,
        kb.name AS karbar_name,
        kb.code_p AS karbar_code,
        kb.kind AS karbar_kind
    FROM karkerd k
    JOIN karbar kb 
        ON kb.code_p = k.code_p
    WHERE $shart
    ORDER BY k.i_karkerd DESC
    LIMIT 2000
";

$Result_list = mysqli_query($Link, $Query_list);
$shomare = 0;
$all_zaman = 0;

                
                        
                        
                        if ($Result_list) {
                            while ($q = mysqli_fetch_array($Result_list)) {
                                $shomare++;
                                $all_zaman += $q['zaman'];
                        ?>
                            <tr>
                                <td><?= $shomare ?></td>
                                <td><?= $q['karbar_name'] ?></td>
                                <td><?= $q['karbar_code'] ?></td>
                                <td><?= $q['daste'] ?></td>
                                <td><?= $q['mortabet'] ?></td>
                                <td><?= $q['tarikh_s'] . "-" . $q['saat_s'] ?></td>
                                <td><?= $q['zaman'] ?></td>

                                <td>
                                    <?php if ($q['faal'] == "y") { ?>
                                        <span class="badge border border-success text-success">کاری</span>
                                    <?php } elseif ($q['faal'] == "a") { ?>
                                        <span class="badge border border-info text-info">آموزش</span>
                                    <?php } elseif ($q['faal'] == "j") { ?>
                                        <span class="badge border border-warning text-warning">جلسات</span>
                                    <?php } elseif ($q['faal'] == "z") { ?>
                                        <span class="badge border border-warning text-warning">تایم آزاد</span>
                                    <?php } elseif ($q['faal'] == "m") { ?>
                                        <span class="badge border border-warning text-warning">مرخصی</span>
                                    <?php } ?>
                                </td>

                                <td><?= $q['matn'] ?></td>
                            </tr>

                        <?php
                            }
                        }
                        ?>
                    </tbody>

                    <tbody>
                        <tr>
                            <td>#</td>
                            <td colspan="3"></td>
                            <td>جمع:</td>
                            <td><?= $all_zaman ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

