<?php
$faal = str_p('faal');
$daste = str_p('daste');
$sherkat = str_p('sherkat');
$tarikh_1 = str_p('tarikh_1');
$tarikh_2 = str_p('tarikh_2');
$sn_ticket = str_p('sn_ticket');
?>
<style>
    .modern-card {
        background: var(--bs-card-bg, #fff);
        border: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .modern-card-header {
        background: var(--bs-tertiary-bg, #f8f9fa);
        padding: 20px 24px;
        border-bottom: 1px solid var(--bs-border-color, #dee2e6);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modern-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--bs-body-color);
    }

    .modern-card-body {
        padding: 24px;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--bs-secondary-color);
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        border-color: var(--bs-border-color);
        font-size: 0.9rem;
        background-color: var(--bs-body-bg);
    }

    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        border-color: var(--bs-primary);
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        background: var(--bs-tertiary-bg);
        font-weight: 600;
        font-size: 0.7rem;
        color: var(--bs-secondary-color);
        padding: 10px;
        border-bottom: 2px solid var(--bs-border-color);
        white-space: nowrap;
    }
    
    .modern-table tbody td {
        padding: 10px;
        vertical-align: middle;
        border-bottom: 1px solid var(--bs-border-color);
        font-size: 0.75rem;
        color: var(--bs-body-color);
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .modern-table tbody tr:hover {
        background-color: var(--bs-tertiary-bg);
    }

    .status-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .status-pill::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background-color: currentColor;
    }

    .badge-soft-primary { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .badge-soft-success { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .badge-soft-warning { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .badge-soft-danger { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .badge-soft-info { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
    .badge-soft-secondary { background: rgba(108, 117, 125, 0.1); color: #6c757d; }

    .user-avatar-sm {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: bold;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
        border: none;
        color: white;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        font-family: 'IranYekanNum', sans-serif;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        transition: all 0.2s;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(13, 110, 253, 0.3);
        color: white;
    }

    .btn-outline-modern {
        border: 1px solid var(--bs-border-color);
        background: transparent;
        color: var(--bs-body-color);
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        font-family: 'IranYekanNum', sans-serif;
        transition: all 0.2s;
    }

    .btn-outline-modern:hover {
        background: var(--bs-tertiary-bg);
        border-color: var(--bs-gray-400);
    }
    
    .ticket-title-link {
        color: var(--bs-body-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem;
        transition: color 0.2s;
    }
    
    .ticket-title-link:hover {
        color: var(--bs-primary);
    }
</style>

<div class="row gx-3">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-funnel-fill text-primary"></i>
                    فیلتر تیکت‌ها
                </h5>
            </div>
            <div class="modern-card-body">
                <form method="post" action="?page=list_ticket">
                    <div class="row gx-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="faal">وضعیت</label>
                                <select class="form-select" name="faal" id="faal">
                                    <option value="" <?php if ($faal === "" || $faal === "0" || $faal === "all") { echo "selected"; } ?>>همه</option>
                                    <option value="a" <?php if ($faal === "a") { echo "selected"; } ?>>ثبت اولیه</option>
                                    <option value="m" <?php if ($faal === "m") { echo "selected"; } ?>>درحال بررسی</option>
                                    <option value="k" <?php if ($faal === "k") { echo "selected"; } ?>>انجام شده</option>
                                    <option value="b" <?php if ($faal === "b") { echo "selected"; } ?>>بسته شده</option>
                                    <option value="c" <?php if ($faal === "c") { echo "selected"; } ?>>کنسل شده</option>
                                    <option value="t" <?php if ($faal === "t") { echo "selected"; } ?>>بررسی مجدد</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="daste">دپارتمان فعالیت</label>
                                <select class="form-select" name="daste" id="daste">
                                    <?php
                                    $Query_dep = "SELECT * from departman where (vaziat = 'y') ORDER BY name ASC LIMIT 200";
                                    if ($Result_dep = mysqli_query($Link, $Query_dep)) {
                                        while ($q_dep = mysqli_fetch_array($Result_dep)) {
                                    ?>
                                    <option value="<?php echo $q_dep['id']; ?>" <?php if ($daste === $q_dep['id']) { echo "selected"; } ?>>
                                        <?php echo $q_dep['name']; ?> - [<?php echo $q_dep['id']; ?>]
                                    </option>
                                    <?php } } ?>
                                    <option value="" <?php if ($daste === "" || $daste === "0" || $daste === "all") { echo "selected"; } ?>>همه موارد</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="sherkat">مرتبط با شرکت</label>
                                <select class="form-select" name="sherkat" id="sherkat">
                                    <?php
                                    $Query_sherkat = "SELECT * from sherkatha where (1) ORDER BY name DESC LIMIT 200";
                                    if ($Result_sherkat = mysqli_query($Link, $Query_sherkat)) {
                                        while ($q_sherkat = mysqli_fetch_array($Result_sherkat)) {
                                    ?>
                                    <option value="<?php echo $q_sherkat['code']; ?>" <?php if ($sherkat === $q_sherkat['code']) { echo "selected"; } ?>>
                                        <?php echo $q_sherkat['name']; ?>
                                    </option>
                                    <?php } } ?>
                                    <option value="" <?php if ($sherkat === "" || $sherkat === "0" || $sherkat === "all") { echo "selected"; } ?>>همه موارد</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="sn_ticket">شماره تیکت</label>
                                <input type="text" value="<?php echo htmlspecialchars($sn_ticket, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" id="sn_ticket" name="sn_ticket" placeholder="جستجو...">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">بازه تاریخی</label>
                                <div class="input-group">
                                    <input type="text" value="<?php echo htmlspecialchars($tarikh_1, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" id="tarikh_1" name="tarikh_1" placeholder="از تاریخ">
                                    <span class="input-group-text bg-transparent border-end-0 border-start-0 text-muted">-</span>
                                    <input type="text" value="<?php echo htmlspecialchars($tarikh_2, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" id="tarikh_2" name="tarikh_2" placeholder="تا تاریخ">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                        <button type="button" class="btn-outline-modern" onclick="window.location.href='?page=list_ticket'">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> بازنشانی
                        </button>
                        <button type="submit" class="btn-primary-modern">
                            <i class="bi bi-funnel-fill me-1"></i> اعمال فیلتر
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row gx-3">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-list-task text-primary"></i>
                    لیست تیکت‌های من
                </h5>
            </div>
            <div class="modern-card-body p-0">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اولویت</th>
                                <th>کد تیکت</th>
                                <th style="min-width: 250px;">عنوان</th>
                                <th>دپارتمان</th>
                                <th>فرستنده</th>
                                <th>وضعیت</th>
                                <th>پاسخگو</th>
                                <th>تاریخ ثبت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $shomare = 0;
                        
                        // Query Building
                        $shart = " code_p_karbar = '$code_p_run' AND i_ticket > 0 ";
                        
                        if ($faal != "0" AND $faal != "all" AND $faal != "") {
                            $shart = $shart . "AND vaziat = '$faal' ";
                        }
                        if ($daste != "0" AND $daste != "all" AND $daste != "") {
                            $shart = $shart . "AND daste = '$daste' ";
                        }
                        if ($sherkat != "0" AND $sherkat != "all" AND $sherkat != "") {
                            $shart = $shart . "AND code_sherkat = '$sherkat' ";
                        }
                        if ($tarikh_1 != "0" AND $tarikh_1 != "all" AND $tarikh_1 != "") {
                            $shart = $shart . "AND tarikh_sabt > '$tarikh_1' ";
                        }
                        if ($tarikh_2 != "0" AND $tarikh_2 != "all" AND $tarikh_2 != "") {
                            $shart = $shart . "AND tarikh_sabt < '$tarikh_2' ";
                        }
                        if ($sn_ticket != "0" AND $sn_ticket != "all" AND $sn_ticket != "") {
                            $sn_ticket_escaped = mysqli_real_escape_string($Link, $sn_ticket);
                            $shart = $shart . "AND code like '%$sn_ticket_escaped%' ";
                        }
                        
                        $Query_list = "SELECT * from ticket where ($shart) ORDER BY i_ticket DESC LIMIT 2000";

                        if ($Result_list = mysqli_query($Link, $Query_list)) {
                            if (mysqli_num_rows($Result_list) > 0) {
                                while ($q_list = mysqli_fetch_array($Result_list)) {
                                    $shomare++;
                                    $cod_tiket_in = $q_list['code'];
                                    
                                    // Count unread responses for ticket creator
                                    // Ticket creators see ALL unread responses (not filtered by code_karbar2)
                                    $pasokh = 0;
                                    $monhh = "مسئول پاسخگویی به";
                                    $Query_pasokh = "SELECT * FROM pasokh where (code_ticket = '$cod_tiket_in' AND matn NOT like '%$monhh%' AND oksee = 'n')";
                                    if ($Result_pasokh = mysqli_query($Link, $Query_pasokh)) {
                                        $pasokh = mysqli_num_rows($Result_pasokh);
                                    }
                        ?>
                            <tr>
                                <td><span class="fw-bold text-muted"><?php echo $shomare; ?></span></td>
                                <td>
                                    <?php if ($q_list['olaviat'] == "1") { ?>
                                        <span class="status-pill badge-soft-danger">ضروری</span>
                                    <?php } elseif ($q_list['olaviat'] == "2") { ?>
                                        <span class="status-pill badge-soft-warning">متوسط</span>
                                    <?php } elseif ($q_list['olaviat'] == "3") { ?>
                                        <span class="status-pill badge-soft-info">معمولی</span>
                                    <?php } elseif ($q_list['olaviat'] == "4") { ?>
                                        <span class="status-pill badge-soft-secondary">پایین</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <span class="fw-bold font-monospace"><?php echo $q_list['code']; ?></span>
                                </td>
                                <td>
                                    <a href="?page=info_ticket&code=<?php echo $q_list['code']; ?>" class="ticket-title-link">
                                        <?php echo $q_list['titr']; ?>
                                    </a>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <small class="text-muted"><i class="bi bi-building me-1"></i><?php echo $q_list['name_sherkat']; ?></small>
                                        <?php if ($pasokh > 0) { ?>
                                            <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">
                                                <?php echo $pasokh; ?> پاسخ جدید
                                            </span>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo $q_list['name_daste']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar-sm">
                                            <?php echo mb_substr($q_list['name_karbar'], 0, 1, 'UTF-8'); ?>
                                        </div>
                                        <span><?php echo $q_list['name_karbar']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $status_class = 'badge-soft-secondary';
                                    $status_text = 'نامشخص';
                                    
                                    switch($q_list['vaziat']) {
                                        case 'a': $status_class = 'badge-soft-danger'; $status_text = 'ثبت اولیه'; break;
                                        case 'm': $status_class = 'badge-soft-info'; $status_text = 'درحال بررسی'; break;
                                        case 'b': $status_class = 'badge-soft-success'; $status_text = 'بسته شده'; break;
                                        case 'k': $status_class = 'badge-soft-success'; $status_text = 'انجام شد'; break;
                                        case 't': $status_class = 'badge-soft-warning'; $status_text = 'بررسی مجدد'; break;
                                        case 'c': $status_class = 'badge-soft-secondary'; $status_text = 'کنسل شده'; break;
                                    }
                                    ?>
                                    <span class="status-pill <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($q_list['vaziat'] == "a") { ?>
                                        <span class="text-muted">منتظر ارجاع</span>
                                    <?php } else { ?>
                                        <?php if ($q_list['name_karbar_anjam']) { ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar-sm" style="background: linear-gradient(135deg, var(--bs-orange), var(--bs-warning));">
                                                    <?php echo mb_substr($q_list['name_karbar_anjam'], 0, 1, 'UTF-8'); ?>
                                                </div>
                                                <small><?php echo $q_list['name_karbar_anjam']; ?></small>
                                            </div>
                                        <?php } else { ?>
                                            <span class="text-muted">-</span>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span><?php echo $q_list['tarikh_sabt']; ?></span>
                                        <small class="text-muted"><?php echo $q_list['saat_sabt']; ?></small>
                                    </div>
                                </td>
                                <td>
                                    <a href="?page=info_ticket&code=<?php echo $q_list['code']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-family: 'IranYekanNum', sans-serif;">
                                        مشاهده
                                    </a>
                                </td>
                            </tr>
                        <?php 
                                } // End while
                            } else {
                        ?>
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    <p class="mb-0">هیچ تیکتی با شرایط مورد نظر یافت نشد.</p>
                                </td>
                            </tr>
                        <?php
                            } // End if rows > 0
                        } // End query
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
