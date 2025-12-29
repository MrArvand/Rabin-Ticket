<?php
$all_zaman = 0;
$jam_emroz = 0;
?>
<style>
    .work-form-card {
        background: var(--color-bg-card, var(--bs-card-bg));
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
        border-radius: 10px;
        box-shadow: 0 5px 15px var(--color-shadow-lg, rgba(0,0,0,0.3));
        overflow: hidden;
    }
    .work-form-card .card-header {
        background: var(--color-bg-tertiary, var(--bs-card-cap-bg));
        border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color));
        padding: 1rem;
    }
    .work-form-card .card-title {
        color: var(--color-text-primary, var(--bs-body-color));
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
    }
    .work-form-card .card-body {
        background: var(--color-bg-card, var(--bs-card-bg));
        padding: 1.25rem;
        color: var(--color-text-primary, var(--bs-body-color));
    }
    .time-input-group {
        position: relative;
    }
    .time-input-group .input-group-text {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
        color: var(--color-text-primary, var(--bs-body-color));
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
        font-weight: 600;
    }
    .duration-display {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
        color: var(--color-text-primary, var(--bs-body-color));
        padding: 0.75rem;
        border-radius: 8px;
        text-align: center;
        font-size: 1rem;
        font-weight: 600;
        margin-top: 0.5rem;
        transition: all 0.3s ease;
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
    }
    .duration-display.has-value {
        background: var(--color-primary, var(--bs-primary));
        color: var(--color-white, #ffffff);
        box-shadow: 0 5px 20px var(--color-shadow-md, rgba(60, 146, 177, 0.3));
    }
    .activity-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        display: inline-block;
    }
    .stats-card {
        background: var(--color-bg-card, var(--bs-card-bg));
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        box-shadow: 0 3px 10px var(--color-shadow-md, rgba(0,0,0,0.2));
    }
    .stats-card .stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--color-primary, var(--bs-primary));
    }
    .stats-card .stat-label {
        color: var(--color-text-secondary, var(--bs-secondary-color));
        font-size: 0.75rem;
        margin-top: 0.15rem;
    }
    .work-table {
        border-radius: 15px;
        overflow: hidden;
    }
    .work-table thead {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
        color: var(--color-text-primary, var(--bs-body-color));
    }
    .work-table thead th {
        border: none;
        padding: 0.75rem;
        font-weight: 600;
        color: var(--color-text-primary, var(--bs-body-color));
        font-size: 0.85rem;
    }
    .work-table tbody tr {
        transition: all 0.3s ease;
        background: var(--color-bg-card, var(--bs-card-bg));
        font-size: 0.85rem;
    }
    .work-table tbody tr:hover {
        background-color: var(--color-bg-hover, var(--bs-tertiary-bg));
    }
    .work-table tbody tr:hover td {
        background-color: var(--color-bg-hover, var(--bs-tertiary-bg)) !important;
        color: var(--color-text-primary, var(--bs-body-color)) !important;
    }
    .work-table tbody td {
        padding: 0.6rem 0.75rem;
        vertical-align: middle;
    }
    .work-table tfoot {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
    }
    .badge-modern {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .btn-gradient {
        background: var(--color-primary, var(--bs-primary));
        border: none;
        color: var(--color-white, #ffffff);
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px var(--color-shadow-lg, rgba(60, 146, 177, 0.4));
        color: var(--color-white, #ffffff);
        background: var(--color-primary-hover, var(--bs-primary));
        opacity: 0.9;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--color-primary, var(--bs-primary));
        box-shadow: 0 0 0 0.2rem var(--color-badge-primary-bg, rgba(60, 146, 177, 0.25));
        background: var(--color-bg-card, var(--bs-card-bg));
        color: var(--color-text-primary, var(--bs-body-color));
    }
    .form-control, .form-select {
        background: var(--color-bg-card, var(--bs-card-bg));
        color: var(--color-text-primary, var(--bs-body-color));
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
    }
    .form-control:read-only {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
        color: var(--color-text-primary, var(--bs-body-color));
    }
    .time-section {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
    }
    .icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--color-primary, var(--bs-primary));
        color: var(--color-white, #ffffff);
        font-size: 1.2rem;
        margin-bottom: 0.75rem;
    }
    .card-footer {
        background: var(--color-bg-tertiary, var(--bs-card-cap-bg));
        border-top: 1px solid var(--color-border-primary, var(--bs-border-color));
    }
    .text-muted {
        color: var(--color-text-secondary, var(--bs-secondary-color)) !important;
    }
    .badge.bg-light {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg)) !important;
        color: var(--color-text-primary, var(--bs-body-color)) !important;
    }
