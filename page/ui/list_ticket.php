<?php
$kind = str_g('kind');
// Get faal from POST first, then GET (for pagination), then default to empty
$faal = '';
if (isset($_POST['faal']) && $_POST['faal'] !== '') {
    $faal = str_p('faal');
} elseif (isset($_GET['faal']) && $_GET['faal'] !== '') {
    $faal = str_g('faal');
}
if ($faal === '0' || $faal === 'all') $faal = '';

$daste = '';
if (isset($_POST['daste']) && $_POST['daste'] !== '') {
    $daste = str_p('daste');
} elseif (isset($_GET['daste']) && $_GET['daste'] !== '') {
    $daste = str_g('daste');
}
if ($daste === '0' || $daste === 'all') $daste = '';

$sherkat = '';
if (isset($_POST['sherkat']) && $_POST['sherkat'] !== '') {
    $sherkat = str_p('sherkat');
} elseif (isset($_GET['sherkat']) && $_GET['sherkat'] !== '') {
    $sherkat = str_g('sherkat');
}
if ($sherkat === '0' || $sherkat === 'all') $sherkat = '';

// Get from POST first, then GET (for back navigation from ticket detail)
$tarikh_1 = '';
if (isset($_POST['tarikh_1']) && $_POST['tarikh_1'] !== '') {
    $tarikh_1 = str_p('tarikh_1');
} elseif (isset($_GET['tarikh_1']) && $_GET['tarikh_1'] !== '') {
    $tarikh_1 = str_g('tarikh_1');
}
if ($tarikh_1 === '0') $tarikh_1 = '';

$tarikh_2 = '';
if (isset($_POST['tarikh_2']) && $_POST['tarikh_2'] !== '') {
    $tarikh_2 = str_p('tarikh_2');
} elseif (isset($_GET['tarikh_2']) && $_GET['tarikh_2'] !== '') {
    $tarikh_2 = str_g('tarikh_2');
}
if ($tarikh_2 === '0') $tarikh_2 = '';

$sn_ticket = '';
if (isset($_POST['sn_ticket']) && $_POST['sn_ticket'] !== '') {
    $sn_ticket = str_p('sn_ticket');
} elseif (isset($_GET['sn_ticket']) && $_GET['sn_ticket'] !== '') {
    $sn_ticket = str_g('sn_ticket');
}
if ($sn_ticket === '0') $sn_ticket = '';

$titr = '';
if (isset($_POST['titr']) && $_POST['titr'] !== '') {
    $titr = str_p('titr');
} elseif (isset($_GET['titr']) && $_GET['titr'] !== '') {
    $titr = str_g('titr');
}
if ($titr === '0') $titr = '';

$karbar_ersal = '';
if (isset($_POST['karbar_ersal']) && $_POST['karbar_ersal'] !== '') {
    $karbar_ersal = str_p('karbar_ersal');
} elseif (isset($_GET['karbar_ersal']) && $_GET['karbar_ersal'] !== '') {
    $karbar_ersal = str_g('karbar_ersal');
}
if ($karbar_ersal === '0') $karbar_ersal = '';

$karbar_paziresh = '';
if (isset($_POST['karbar_paziresh']) && $_POST['karbar_paziresh'] !== '') {
    $karbar_paziresh = str_p('karbar_paziresh');
} elseif (isset($_GET['karbar_paziresh']) && $_GET['karbar_paziresh'] !== '') {
    $karbar_paziresh = str_g('karbar_paziresh');
}
if ($karbar_paziresh === '0') $karbar_paziresh = '';

$per_page_input = '';
if (isset($_POST['per_page']) && $_POST['per_page'] !== '') {
    $per_page_input = str_p('per_page');
} elseif (isset($_GET['per_page']) && $_GET['per_page'] !== '') {
    $per_page_input = str_g('per_page');
}
if ($per_page_input === '0') $per_page_input = '';