</style>

<!-- Work Entry Form -->
            <div class="row gx-3">
              <div class="col-sm-12">
        <div class="card work-form-card mb-4">
                  <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-plus-circle me-2"></i>ثبت کارکرد روزانه
                </h5>
                <p class="text-secondary mb-0 mt-2">
                    <i class="bi bi-info-circle me-1"></i>
                    زمان شروع و پایان را وارد کنید تا مدت زمان به صورت خودکار محاسبه شود
                </p>
                  </div>
                  <div class="card-body">
                <form method="post" action="?page=s_new_karkerd" enctype="multipart/form-data" id="workForm">
                    <div class="row gx-3">
                        <!-- User Name -->
                        <div class="col-lg-3 col-md-6 mb-2">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                <i class="bi bi-person me-1"></i>ثبت کننده
                            </label>
                            <input type="text" value="<?php echo htmlspecialchars($name_karbar_run); ?>" 
                                   class="form-control" name="karbar_darkhast" readonly>
                        </div>

                        <!-- Activity Type -->
                        <div class="col-lg-3 col-md-6 mb-2">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                <i class="bi bi-tag me-1"></i>نوع فعالیت
                            </label>
                          <select class="form-select" name="faal" id="faal">
                                <option value="y" selected>کاری</option>
							<option value="a">آموزش</option>
							<option value="j">جلسات</option>
							<option value="z">تایم آزاد</option>
							<option value="m">مرخصی</option>
                          </select>
                      </div>

                        <!-- Department -->
                        <div class="col-lg-3 col-md-6 mb-2">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                <i class="bi bi-building me-1"></i>دپارتمان
                            </label>
                          <select class="form-select" name="daste" id="daste">
                          <?php
                                $Query_dep = "SELECT*from departman where (vaziat = 'y') ORDER BY name ASC LIMIT 200";
                                if ($Result_dep = mysqli_query($Link, $Query_dep)) {
                                    while ($q_dep = mysqli_fetch_array($Result_dep)) {
                                        ?>
                                        <option value="<?php echo $q_dep['id']; ?>">
                                            <?php echo htmlspecialchars($q_dep['name']); ?> - [<?php echo $q_dep['id']; ?>]
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                                <option value="n" selected>بدون دسته بندی</option>
                          </select>
                      </div>

                        <!-- Company -->
                        <div class="col-lg-3 col-md-6 mb-2">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                <i class="bi bi-briefcase me-1"></i>شرکت
                            </label>
                          <select class="form-select" name="sherkat" id="sherkat">
                          <?php
                                $Query_sherkat = "SELECT*from sherkatha where (1)ORDER BY name DESC LIMIT 200";
                                if ($Result_sherkat = mysqli_query($Link, $Query_sherkat)) {
                                    while ($q_sherkat = mysqli_fetch_array($Result_sherkat)) {
                                        ?>
                                        <option value="<?php echo $q_sherkat['code']; ?>">
                                            <?php echo htmlspecialchars($q_sherkat['name']); ?>
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                                <option value="n" selected>غیر مرتبط</option>
                          </select>
                        </div>
                      </div>

                    <!-- Time Section -->
                    <div class="time-section">
                        <h6 class="mb-2 fw-bold" style="font-size: 0.95rem;">
                            <i class="bi bi-clock me-2"></i>زمان‌بندی
                        </h6>
                        <div class="row gx-3">
                            <!-- Start Date -->
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label class="form-label fw-bold" style="font-size: 0.9rem;">تاریخ شروع</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-calendar"></i>
                                    </span>
                                    <input value="<?php echo $tarikh; ?>" 
                                           data-bs-toggle="modal" data-bs-target="#selector_mod" 
                                           type="text" class="form-control" id="tarikh_e" name="tarikh_e" 
                                           placeholder="تاریخ" readonly>
                        </div>
                      </div>

                            <!-- Start Time -->
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label class="form-label fw-bold" style="font-size: 0.9rem;">ساعت شروع</label>
                                <div class="input-group clockpicker" data-placement="top" data-align="left" data-autoclose="true">
                                    <span class="input-group-text">
                                        <i class="bi bi-clock"></i>
                                    </span>
                                    <input value="<?php echo $saat; ?>" 
                                           type="text" class="form-control" id="saat_e" name="saat_e" 
                                           placeholder="ساعت">
                                </div>
                            </div>

                            <!-- End Time -->
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label class="form-label fw-bold" style="font-size: 0.9rem;">ساعت پایان</label>
                                <div class="input-group clockpicker" data-placement="top" data-align="left" data-autoclose="true">
                                    <span class="input-group-text">
                                        <i class="bi bi-clock-fill"></i>
                                    </span>
                                    <input type="text" class="form-control" id="saat_end" name="saat_e_end" 
                                           placeholder="ساعت">
                                </div>
                            </div>
					  
                            <!-- Duration (Auto-calculated) -->
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label class="form-label fw-bold" style="font-size: 0.9rem;">مدت زمان (دقیقه)</label>
                                <div class="duration-display" id="durationDisplay">
                                    <i class="bi bi-calculator me-2"></i>
                                    <span id="durationValue">0</span> دقیقه
                                </div>
                                <input type="hidden" id="modat" name="modat" value="0">
                            </div>
                        </div>
                      </div>
                      
                    <!-- Activity Title -->
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                <i class="bi bi-file-text me-1"></i>موضوع فعالیت
                            </label>
                            <input type="text" class="form-control" id="titr" name="titr" 
                                   placeholder="توضیحات فعالیت را وارد کنید..." required>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>لغو
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-check-circle me-1"></i>ثبت کارکرد
                        </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            </div>

<!-- Statistics Cards (moved below table, smaller) -->
<div class="row gx-2 mb-3">
    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2">
        <div class="stats-card d-flex align-items-center">
            <div class="icon-wrapper me-2">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="stat-value" id="todayTotal">0</div>
                <div class="stat-label">دقیقه کارکرد امروز</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2">
        <div class="stats-card d-flex align-items-center">
            <div class="icon-wrapper me-2" style="background: var(--color-danger, var(--bs-danger));">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <div class="stat-value" id="totalRecords">0</div>
                <div class="stat-label">تعداد رکوردها</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2">
        <div class="stats-card d-flex align-items-center">
            <div class="icon-wrapper me-2" style="background: var(--color-info, var(--bs-info));">
                <i class="bi bi-graph-up"></i>
            </div>
            <div>
                <div class="stat-value" id="totalTime">0</div>
                <div class="stat-label">مجموع کل زمان (دقیقه)</div>
            </div>
        </div>
    </div>
</div>
<!-- Work History Table -->
<div class="row gx-3">
           <div class="col-xxl-12">
                <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>تاریخچه کارکرد
                </h5>
            </div>
            <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-hover work-table m-0">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>دسته فعالیت</th>
                             <th>شرکت</th>
                            <th>تاریخ شروع</th>
                                <th>ساعت شروع</th>
                                <th>ساعت پایان</th>
                            <th>مدت زمان</th>
                                <th>نوع فعالیت</th>
                                <th>موضوع</th>
                                <th>عملیات</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                            $shomare = 0;
                            $Query_list = "SELECT*from karkerd where (code_p = '$code_p_run' )ORDER BY i_karkerd DESC LIMIT 1000";
        
                            if ($Result_list = mysqli_query($Link, $Query_list)) {
                                while ($q_kkk = mysqli_fetch_array($Result_list)) {
	 $shomare++;
                                    if ($tarikh == $q_kkk['tarikh_s']) {
                                        $jam_emroz = $jam_emroz + (int)$q_kkk['zaman'];
                                    }
                                    $all_zaman = $all_zaman + (int)$q_kkk['zaman'];
                                    
                                    // Determine badge class based on activity type
                                    $badge_class = "bg-secondary";
                                    $activity_text = "نامشخص";
                                    if ($q_kkk['faal'] == "y") {
                                        $badge_class = "bg-danger";
                                        $activity_text = "کاری";
                                    } elseif ($q_kkk['faal'] == "a") {
                                        $badge_class = "bg-success";
                                        $activity_text = "آموزش";
                                    } elseif ($q_kkk['faal'] == "j") {
                                        $badge_class = "bg-warning";
                                        $activity_text = "جلسات";
                                    } elseif ($q_kkk['faal'] == "z") {
                                        $badge_class = "bg-info";
                                        $activity_text = "تایم آزاد";
                                    } elseif ($q_kkk['faal'] == "m") {
                                        $badge_class = "bg-primary";
                                        $activity_text = "مرخصی";
                                    }
	 ?>
                          <tr>
                                        <td><strong><?php echo $shomare; ?></strong></td>
                                        <td><?php echo htmlspecialchars($q_kkk['daste']); ?></td>
                                        <td><?php echo htmlspecialchars($q_kkk['mortabet']); ?></td>
                                        <td><?php echo htmlspecialchars($q_kkk['tarikh_s']); ?></td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($q_kkk['saat_s']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($q_kkk['saat_e'])) { ?>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-clock-fill me-1"></i><?php echo htmlspecialchars($q_kkk['saat_e']); ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="text-muted">-</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <strong class="text-primary"><?php echo $q_kkk['zaman']; ?></strong> دقیقه
                                        </td>
                                        <td>
                                            <span class="badge badge-modern <?php echo $badge_class; ?>">
                                                <?php echo $activity_text; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-truncate d-inline-block flex-grow-1" style="max-width: 200px;" 
                                                      title="<?php echo htmlspecialchars($q_kkk['matn']); ?>">
                                                    <?php echo htmlspecialchars($q_kkk['matn']); ?>
                                                </span>
                                                <?php if (strlen($q_kkk['matn']) > 30) { ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-link text-primary p-0" 
                                                            style="min-width: 24px; font-size: 0.9rem;"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewTextModal<?php echo htmlspecialchars($q_kkk['code']); ?>"
                                                            title="مشاهده متن کامل">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($q_kkk['tarikh_s'] == $tarikh) { ?>
                                                <a class="btn btn-sm btn-outline-danger" 
                                                   href="?page=hazf_karkerd&code=<?php echo $q_kkk['code']; ?>"
                                                   onclick="return confirm('آیا از حذف این رکورد اطمینان دارید؟')">
                                                    <i class="bi bi-trash"></i> حذف
                                                </a>
                                            <?php } else { ?>
                                                <span class="text-muted">-</span>
    <?php } ?>
    </td>
                          </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">مجموع کل:</td>
                                <td class="fw-bold text-primary">
                                    <strong><?php echo $all_zaman; ?></strong> دقیقه
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                      </table>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="text-success fw-bold">
                            <i class="bi bi-check-circle me-1"></i>
                            کارکرد ثبت شده امروز: <strong><?php echo $jam_emroz; ?></strong> دقیقه
                        </span>
                        <span class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            فقط رکوردهای امروز قابل حذف هستند
                        </span>
                    </div>
                </div>
                    </div>
                  </div>
                </div>
              </div>



<!-- View Full Text Modals -->
<?php
// Reset query to create modals for rows with long text
$Query_list_modal = "SELECT*from karkerd where (code_p = '$code_p_run' )ORDER BY i_karkerd DESC LIMIT 1000";
if ($Result_list_modal = mysqli_query($Link, $Query_list_modal)) {
    while ($q_modal = mysqli_fetch_array($Result_list_modal)) {
        if (strlen($q_modal['matn']) > 30) {
            // Determine activity type for display
            $activity_text_modal = "نامشخص";
            if ($q_modal['faal'] == "y") {
                $activity_text_modal = "کاری";
            } elseif ($q_modal['faal'] == "a") {
                $activity_text_modal = "آموزش";
            } elseif ($q_modal['faal'] == "j") {
                $activity_text_modal = "جلسات";
            } elseif ($q_modal['faal'] == "z") {
                $activity_text_modal = "تایم آزاد";
            } elseif ($q_modal['faal'] == "m") {
                $activity_text_modal = "مرخصی";
            }
            $modal_id = htmlspecialchars($q_modal['code']);
            ?>
            <div class="modal fade" id="viewTextModal<?php echo $modal_id; ?>" tabindex="-1" aria-labelledby="viewTextModalLabel<?php echo $modal_id; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewTextModalLabel<?php echo $modal_id; ?>">
                                <i class="bi bi-file-text me-2"></i>مشاهده متن کامل فعالیت
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1" style="font-size: 0.85rem;">تاریخ:</strong>
                                    <span><?php echo htmlspecialchars($q_modal['tarikh_s']); ?></span>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1" style="font-size: 0.85rem;">ساعت:</strong>
                                    <span><?php echo htmlspecialchars($q_modal['saat_s']); ?></span>
                                    <?php if (!empty($q_modal['saat_e'])) { ?>
                                        <span> - <?php echo htmlspecialchars($q_modal['saat_e']); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1" style="font-size: 0.85rem;">مدت زمان:</strong>
                                    <span class="text-primary fw-bold"><?php echo $q_modal['zaman']; ?> دقیقه</span>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1" style="font-size: 0.85rem;">نوع فعالیت:</strong>
                                    <span><?php echo $activity_text_modal; ?></span>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1" style="font-size: 0.85rem;">دپارتمان:</strong>
                                    <span><?php echo htmlspecialchars($q_modal['daste']); ?></span>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1" style="font-size: 0.85rem;">شرکت:</strong>
                                    <span><?php echo htmlspecialchars($q_modal['mortabet']); ?></span>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div>
                                <strong class="text-muted d-block mb-2">موضوع فعالیت:</strong>
                                <div class="p-3 rounded" style="background: var(--color-bg-tertiary, var(--bs-tertiary-bg)); border: 1px solid var(--color-border-primary, var(--bs-border-color)); min-height: 100px;  word-wrap: break-word; line-height: 1.8;">
                                    <?php echo nl2br(htmlspecialchars($q_modal['matn'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
          
<!-- Date Selection Modal -->
<div class="modal fade" id="selector_mod" tabindex="-1" aria-labelledby="selector_mod" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                          <div class="modal-header">
                <h5 class="modal-title">انتخاب تاریخ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
            <div class="modal-body">
<?php include('page/ui/modal/re_tarikh_fa.php'); ?>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                          </div>
                        </div>
                      </div>
                    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('saat_e');
    const endTimeInput = document.getElementById('saat_end');
    const durationDisplay = document.getElementById('durationDisplay');
    const durationValue = document.getElementById('durationValue');
    const durationInput = document.getElementById('modat');
    const dateInput = document.getElementById('tarikh_e');
    
    // Update statistics
    document.getElementById('todayTotal').textContent = <?php echo $jam_emroz; ?>;
    document.getElementById('totalRecords').textContent = <?php echo $shomare; ?>;
    document.getElementById('totalTime').textContent = <?php echo $all_zaman; ?>;
    
    function calculateDuration() {
        const startTime = startTimeInput.value.trim();
        const endTime = endTimeInput.value.trim();
        const date = dateInput.value.trim();
        
        if (!startTime || !endTime || !date) {
            durationValue.textContent = '0';
            durationInput.value = '0';
            durationDisplay.classList.remove('has-value');
            return;
        }
        
        try {
            // Parse times (format: HH:MM)
            const [startHour, startMin] = startTime.split(':').map(Number);
            const [endHour, endMin] = endTime.split(':').map(Number);
            
            // Parse date (format: YYYYMMDD)
            const year = parseInt(date.substring(0, 4));
            const month = parseInt(date.substring(4, 6)) - 1; // JS months are 0-indexed
            const day = parseInt(date.substring(6, 8));
            
            // Create date objects
            const startDate = new Date(year, month, day, startHour, startMin);
            const endDate = new Date(year, month, day, endHour, endMin);
            
            // Handle case where end time is next day
            if (endDate < startDate) {
                endDate.setDate(endDate.getDate() + 1);
            }
            
            // Calculate difference in minutes
            const diffMs = endDate - startDate;
            const diffMinutes = Math.round(diffMs / (1000 * 60));
            
            if (diffMinutes < 0) {
                durationValue.textContent = '0';
                durationInput.value = '0';
                durationDisplay.classList.remove('has-value');
                return;
            }
            
            // Update hidden input
            durationInput.value = diffMinutes;
            durationDisplay.classList.add('has-value');
            
            // Format display with hours and minutes (text only, unit added in HTML)
            const hours = Math.floor(diffMinutes / 60);
            const minutes = diffMinutes % 60;
            if (hours > 0) {
                durationValue.textContent = `${hours} ساعت و ${minutes}`;
            } else {
                durationValue.textContent = `${minutes}`;
            }
            
        } catch (error) {
            console.error('Error calculating duration:', error);
            durationValue.textContent = '0';
            durationInput.value = '0';
            durationDisplay.classList.remove('has-value');
        }
    }
    
    // Listen for changes
    startTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);
    dateInput.addEventListener('change', calculateDuration);
    
    // Also listen for input events for real-time updates
    startTimeInput.addEventListener('input', calculateDuration);
    endTimeInput.addEventListener('input', calculateDuration);
    
    // Initialize ClockPicker with proper positioning
    if (window.jQuery && typeof $.fn.clockpicker === 'function') {
        $('.clockpicker').clockpicker({
            placement: 'top',
            align: 'left',
            autoclose: true,
            donetext: 'تایید',
            afterDone: function() {
                calculateDuration();
            },
            afterShow: function() {
                // Ensure popover is visible and properly positioned
                var popover = $('.clockpicker-popover');
                if (popover.length) {
                    popover.css({
                        'z-index': '9999',
                        'position': 'fixed'
                    });
                }
            }
        });
        
        // Also trigger calculation on input change
        $('.clockpicker input').on('change input', function() {
            calculateDuration();
        });
    } else {
        console.warn('ClockPicker plugin is not available.');
    }
    
    // Initial calculation
    calculateDuration();
    
    // Form validation
    const workForm = document.getElementById('workForm');
    workForm.addEventListener('submit', function(e) {
        const startTime = startTimeInput.value.trim();
        const endTime = endTimeInput.value.trim();
        const duration = parseInt(durationInput.value) || 0;
        
        if (!startTime || !endTime) {
            e.preventDefault();
            alert('لطفاً ساعت شروع و پایان را وارد کنید');
            return false;
        }
        
        if (duration <= 0) {
            e.preventDefault();
            alert('مدت زمان باید بیشتر از صفر باشد');
            return false;
        }
    });
});
</script>
          