$page_number_input = '';
if (isset($_POST['page_number']) && $_POST['page_number'] !== '') {
    $page_number_input = str_p('page_number');
} elseif (isset($_GET['page_number']) && $_GET['page_number'] !== '') {
    $page_number_input = str_g('page_number');
}
if ($page_number_input === '0') $page_number_input = '';

$per_page_options = [25, 50, 100, 200];
if ($per_page_input === "" || $per_page_input === null) {
    $per_page_input = "50";
}

if ($per_page_input === "all") {
    $per_page = "all";
} else {
    $per_page_value = (int) $per_page_input;
    if (!in_array($per_page_value, $per_page_options, true)) {
        $per_page_value = 50;
    }
    $per_page = $per_page_value;
}

$per_page_selected_value = ($per_page === "all") ? "all" : (string) $per_page;
$page_number = (int) $page_number_input;
if ($page_number < 1) {
    $page_number = 1;
}
?>
<style>
    .modern-card {
        background: var(--color-bg-card, var(--bs-card-bg, #fff));
        border: 1px solid var(--color-border-primary, var(--bs-border-color, #dee2e6));
        border-radius: 16px;
        box-shadow: 0 4px 24px var(--color-shadow-sm, rgba(0, 0, 0, 0.06));
        overflow: hidden;
        margin-bottom: 24px;
    }

    .modern-card-header {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg, #f8f9fa));
        padding: 20px 24px;
        border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color, #dee2e6));
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

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        border-color: var(--bs-border-color);
        font-size: 0.9rem;
        background-color: var(--bs-body-bg);
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 3px var(--color-badge-primary-bg, rgba(13, 110, 253, 0.15));
        border-color: var(--color-primary, var(--bs-primary));
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
        background-color: var(--color-bg-hover, var(--bs-tertiary-bg));
    }

    .modern-table tbody tr:hover td {
        background-color: var(--color-bg-hover, var(--bs-tertiary-bg)) !important;
        color: var(--color-text-primary, var(--bs-body-color)) !important;
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

    .badge-soft-primary {
        background: var(--color-badge-primary-bg, rgba(13, 110, 253, 0.1));
        color: var(--color-badge-primary-text, #0d6efd);
    }

    .badge-soft-success {
        background: var(--color-badge-success-bg, rgba(25, 135, 84, 0.1));
        color: var(--color-badge-success-text, #198754);
    }

    .badge-soft-warning {
        background: var(--color-badge-warning-bg, rgba(255, 193, 7, 0.1));
        color: var(--color-badge-warning-text, #ffc107);
    }

    .badge-soft-danger {
        background: var(--color-badge-danger-bg, rgba(220, 53, 69, 0.1));
        color: var(--color-badge-danger-text, #dc3545);
    }

    .badge-soft-info {
        background: var(--color-badge-info-bg, rgba(13, 202, 240, 0.1));
        color: var(--color-badge-info-text, #0dcaf0);
    }

    .badge-soft-secondary {
        background: var(--color-badge-secondary-bg, rgba(108, 117, 125, 0.1));
        color: var(--color-badge-secondary-text, #6c757d);
    }

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
        background: linear-gradient(135deg, var(--color-primary, var(--bs-primary)), var(--color-info, var(--bs-info)));
        border: none;
        color: var(--color-white, #ffffff);
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        font-family: 'IranYekanNum', sans-serif;
        box-shadow: 0 4px 12px var(--color-shadow-md, rgba(13, 110, 253, 0.2));
        transition: all 0.2s;
    }

    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px var(--color-shadow-lg, rgba(13, 110, 253, 0.3));
        color: var(--color-white, #ffffff);
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
                <form method="post" action="?page=list_ticket" id="ticketFilterForm">
                    <div class="row gx-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="faal">وضعیت</label>
                                <select class="form-select" name="faal" id="faal">
                                    <option value="" <?php if ($faal === "" || $faal === "0" || $faal === "all") {
                                                            echo "selected";
                                                        } ?>>همه</option>
                                    <option value="a" <?php if ($faal === "a") {
                                                            echo "selected";
                                                        } ?>>ثبت اولیه</option>
                                    <option value="m" <?php if ($faal === "m") {
                                                            echo "selected";
                                                        } ?>>درحال بررسی</option>
                                    <option value="w" <?php if ($faal === "w") {
                                                            echo "selected";
                                                        } ?>>روی میز</option>
                                    <option value="k" <?php if ($faal === "k") {
                                                            echo "selected";
                                                        } ?>>انجام شده</option>
                                    <option value="b" <?php if ($faal === "b") {
                                                            echo "selected";
                                                        } ?>>بسته شده</option>
                                    <option value="c" <?php if ($faal === "c") {
                                                            echo "selected";
                                                        } ?>>کنسل شده</option>
                                    <option value="t" <?php if ($faal === "t") {
                                                            echo "selected";
                                                        } ?>>بررسی مجدد</option>
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
                                            <option value="<?php echo $q_dep['id']; ?>" <?php if ($daste === $q_dep['id']) {
                                                                                            echo "selected";
                                                                                        } ?>>
                                                <?php echo $q_dep['name']; ?> - [<?php echo $q_dep['id']; ?>]
                                            </option>
                                    <?php }
                                    } ?>
                                    <option value="" <?php if ($daste === "" || $daste === "0" || $daste === "all") {
                                                            echo "selected";
                                                        } ?>>همه موارد</option>
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
                                            <option value="<?php echo $q_sherkat['code']; ?>" <?php if ($sherkat === $q_sherkat['code']) {
                                                                                                    echo "selected";
                                                                                                } ?>>
                                                <?php echo $q_sherkat['name']; ?>
                                            </option>
                                    <?php }
                                    } ?>
                                    <option value="" <?php if ($sherkat === "" || $sherkat === "0" || $sherkat === "all") {
                                                            echo "selected";
                                                        } ?>>همه موارد</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="sn_ticket">شماره تیکت</label>
                                <input type="text"
                                    value="<?php echo htmlspecialchars($sn_ticket, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="form-control" id="sn_ticket" name="sn_ticket" placeholder="جستجو...">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="titr">عنوان</label>
                                <input type="text" value="<?php echo htmlspecialchars($titr, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="form-control" id="titr" name="titr" placeholder="جستجو...">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="karbar_ersal">کاربر ارسال کننده</label>
                                <input type="text"
                                    value="<?php echo htmlspecialchars($karbar_ersal, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="form-control" id="karbar_ersal" name="karbar_ersal" placeholder="جستجو...">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="karbar_paziresh">کاربر پذیرنده</label>
                                <input type="text"
                                    value="<?php echo htmlspecialchars($karbar_paziresh, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="form-control" id="karbar_paziresh" name="karbar_paziresh"
                                    placeholder="جستجو...">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">بازه تاریخی</label>
                                <div class="input-group">
                                    <input type="text"
                                        value="<?php echo htmlspecialchars($tarikh_1, ENT_QUOTES, 'UTF-8'); ?>"
                                        class="form-control" id="tarikh_1" name="tarikh_1" placeholder="از تاریخ">
                                    <span
                                        class="input-group-text bg-transparent border-end-0 border-start-0 text-muted">-</span>
                                    <input type="text"
                                        value="<?php echo htmlspecialchars($tarikh_2, ENT_QUOTES, 'UTF-8'); ?>"
                                        class="form-control" id="tarikh_2" name="tarikh_2" placeholder="تا تاریخ">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="per_page">تعداد نمایش</label>
                                <select class="form-select" name="per_page" id="per_page">
                                    <?php foreach ($per_page_options as $option) { ?>
                                        <option value="<?php echo $option; ?>" <?php if ($per_page_selected_value == (string) $option) {
                                                                                    echo "selected";
                                                                                } ?>>
                                            <?php echo $option; ?> مورد در صفحه
                                        </option>
                                    <?php } ?>
                                    <option value="all" <?php if ($per_page_selected_value === "all") {
                                                            echo "selected";
                                                        } ?>>نمایش همه</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="page_number" id="page_number" value="<?php echo $page_number; ?>">

                    <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top"
                        style="border-color: var(--bs-border-color) !important;">
                        <button type="button" class="btn-outline-modern"
                            onclick="window.location.href='?page=list_ticket'">
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

<script>
    (function() {
        var filterForm = document.getElementById('ticketFilterForm');
        var pageField = document.getElementById('page_number');
        if (!filterForm || !pageField) {
            return;
        }
        pageField.value = "<?php echo $page_number; ?>";

        filterForm.addEventListener('submit', function() {
            if (filterForm.dataset.keepPage === 'true') {
                filterForm.dataset.keepPage = '';
                return;
            }
            pageField.value = '1';
        });
        window.goToTicketPage = function(page) {
            if (!filterForm || !pageField) {
                return;
            }
            var targetPage = parseInt(page, 10);
            if (isNaN(targetPage) || targetPage < 1) {
                targetPage = 1;
            }
            filterForm.dataset.keepPage = 'true';
            pageField.value = targetPage;
            // Preserve all form values when submitting for pagination
            filterForm.submit();
        };
    })();
</script>

<div class="row gx-3">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bi bi-list-task text-primary"></i>
                    لیست تیکت‌ها
                </h5>
            </div>
            <div class="modern-card-body p-0">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اولویت</th>
                                <th>شماره تیکت</th>
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
                            if ($code_p_run == "24277" || $code_p_run == "25662"  || $code_p_run == "1100105"   || $code_p_run == "20612"       || $code_p_run == "23056"      || $code_p_run == "1100110" || $code_p_run == "20072" || $code_p_run == "1100056" || $code_p_run == "30613" || $code_p_run == "1064046037") {
                                $shart = " i_ticket > 0 ";
                            } else {
                                $shart = " code_p_karbar_anjam = '$code_p_run' AND i_ticket > 0 ";
                            }
                            if (!empty($faal) && $faal != "0" && $faal != "all") {
                                $faal_escaped = mysqli_real_escape_string($Link, $faal);
                                $shart = $shart . "AND ticket.vaziat = '$faal_escaped' ";
                            }
                            if ($daste != "0" and $daste != "all"  and $daste != "") {
                                $shart = $shart . "AND daste = '$daste' ";
                            }
                            if ($sherkat != "0" and $sherkat != "all"  and $sherkat != "") {
                                $shart = $shart . "AND code_sherkat = '$sherkat' ";
                            }
                            if ($tarikh_1 != "0" and $tarikh_1 != "all"  and $tarikh_1 != "") {
                                $shart = $shart . "AND tarikh_sabt > '$tarikh_1' ";
                            }
                            if ($tarikh_2 != "0" and $tarikh_2 != "all"  and $tarikh_2 != "") {
                                $shart = $shart . "AND tarikh_sabt < '$tarikh_2' ";
                            }
                            if ($sn_ticket != "0" and $sn_ticket != "all"  and $sn_ticket != "") {
                                $sn_ticket_escaped = mysqli_real_escape_string($Link, $sn_ticket);
                                $shart = $shart . "AND code like '%$sn_ticket_escaped%' ";
                            }
                            if ($titr != "0" and $titr != "all"  and $titr != "") {
                                $titr_escaped = mysqli_real_escape_string($Link, $titr);
                                $shart = $shart . "AND titr like '%$titr_escaped%' ";
                            }
                            if ($karbar_ersal != "0" and $karbar_ersal != "all"  and $karbar_ersal != "") {
                                $karbar_ersal_escaped = mysqli_real_escape_string($Link, $karbar_ersal);
                                $shart = $shart . "AND (name_karbar like '%$karbar_ersal_escaped%' OR code_p_karbar like '%$karbar_ersal_escaped%') ";
                            }
                            if ($karbar_paziresh != "0" and $karbar_paziresh != "all"  and $karbar_paziresh != "") {
                                $karbar_paziresh_escaped = mysqli_real_escape_string($Link, $karbar_paziresh);
                                $shart = $shart . "AND (name_karbar_anjam like '%$karbar_paziresh_escaped%' OR code_p_karbar_anjam like '%$karbar_paziresh_escaped%') ";
                            }

                            if ($kind != "0" and $kind != "all" and  $kind != "") {
                                $shart = $shart . "AND daste = '$kind' ";
                            }

                            // Pagination Logic
                            $total_rows = 0;
                            $total_pages = 1;
                            $start_record = 0;
                            $end_record = 0;
                            $offset = 0;

                            $Query_count = "SELECT COUNT(*) as total_rows FROM ticket where ($shart)";
                            if ($Result_count = mysqli_query($Link, $Query_count)) {
                                if ($row_count = mysqli_fetch_assoc($Result_count)) {
                                    $total_rows = (int) $row_count['total_rows'];
                                }
                            }

                            if ($per_page === "all") {
                                $page_number = 1;
                                if ($total_rows > 0) {
                                    $start_record = 1;
                                    $end_record = $total_rows;
                                }
                            } else {
                                $total_pages = ($total_rows > 0) ? (int) ceil($total_rows / $per_page) : 1;
                                if ($total_pages < 1) {
                                    $total_pages = 1;
                                }
                                if ($page_number > $total_pages) {
                                    $page_number = $total_pages;
                                }
                                $offset = ($page_number - 1) * $per_page;
                                if ($total_rows > 0) {
                                    $start_record = $offset + 1;
                                    $end_record = min($total_rows, $offset + $per_page);
                                }
                            }

                            $limit_clause = "";
                            if ($per_page !== "all") {
                                $limit_clause = " LIMIT " . $offset . ", " . $per_page;
                            }

                            // Build URL parameters for back navigation from ticket detail page
                            $list_state_params = [];
                            if (!empty($faal)) $list_state_params['faal'] = $faal;
                            if (!empty($daste)) $list_state_params['daste'] = $daste;
                            if (!empty($sherkat)) $list_state_params['sherkat'] = $sherkat;
                            if (!empty($tarikh_1)) $list_state_params['tarikh_1'] = $tarikh_1;
                            if (!empty($tarikh_2)) $list_state_params['tarikh_2'] = $tarikh_2;
                            if (!empty($sn_ticket)) $list_state_params['sn_ticket'] = $sn_ticket;
                            if (!empty($titr)) $list_state_params['titr'] = $titr;
                            if (!empty($karbar_ersal)) $list_state_params['karbar_ersal'] = $karbar_ersal;
                            if (!empty($karbar_paziresh)) $list_state_params['karbar_paziresh'] = $karbar_paziresh;
                            if ($per_page !== 50) $list_state_params['per_page'] = $per_page_selected_value;
                            if ($page_number > 1) $list_state_params['page_number'] = $page_number;
                            if (!empty($kind)) $list_state_params['kind'] = $kind;
                            $list_state_query = !empty($list_state_params) ? '&' . http_build_query($list_state_params) : '';

                            // Escape current user code for SQL
                            $code_p_run_escaped = mysqli_real_escape_string($Link, $code_p_run);
                            $monhh_escaped = mysqli_real_escape_string($Link, "مسئول پاسخگویی به");

                            // PERFORMANCE OPTIMIZED: Single query with all counts calculated via JOINs
                            // This eliminates N+1 query problem - previously each ticket row triggered additional queries
                            // Now we calculate:
                            // 1. Department name via JOIN
                            // 2. Unread counts for assigned users (pasokh_counts_assigned)
                            // 3. Unread counts for ticket creators (pasokh_counts_creator)
                            // 4. Latest response time for sorting
                            // 5. User's own tickets (creator or assigned)
                            // 6. Unassigned tickets (not cancelled)
                            $Query_list = "SELECT ticket.*, 
                                     COALESCE(NULLIF(departman.name, ''), NULLIF(ticket.name_daste, ''), ticket.daste) as display_name_daste,
                                     departman.name as department_name,
                                     CASE WHEN ticket.code_p_karbar_anjam = '$code_p_run_escaped' THEN 1 ELSE 0 END as is_assigned_to_user,
                                     CASE WHEN ticket.code_p_karbar = '$code_p_run_escaped' THEN 1 ELSE 0 END as is_ticket_creator,
                                     CASE WHEN (ticket.code_p_karbar = '$code_p_run_escaped' OR ticket.code_p_karbar_anjam = '$code_p_run_escaped') THEN 1 ELSE 0 END as is_user_ticket,
                                     CASE WHEN (ticket.code_p_karbar_anjam IS NULL OR ticket.code_p_karbar_anjam = '' OR ticket.code_p_karbar_anjam = '0') AND ticket.vaziat != 'c' THEN 1 ELSE 0 END as is_unassigned,
                                     COALESCE(pasokh_counts_assigned.new_answers_count, 0) as new_answers_assigned,
                                     COALESCE(pasokh_counts_creator.new_answers_count, 0) as new_answers_creator,
                                     CASE WHEN ticket.code_p_karbar = '$code_p_run_escaped' THEN COALESCE(pasokh_counts_creator.new_answers_count, 0) ELSE COALESCE(pasokh_counts_assigned.new_answers_count, 0) END as has_new_responses,
                                     COALESCE(latest_response.last_response_time, CONCAT(ticket.tarikh_sabt, ' ', ticket.saat_sabt)) as sort_time
                                     FROM ticket 
                                     LEFT JOIN departman ON ticket.daste = departman.id
                                     LEFT JOIN (
                                         SELECT code_ticket, COUNT(*) as new_answers_count
                                         FROM pasokh
                                         WHERE oksee = 'n' 
                                         AND matn NOT LIKE '%$monhh_escaped%'
                                         AND (code_karbar2 = '$code_p_run_escaped' OR code_karbar2 IS NULL OR code_karbar2 = '')
                                         GROUP BY code_ticket
                                     ) pasokh_counts_assigned ON ticket.code = pasokh_counts_assigned.code_ticket
                                     LEFT JOIN (
                                         SELECT code_ticket, COUNT(*) as new_answers_count
                                         FROM pasokh
                                         WHERE oksee = 'n' 
                                         AND matn NOT LIKE '%$monhh_escaped%'
                                         GROUP BY code_ticket
                                     ) pasokh_counts_creator ON ticket.code = pasokh_counts_creator.code_ticket
                                     LEFT JOIN (
                                         SELECT code_ticket, MAX(CONCAT(tarikh_sabt, ' ', saat_sabt)) as last_response_time
                                         FROM pasokh
                                         GROUP BY code_ticket
                                     ) latest_response ON ticket.code = latest_response.code_ticket
                                     WHERE ($shart) 
                                     ORDER BY is_user_ticket DESC, 
                                              is_unassigned DESC, 
                                              has_new_responses DESC, 
                                              sort_time DESC" . $limit_clause;

                            if ($Result_list = mysqli_query($Link, $Query_list)) {
                                if (mysqli_num_rows($Result_list) > 0) {
                                    while ($q_list = mysqli_fetch_array($Result_list)) {
                                        $shomare++;

                                        // PERFORMANCE: Use pre-calculated counts from the optimized JOIN query
                                        // No more N+1 queries - counts are already in $q_list
                                        $is_ticket_creator_for_count = ($q_list['is_ticket_creator'] == 1);

                                        if ($is_ticket_creator_for_count) {
                                            // Ticket creator sees ALL unread responses
                                            $pasokh = (int) $q_list['new_answers_creator'];
                                        } else {
                                            // Assigned user sees only responses directed to them
                                            $pasokh = (int) $q_list['new_answers_assigned'];
                                        }

                                        // PERFORMANCE: Category name already fetched via JOIN, no additional query needed
                                        // Prioritize department name from departman table (Persian label)
                                        $category_name = $q_list['department_name'];
                                        if (empty($category_name)) {
                                            // Fallback to ticket.name_daste if available
                                            $category_name = $q_list['name_daste'];
                                        }
                                        if (empty($category_name)) {
                                            // Last fallback to display_name_daste (which includes code as final fallback)
                                            $category_name = $q_list['display_name_daste'];
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
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold font-monospace"><?php echo $q_list['code']; ?></span>
                                                    <small class="text-muted">ID: <?php echo $q_list['i_ticket']; ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="?page=info_ticket&code=<?php echo $q_list['code']; ?><?php echo htmlspecialchars($list_state_query, ENT_QUOTES, 'UTF-8'); ?>"
                                                    class="ticket-title-link">
                                                    <?php echo $q_list['titr']; ?>
                                                </a>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <small class="text-muted"><i
                                                            class="bi bi-building me-1"></i><?php echo $q_list['name_sherkat']; ?></small>
                                                    <?php if ($pasokh > 0) { ?>
                                                        <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">
                                                            <?php echo $pasokh; ?> پاسخ جدید
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    <?php echo htmlspecialchars($category_name); ?>
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

                                                switch ($q_list['vaziat']) {
                                                    case 'a':
                                                        $status_class = 'badge-soft-danger';
                                                        $status_text = 'ثبت اولیه';
                                                        break;
                                                    case 'm':
                                                        $status_class = 'badge-soft-info';
                                                        $status_text = 'درحال بررسی';
                                                        break;
                                                    case 'w':
                                                        $status_class = 'badge-soft-primary';
                                                        $status_text = 'روی میز';
                                                        break;
                                                    case 'b':
                                                        $status_class = 'badge-soft-success';
                                                        $status_text = 'بسته شده';
                                                        break;
                                                    case 'k':
                                                        $status_class = 'badge-soft-success';
                                                        $status_text = 'انجام شد';
                                                        break;
                                                    case 't':
                                                        $status_class = 'badge-soft-warning';
                                                        $status_text = 'بررسی مجدد';
                                                        break;
                                                    case 'c':
                                                        $status_class = 'badge-soft-secondary';
                                                        $status_text = 'کنسل شده';
                                                        break;
                                                }
                                                ?>
                                                <span class="status-pill <?php echo $status_class; ?>">
                                                    <?php echo $status_text; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($q_list['name_karbar_anjam']) { ?>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="user-avatar-sm"
                                                            style="background: linear-gradient(135deg, var(--bs-orange), var(--bs-warning));">
                                                            <?php echo mb_substr($q_list['name_karbar_anjam'], 0, 1, 'UTF-8'); ?>
                                                        </div>
                                                        <small><?php echo $q_list['name_karbar_anjam']; ?></small>
                                                    </div>
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span><?php echo $q_list['tarikh_sabt']; ?></span>
                                                    <small class="text-muted"><?php echo $q_list['saat_sabt']; ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="?page=info_ticket&code=<?php echo $q_list['code']; ?><?php echo htmlspecialchars($list_state_query, ENT_QUOTES, 'UTF-8'); ?>"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1"
                                                    style="font-size: 0.75rem; font-family: 'IranYekanNum', sans-serif;">
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

                <!-- Pagination -->
                <?php
                $has_pagination = ($per_page !== "all" && $total_rows > 0 && $total_pages > 1);
                if ($has_pagination || $total_rows > 0) {
                    $display_start = ($start_record > 0) ? $start_record : 0;
                    $display_end = ($end_record > 0) ? $end_record : 0;
                ?>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 p-4 border-top"
                        style="border-color: var(--bs-border-color) !important;">
                        <div class="text-muted small">
                            نمایش <span class="fw-bold text-primary"><?php echo $display_start; ?></span> تا <span
                                class="fw-bold text-primary"><?php echo $display_end; ?></span> از <span
                                class="fw-bold text-primary"><?php echo $total_rows; ?></span> تیکت
                        </div>

                        <?php if ($has_pagination) { ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <!-- First -->
                                    <li class="page-item <?php if ($page_number <= 1) {
                                                                echo "disabled";
                                                            } ?>">
                                        <button type="button" class="page-link rounded-end-2 border-0 bg-transparent"
                                            style="font-family: 'IranYekanNum', sans-serif;"
                                            <?php if ($page_number > 1) { ?>onclick="goToTicketPage(1)"
                                            <?php } else { ?>disabled<?php } ?>>
                                            <i class="bi bi-chevron-double-right"></i>
                                        </button>
                                    </li>
                                    <!-- Prev -->
                                    <li class="page-item <?php if ($page_number <= 1) {
                                                                echo "disabled";
                                                            } ?>">
                                        <button type="button" class="page-link border-0 bg-transparent"
                                            style="font-family: 'IranYekanNum', sans-serif;"
                                            <?php if ($page_number > 1) { ?>onclick="goToTicketPage(<?php echo $page_number - 1; ?>)"
                                            <?php } else { ?>disabled<?php } ?>>
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </li>

                                    <!-- Pages -->
                                    <?php
                                    $max_buttons = 5;
                                    $start_page = max(1, $page_number - floor($max_buttons / 2));
                                    $end_page = min($total_pages, $start_page + $max_buttons - 1);
                                    $start_page = max(1, $end_page - $max_buttons + 1);

                                    if ($start_page > 1) {
                                        echo '<li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>';
                                    }

                                    for ($i = $start_page; $i <= $end_page; $i++) {
                                        $activeClass = ($i == $page_number) ? 'active bg-primary text-white shadow-sm' : 'text-muted';
                                    ?>
                                        <li class="page-item">
                                            <button type="button"
                                                class="page-link border-0 rounded-circle mx-1 d-flex align-items-center justify-content-center <?php echo $activeClass; ?>"
                                                style="width: 32px; height: 32px; font-family: 'IranYekanNum', sans-serif;"
                                                onclick="goToTicketPage(<?php echo $i; ?>)">
                                                <?php echo $i; ?>
                                            </button>
                                        </li>
                                    <?php }

                                    if ($end_page < $total_pages) {
                                        echo '<li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>';
                                    }
                                    ?>

                                    <!-- Next -->
                                    <li class="page-item <?php if ($page_number >= $total_pages) {
                                                                echo "disabled";
                                                            } ?>">
                                        <button type="button" class="page-link border-0 bg-transparent"
                                            style="font-family: 'IranYekanNum', sans-serif;"
                                            <?php if ($page_number < $total_pages) { ?>onclick="goToTicketPage(<?php echo $page_number + 1; ?>)"
                                            <?php } else { ?>disabled<?php } ?>>
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                    </li>
                                    <!-- Last -->
                                    <li class="page-item <?php if ($page_number >= $total_pages) {
                                                                echo "disabled";
                                                            } ?>">
                                        <button type="button" class="page-link rounded-start-2 border-0 bg-transparent"
                                            style="font-family: 'IranYekanNum', sans-serif;"
                                            <?php if ($page_number < $total_pages) { ?>onclick="goToTicketPage(<?php echo $total_pages; ?>)"
                                            <?php } else { ?>disabled<?php } ?>>
                                            <i class="bi bi-chevron-double-left"></i>
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>