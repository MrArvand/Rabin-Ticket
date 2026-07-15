<?php
$categoryFilter = str_g('category');

$categoryQuery = "SELECT id, name, default_company_code, default_company_name, division_code, division_name FROM departman WHERE vaziat = 'y' ORDER BY default_company_name ASC, division_name ASC, name ASC LIMIT 500";
$categories = [];
$categoryCounts = [];

if ($resultCategories = mysqli_query($Link, $categoryQuery)) {
    while ($row = mysqli_fetch_assoc($resultCategories)) {
        $categories[] = $row;
        $categoryId = $row['id'];
        $categoryCounts[$categoryId] = 0;
    }
    mysqli_free_result($resultCategories);
}

if ($categoryFilter === '0' || $categoryFilter === '' || !in_array($categoryFilter, array_column($categories, 'id'))) {
    $categoryFilter = !empty($categories) ? $categories[0]['id'] : '';
}

// Count tickets with status 'a' or 'm' for each category
$countQuery = "SELECT daste, COUNT(*) as count FROM ticket WHERE (vaziat IN ('a', 'm')) GROUP BY daste";
if ($resultCounts = mysqli_query($Link, $countQuery)) {
    while ($row = mysqli_fetch_assoc($resultCounts)) {
        $catId = $row['daste'];
        if (isset($categoryCounts[$catId])) {
            $categoryCounts[$catId] = (int)$row['count'];
        }
    }
    mysqli_free_result($resultCounts);
}

$selectedCategory = $categoryFilter;

$companyOptions = [];
$divisionOptions = [];
$selectedCompany = '';
$selectedDivision = '';

foreach ($categories as $category) {
    $companyCode = $category['default_company_code'] ?? '';
    $divisionCode = $category['division_code'] ?? '';

    if ($companyCode !== '') {
        $companyOptions[$companyCode] = $category['default_company_name'] ?: $companyCode;
    }
    if ($divisionCode !== '') {
        $divisionOptions[$divisionCode] = $category['division_name'] ?: $divisionCode;
    }
    if ($category['id'] === $selectedCategory) {
        $selectedCompany = $companyCode;
        $selectedDivision = $divisionCode;
    }
}

asort($companyOptions, SORT_FLAG_CASE | SORT_STRING);
asort($divisionOptions, SORT_FLAG_CASE | SORT_STRING);

// Show tickets with status 'a' or 'm', limit to last 20 per category
$ticketsQuery = "SELECT code, titr, name_karbar, name_sherkat, name_daste, daste, tarikh_sabt, saat_sabt, olaviat, vaziat, IFNULL(priority_status, 'n') AS priority_status, priority_order, (SELECT default_company_name FROM departman WHERE id = ticket.daste LIMIT 1) AS dept_company_name, (SELECT division_name FROM departman WHERE id = ticket.daste LIMIT 1) AS dept_division_name FROM ticket WHERE (vaziat IN ('a', 'm')) " . (!empty($categoryFilter) ? "AND daste = '" . mysqli_real_escape_string($Link, $categoryFilter) . "'" : "") . " ORDER BY priority_status DESC, priority_order ASC, i_ticket DESC LIMIT 20";

$prioritizedTickets = [];
$unprioritizedTickets = [];

if ($resultTickets = mysqli_query($Link, $ticketsQuery)) {
    while ($row = mysqli_fetch_assoc($resultTickets)) {
        $status = $row['priority_status'] === 'y' ? 'y' : 'n';
        if ($status === 'y') {
            $prioritizedTickets[] = $row;
        } else {
            $unprioritizedTickets[] = $row;
        }
    }
    mysqli_free_result($resultTickets);
}

usort($prioritizedTickets, function ($a, $b) {
    $orderA = isset($a['priority_order']) ? (int)$a['priority_order'] : PHP_INT_MAX;
    $orderB = isset($b['priority_order']) ? (int)$b['priority_order'] : PHP_INT_MAX;

    if ($orderA === $orderB) {
        $timeA = ($a['tarikh_sabt'] ?? '') . ($a['saat_sabt'] ?? '');
        $timeB = ($b['tarikh_sabt'] ?? '') . ($b['saat_sabt'] ?? '');
        return strcmp($timeA, $timeB);
    }

    return $orderA <=> $orderB;
});

usort($unprioritizedTickets, function ($a, $b) {
    $timeA = ($a['tarikh_sabt'] ?? '') . ($a['saat_sabt'] ?? '');
    $timeB = ($b['tarikh_sabt'] ?? '') . ($b['saat_sabt'] ?? '');
    return strcmp($timeB, $timeA);
});

$priorityLabels = [
    '1' => ['label' => 'ضروری', 'class' => 'danger'],
    '2' => ['label' => 'متوسط', 'class' => 'warning'],
    '3' => ['label' => 'معمولی', 'class' => 'info'],
    '4' => ['label' => 'پایین', 'class' => 'secondary']
];
?>

<link rel="stylesheet" href="https://unpkg.com/dragula@3.7.3/dist/dragula.min.css">

<style>
:root {
    --kanban-surface: #1c1f21;
    --kanban-border: rgba(148, 163, 184, 0.22);
    --kanban-shadow: 0 26px 48px -20px rgba(8, 15, 30, 0.8);
    --kanban-accent: #2563eb;
    --kanban-accent-soft: rgba(37, 99, 235, 0.25);
    --kanban-text: #f9fafb;
    --kanban-muted: rgba(209, 213, 219, 0.68);
}

.kanban-wrapper {
    min-height: 420px;
}

.kanban-dropzone {
    min-height: 240px;
    border: 1px dashed rgba(148, 163, 184, 0.35);
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(17, 24, 39, 0.85), rgba(28, 31, 33, 0.78));
    padding: 16px;
    transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
    box-shadow: inset 0 0 0 rgba(0, 0, 0, 0);
}

.kanban-dropzone.drag-over {
    border-color: var(--kanban-accent);
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.25), rgba(17, 24, 39, 0.85));
    box-shadow: inset 0 0 25px -18px rgba(37, 99, 235, 0.9);
}

.kanban-card {
    border-radius: 12px;
    border: 1px solid var(--kanban-border);
    background: linear-gradient(160deg, rgba(32, 39, 50, 0.95), rgba(17, 23, 34, 0.95));
    box-shadow: var(--kanban-shadow);
    margin-bottom: 12px;
    cursor: grab;
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    color: var(--kanban-text);
}

.kanban-card:last-child {
    margin-bottom: 0;
}

.kanban-card:active {
    cursor: grabbing;
}

.kanban-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 26px 38px -18px rgba(37, 99, 235, 0.55);
    border-color: rgba(59, 130, 246, 0.45);
}

.kanban-card.dragging {
    opacity: 0.85;
    box-shadow: 0 32px 48px -20px rgba(37, 99, 235, 0.75) !important;
    border-color: rgba(37, 99, 235, 0.55);
}

.kanban-meta {
    font-size: 0.78rem;
    color: var(--kanban-muted);
}

.kanban-meta span+span::before {
    content: "•";
    margin: 0 4px;
    color: rgba(129, 140, 248, 0.55);
}

.kanban-card .badge.border {
    background: rgba(17, 24, 39, 0.7);
    border-width: 1px;
}

.kanban-card .badge.border.text-secondary {
    color: var(--kanban-muted) !important;
    border-color: rgba(148, 163, 184, 0.45) !important;
}

.kanban-card .badge.border.text-primary {
    color: #93c5fd !important;
    border-color: rgba(59, 130, 246, 0.5) !important;
}

.kanban-card .badge.border.text-success {
    color: #6ee7b7 !important;
    border-color: rgba(16, 185, 129, 0.45) !important;
}

.kanban-card .badge.border.text-info {
    color: #67e8f9 !important;
    border-color: rgba(8, 145, 178, 0.45) !important;
}

.kanban-card .badge.bg-primary {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    box-shadow: 0 8px 18px -10px rgba(37, 99, 235, 0.66);
}

.kanban-card .badge.bg-danger {
    box-shadow: 0 8px 18px -10px rgba(239, 68, 68, 0.6);
}

.kanban-card .badge.bg-warning {
    box-shadow: 0 8px 18px -10px rgba(251, 191, 36, 0.5);
    color: #1c1917;
}

.kanban-badge {
    font-size: 0.75rem;
    font-weight: 600;
}

.kanban-card .text-muted {
    color: rgba(229, 231, 235, 0.6) !important;
}

.kanban-empty {
    text-align: center;
    padding: 32px 16px;
    color: rgba(148, 163, 184, 0.8);
    font-size: 0.9rem;
}

.kanban-feedback {
    position: fixed;
    right: 24px;
    bottom: 24px;
    z-index: 1050;
    min-width: 240px;
    display: none;
    background: rgba(13, 20, 32, 0.95);
    color: var(--kanban-text);
    border: 1px solid rgba(59, 130, 246, 0.4);
    box-shadow: 0 18px 32px -20px rgba(15, 23, 42, 0.75);
}

@media (max-width: 767.98px) {
    .kanban-dropzone {
        min-height: 120px;
    }
}
</style>

<div class="row gx-3">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="mb-2">مدیریت اولویت تیکت ها</h5>
                    <p class="text-muted mb-3 small">تیکت های در وضعیت ثبت اولیه و در حال بررسی نمایش داده می‌شوند
                        (حداکثر 20 تیکت از هر دسته). کارت‌ها را برای تعیین اولویت جابه‌جا کنید.</p>
                </div>
                <?php if (!empty($categories)): ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1"><i class="bi bi-building me-1"></i>شرکت</label>
                        <select class="form-select form-select-sm" id="priorityCompanyFilter">
                            <option value=""<?php echo $selectedCompany === '' ? ' selected' : ''; ?>>همه شرکت‌ها</option>
                            <?php foreach ($companyOptions as $ccode => $cname): ?>
                                <option value="<?php echo htmlspecialchars($ccode, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $selectedCompany === $ccode ? ' selected' : ''; ?>><?php echo htmlspecialchars($cname, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1"><i class="bi bi-diagram-3 me-1"></i>معاونت</label>
                        <select class="form-select form-select-sm" id="priorityDivisionFilter">
                            <option value=""<?php echo $selectedDivision === '' ? ' selected' : ''; ?>>همه معاونت‌ها</option>
                            <?php
                            $seenDivisions = [];
                            foreach ($categories as $category):
                                $dcode = $category['division_code'] ?? '';
                                $ccode = $category['default_company_code'] ?? '';
                                if ($dcode === '') continue;
                                $divKey = $ccode . '::' . $dcode;
                                if (isset($seenDivisions[$divKey])) continue;
                                $seenDivisions[$divKey] = true;
                                $dname = $category['division_name'] ?: $dcode;
                                $isSelected = ($selectedDivision === $dcode && $selectedCompany === $ccode);
                            ?>
                                <option value="<?php echo htmlspecialchars($dcode, ENT_QUOTES, 'UTF-8'); ?>" data-company="<?php echo htmlspecialchars($ccode, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $isSelected ? ' selected' : ''; ?>><?php echo htmlspecialchars($dname, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1"><i class="bi bi-folder me-1"></i>دپارتمان</label>
                        <select class="form-select form-select-sm" id="priorityDepartmentFilter">
                            <?php foreach ($categories as $category):
                                $catId = $category['id'];
                                $count = isset($categoryCounts[$catId]) ? (int) $categoryCounts[$catId] : 0;
                                $label = $category['name'] . ' (' . $count . ')';
                            ?>
                                <option value="<?php echo htmlspecialchars($catId, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-company="<?php echo htmlspecialchars($category['default_company_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-division="<?php echo htmlspecialchars($category['division_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo $selectedCategory === $catId ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12 col-lg-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="priorityResetFilters">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>حذف فیلترها
                        </button>
                    </div>
                </div>
                <script>
                (function() {
                    var companyFilter = document.getElementById('priorityCompanyFilter');
                    var divisionFilter = document.getElementById('priorityDivisionFilter');
                    var departmentFilter = document.getElementById('priorityDepartmentFilter');
                    var resetBtn = document.getElementById('priorityResetFilters');
                    var selectedCategory = <?php echo json_encode($selectedCategory, JSON_UNESCAPED_UNICODE); ?>;
                    var navigating = false;

                    function filterOptions(selectEl, matchFn) {
                        if (!selectEl) return;
                        selectEl.querySelectorAll('option').forEach(function(opt) {
                            if (opt.value === '') return;
                            var show = matchFn(opt);
                            opt.hidden = !show;
                            opt.disabled = !show;
                        });
                    }

                    function syncDivisionFilter() {
                        var company = companyFilter ? companyFilter.value : '';
                        filterOptions(divisionFilter, function(opt) {
                            if (company === '') return true;
                            return (opt.getAttribute('data-company') || '') === company;
                        });
                        if (divisionFilter && divisionFilter.value) {
                            var curDiv = divisionFilter.options[divisionFilter.selectedIndex];
                            if (curDiv && curDiv.disabled) divisionFilter.value = '';
                        }
                    }

                    function syncDepartmentFilter() {
                        var company = companyFilter ? companyFilter.value : '';
                        var division = divisionFilter ? divisionFilter.value : '';
                        filterOptions(departmentFilter, function(opt) {
                            var optCompany = opt.getAttribute('data-company') || '';
                            var optDivision = opt.getAttribute('data-division') || '';
                            if (company !== '' && optCompany !== company) return false;
                            if (division !== '' && optDivision !== division) return false;
                            return true;
                        });
                    }

                    function getFirstVisibleDepartment() {
                        if (!departmentFilter) return '';
                        for (var i = 0; i < departmentFilter.options.length; i++) {
                            var opt = departmentFilter.options[i];
                            if (!opt.disabled && opt.value !== '') return opt.value;
                        }
                        return '';
                    }

                    function navigateToCategory(deptId) {
                        if (!deptId || navigating || deptId === selectedCategory) return;
                        navigating = true;
                        window.location.href = '?page=priority&category=' + encodeURIComponent(deptId);
                    }

                    function applyFilters(autoNavigateIfNeeded) {
                        syncDivisionFilter();
                        syncDepartmentFilter();
                        if (!departmentFilter) return;
                        var currentOpt = departmentFilter.options[departmentFilter.selectedIndex];
                        if (currentOpt && !currentOpt.disabled && currentOpt.value !== '') {
                            return;
                        }
                        var first = getFirstVisibleDepartment();
                        if (first) {
                            departmentFilter.value = first;
                            if (autoNavigateIfNeeded) navigateToCategory(first);
                        }
                    }

                    if (companyFilter) companyFilter.addEventListener('change', function() { applyFilters(true); });
                    if (divisionFilter) divisionFilter.addEventListener('change', function() { applyFilters(true); });
                    if (departmentFilter) {
                        departmentFilter.addEventListener('change', function() {
                            navigateToCategory(departmentFilter.value);
                        });
                    }
                    if (resetBtn) {
                        resetBtn.addEventListener('click', function() {
                            if (companyFilter) companyFilter.value = '';
                            if (divisionFilter) divisionFilter.value = '';
                            applyFilters(true);
                        });
                    }

                    applyFilters(false);
                })();
                </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row gx-3 kanban-wrapper">
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill mb-3">
            <div class="card-header border-0 bg-transparent d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">اولویت بندی نشده</h6>
                    <span class="badge rounded-pill border border-secondary text-secondary bg-transparent fw-semibold"
                        id="count-unset"><?php echo count($unprioritizedTickets); ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="kanban-dropzone" id="kanban-unset" data-column="unprioritized">
                    <?php if (empty($unprioritizedTickets)): ?>
                    <div class="kanban-empty" data-empty="unset">تیکتی برای اولویت‌بندی وجود ندارد.</div>
                    <?php else: ?>
                    <?php foreach ($unprioritizedTickets as $ticket): ?>
                    <?php
                            $categoryId = $ticket['daste'] ?? '';
                            $categoryName = $ticket['name_daste'] ?? '---';
                            $priority = $ticket['olaviat'] ?? '';
                            $vaziat = $ticket['vaziat'] ?? '';
                            $priorityMeta = $priorityLabels[$priority] ?? null;
                            $vaziatLabel = ($vaziat == 'a') ? 'ثبت اولیه' : (($vaziat == 'm') ? 'در حال بررسی' : (($vaziat == 'w') ? 'روی میز' : ''));
                            $vaziatClass = ($vaziat == 'a') ? 'danger' : (($vaziat == 'm') ? 'info' : (($vaziat == 'w') ? 'primary' : 'secondary'));
                            ?>
                    <div class="kanban-card ticket-card-clickable"
                        data-ticket-code="<?php echo htmlspecialchars($ticket['code'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-category-id="<?php echo htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8'); ?>"
                        style="cursor: pointer;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex flex-column">
                                    <span
                                        class="fw-semibold"><?php echo htmlspecialchars($ticket['titr'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="kanban-meta mt-1">
                                        <span>کد:
                                            <?php echo htmlspecialchars($ticket['code'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span><?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </span>
                                </div>
                                <div class="text-end">
                                    <?php if ($vaziatLabel): ?>
                                    <span
                                        class="badge bg-<?php echo $vaziatClass; ?> kanban-badge mb-1 d-block"><?php echo htmlspecialchars($vaziatLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php endif; ?>
                                    <?php if ($priorityMeta): ?>
                                    <span class="badge bg-<?php echo $priorityMeta['class']; ?> kanban-badge">اولویت:
                                        <?php echo htmlspecialchars($priorityMeta['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php else: ?>
                                    <span class="badge border border-secondary text-secondary kanban-badge">اولویت تعریف
                                        نشده</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge border border-primary text-primary kanban-badge">درخواست دهنده:
                                    <?php echo htmlspecialchars($ticket['name_karbar'] ?? '---', ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="badge border border-info text-info kanban-badge">شرکت:
                                    <?php echo htmlspecialchars($ticket['name_sherkat'] ?? '---', ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if (!empty($ticket['dept_company_name'])): ?>
                                <span class="badge bg-primary kanban-badge">شرکت گیرنده: <?php echo htmlspecialchars($ticket['dept_company_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($ticket['dept_division_name'])): ?>
                                <span class="badge bg-info kanban-badge">معاونت: <?php echo htmlspecialchars($ticket['dept_division_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endif; ?>
                                <span class="text-muted small">ثبت:
                                    <?php echo htmlspecialchars(($ticket['tarikh_sabt'] ?? '') . ' - ' . ($ticket['saat_sabt'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill mb-3">
            <div class="card-header border-0 bg-transparent d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">اولویت بندی شده</h6>
                    <span class="badge rounded-pill bg-primary text-white fw-semibold"
                        id="count-set"><?php echo count($prioritizedTickets); ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="kanban-dropzone" id="kanban-set" data-column="prioritized">
                    <?php if (empty($prioritizedTickets)): ?>
                    <div class="kanban-empty" data-empty="set">تیکت اولویت‌دار وجود ندارد. کارت‌ها را به این ستون منتقل
                        کنید.</div>
                    <?php else: ?>
                    <?php foreach ($prioritizedTickets as $ticket): ?>
                    <?php
                            $categoryId = $ticket['daste'] ?? '';
                            $categoryName = $ticket['name_daste'] ?? '---';
                            $priority = $ticket['olaviat'] ?? '';
                            $vaziat = $ticket['vaziat'] ?? '';
                            $priorityMeta = $priorityLabels[$priority] ?? null;
                            $orderBadge = isset($ticket['priority_order']) ? (int)$ticket['priority_order'] : null;
                            $vaziatLabel = ($vaziat == 'a') ? 'ثبت اولیه' : (($vaziat == 'm') ? 'در حال بررسی' : (($vaziat == 'w') ? 'روی میز' : ''));
                            $vaziatClass = ($vaziat == 'a') ? 'danger' : (($vaziat == 'm') ? 'info' : (($vaziat == 'w') ? 'primary' : 'secondary'));
                            ?>
                    <div class="kanban-card ticket-card-clickable"
                        data-ticket-code="<?php echo htmlspecialchars($ticket['code'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-category-id="<?php echo htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8'); ?>"
                        style="cursor: pointer;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex flex-column">
                                    <span
                                        class="fw-semibold"><?php echo htmlspecialchars($ticket['titr'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="kanban-meta mt-1">
                                        <span>کد:
                                            <?php echo htmlspecialchars($ticket['code'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span><?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </span>
                                </div>
                                <div class="text-end">
                                    <?php if ($orderBadge): ?>
                                    <span class="badge bg-primary kanban-badge mb-1 d-block"
                                        data-role="priority-position">#<?php echo $orderBadge; ?></span>
                                    <?php else: ?>
                                    <span class="badge border border-primary text-primary kanban-badge mb-1 d-block"
                                        data-role="priority-position">#--</span>
                                    <?php endif; ?>
                                    <?php if ($vaziatLabel): ?>
                                    <span
                                        class="badge bg-<?php echo $vaziatClass; ?> kanban-badge mb-1 d-block"><?php echo htmlspecialchars($vaziatLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php endif; ?>
                                    <div class="mt-1">
                                        <?php if ($priorityMeta): ?>
                                        <span
                                            class="badge bg-<?php echo $priorityMeta['class']; ?> kanban-badge">اولویت:
                                            <?php echo htmlspecialchars($priorityMeta['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <?php else: ?>
                                        <span class="badge border border-secondary text-secondary kanban-badge">اولویت
                                            تعریف نشده</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge border border-success text-success kanban-badge">درخواست دهنده:
                                    <?php echo htmlspecialchars($ticket['name_karbar'] ?? '---', ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="badge border border-info text-info kanban-badge">شرکت:
                                    <?php echo htmlspecialchars($ticket['name_sherkat'] ?? '---', ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if (!empty($ticket['dept_company_name'])): ?>
                                <span class="badge bg-primary kanban-badge">شرکت گیرنده: <?php echo htmlspecialchars($ticket['dept_company_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($ticket['dept_division_name'])): ?>
                                <span class="badge bg-info kanban-badge">معاونت: <?php echo htmlspecialchars($ticket['dept_division_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endif; ?>
                                <span class="text-muted small">ثبت:
                                    <?php echo htmlspecialchars(($ticket['tarikh_sabt'] ?? '') . ' - ' . ($ticket['saat_sabt'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="kanban-feedback alert alert-success shadow" id="kanbanFeedback" role="alert"></div>

<script src="https://unpkg.com/dragula@3.7.3/dist/dragula.min.js"></script>
<script>
(function() {
    const unsetColumn = document.getElementById('kanban-unset');
    const setColumn = document.getElementById('kanban-set');
    const feedback = document.getElementById('kanbanFeedback');
    const counts = {
        unset: document.getElementById('count-unset'),
        set: document.getElementById('count-set')
    };

    const showFeedback = (message, type = 'success') => {
        if (!feedback) return;
        feedback.classList.remove('alert-success', 'alert-danger', 'alert-info');
        feedback.classList.add('alert-' + type);
        feedback.textContent = message;
        feedback.style.display = 'block';
        setTimeout(() => {
            feedback.style.display = 'none';
        }, 3500);
    };

    const updateCounts = () => {
        if (counts.unset) {
            counts.unset.textContent = unsetColumn.querySelectorAll('.kanban-card').length;
        }
        if (counts.set) {
            counts.set.textContent = setColumn.querySelectorAll('.kanban-card').length;
        }
    };

    const updateEmptyStates = () => {
        const emptyUnset = unsetColumn.querySelector('[data-empty="unset"]');
        const emptySet = setColumn.querySelector('[data-empty="set"]');

        if (emptyUnset) {
            emptyUnset.style.display = unsetColumn.querySelectorAll('.kanban-card').length ? 'none' : 'block';
        }
        if (emptySet) {
            emptySet.style.display = setColumn.querySelectorAll('.kanban-card').length ? 'none' : 'block';
        }
    };

    updateCounts();
    updateEmptyStates();

    const syncPriorities = (ticketCode, status, order) => {
        return fetch('uxindex.php?page=update_priority', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ticket_code: ticketCode,
                    status: status,
                    order: order
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('پاسخ نامعتبر سرور');
                }
                return response.text();
            })
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (!data.success) {
                        throw new Error(data.message || 'خطا در ذخیره‌سازی اطلاعات');
                    }
                    return data;
                } catch (error) {
                    console.error('Invalid JSON response:', text);
                    throw new Error(
                    'پاسخ دریافتی معتبر نبود. دوباره تلاش کنید یا با پشتیبانی تماس بگیرید.');
                }
            });
    };

    const refreshPriorityBadges = () => {
        const prioritizedCards = Array.from(setColumn.querySelectorAll('.kanban-card'));
        prioritizedCards.forEach((card, index) => {
            const badge = card.querySelector('[data-role="priority-position"]');
            if (badge) {
                badge.textContent = '#' + (index + 1);
            }
        });
    };

    if (typeof dragula !== 'undefined') {
        const drake = dragula([unsetColumn, setColumn], {
            moves: function(el, container, handle) {
                return handle.classList.contains('kanban-card') || handle.closest('.kanban-card');
            }
        });

        drake.on('drag', function(el) {
            el.classList.add('dragging');
        });

        drake.on('dragend', function(el) {
            el.classList.remove('dragging');
        });

        drake.on('over', function(el, container) {
            container.classList.add('drag-over');
        });

        drake.on('out', function(el, container) {
            container.classList.remove('drag-over');
        });

        drake.on('drop', function(el, target, source, sibling) {
            const ticketCode = el.dataset.ticketCode;
            const targetColumn = target.dataset.column;
            const prioritizedOrder = Array.from(setColumn.querySelectorAll('.kanban-card')).map(card => card
                .dataset.ticketCode);

            if (!ticketCode || !targetColumn) {
                showFeedback('خطا در شناسایی تیکت.', 'danger');
                return;
            }

            const status = targetColumn === 'prioritized' ? 'y' : 'n';

            [unsetColumn, setColumn, target, source].forEach(container => {
                if (container && container.classList) {
                    container.classList.remove('drag-over');
                }
            });

            syncPriorities(ticketCode, status, prioritizedOrder)
                .then(() => {
                    refreshPriorityBadges();
                    updateCounts();
                    updateEmptyStates();
                    showFeedback('تغییرات با موفقیت ذخیره شد.');
                })
                .catch(error => {
                    drake.cancel(true);
                    updateCounts();
                    updateEmptyStates();
                    showFeedback(error.message || 'خطا در ذخیره‌سازی', 'danger');
                });
        });
    } else {
        console.error('dragula library not loaded.');
    }
})();
</script>

<style>
body.ticket-modal-open {
    overflow: hidden;
}

.ticket-modal {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.22s ease, visibility 0.22s ease;
}

.ticket-modal.is-open {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.ticket-modal__overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(3px);
}

.ticket-modal__dialog {
    position: relative;
    width: min(1100px, 100%);
    max-height: min(88vh, 860px);
    background: var(--kanban-surface);
    color: var(--kanban-text);
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 18px;
    box-shadow: 0 42px 68px -38px rgba(8, 15, 30, 0.9);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.ticket-modal__header,
.ticket-modal__footer {
    padding: 20px 24px;
    background: rgba(17, 24, 39, 0.88);
    border-bottom: 1px solid rgba(99, 102, 241, 0.22);
}

.ticket-modal__footer {
    border-bottom: none;
    border-top: 1px solid rgba(99, 102, 241, 0.22);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.ticket-modal__title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 600;
}

.ticket-modal__body {
    padding: 24px;
    overflow-y: auto;
    background: linear-gradient(160deg, rgba(17, 24, 39, 0.92), rgba(15, 23, 42, 0.85));
}

.ticket-modal__close {
    border: none;
    background: rgba(55, 65, 81, 0.65);
    color: #f9fafb;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
}

.ticket-modal__close:hover {
    background: rgba(59, 130, 246, 0.4);
    transform: translateY(-1px);
}

.ticket-modal__button {
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.85), rgba(37, 99, 235, 0.85));
    color: #e2e8f0;
    font-weight: 500;
    cursor: pointer;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.ticket-modal__button:hover {
    box-shadow: 0 18px 32px -26px rgba(37, 99, 235, 0.75);
    transform: translateY(-1px);
}

.ticket-modal__loading {
    text-align: center;
    padding: 48px 16px;
    color: rgba(203, 213, 225, 0.8);
}

.ticket-modal__spinner {
    width: 46px;
    height: 46px;
    border: 4px solid rgba(148, 163, 184, 0.25);
    border-top-color: rgba(59, 130, 246, 0.85);
    border-radius: 50%;
    margin: 0 auto 18px;
    animation: ticket-modal-spin 0.8s linear infinite;
}

@keyframes ticket-modal-spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

<!-- Ticket Info Modal -->
<div class="ticket-modal" id="ticketInfoModal" aria-hidden="true" role="dialog">
    <div class="ticket-modal__overlay" data-modal-close></div>
    <div class="ticket-modal__dialog" role="document">
        <div class="ticket-modal__header">
            <h5 class="ticket-modal__title" id="ticketInfoModalLabel">جزئیات تیکت</h5>
            <button type="button" class="ticket-modal__close" data-modal-close aria-label="بستن">×</button>
        </div>
        <div class="ticket-modal__body" id="ticketInfoContent">
            <div class="ticket-modal__loading" role="status">
                <div class="ticket-modal__spinner" aria-hidden="true"></div>
                <p class="mb-0">در حال بارگذاری اطلاعات تیکت...</p>
            </div>
        </div>
        <div class="ticket-modal__footer">
            <button type="button" class="ticket-modal__button" data-modal-close>بستن</button>
        </div>
    </div>
</div>

<script>
(function() {
    let isDragging = false;
    const modal = document.getElementById('ticketInfoModal');
    const modalContent = document.getElementById('ticketInfoContent');
    if (!modal || !modalContent) {
        return;
    }

    const closeElements = modal.querySelectorAll('[data-modal-close]');
    const ticketCards = document.querySelectorAll('.ticket-card-clickable');
    let activeFetchController = null;

    const isOpen = () => modal.classList.contains('is-open');

    const showModal = () => {
        if (isOpen()) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('ticket-modal-open');
    };

    const hideModal = () => {
        if (!isOpen()) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('ticket-modal-open');
        if (activeFetchController) {
            activeFetchController.abort();
            activeFetchController = null;
        }
    };

    const renderTicketContent = (html) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const appBody = doc.querySelector('.app-body');
        if (appBody) {
            return appBody.innerHTML;
        }

        let ticketHTML = '';
        const firstRow = doc.querySelector('.row.gx-3');
        if (firstRow) {
            let parent = firstRow.parentElement;
            while (parent && parent !== doc.body && !ticketHTML) {
                const cards = parent.querySelectorAll('.card.mb-3');
                if (cards.length) {
                    cards.forEach(card => {
                        ticketHTML += card.outerHTML;
                    });
                }
                parent = parent.parentElement;
            }

            if (!ticketHTML) {
                firstRow.querySelectorAll('.card').forEach(card => {
                    ticketHTML += card.outerHTML;
                });
            }
        } else {
            const cards = doc.querySelectorAll('.card.mb-3');
            cards.forEach(card => {
                ticketHTML += card.outerHTML;
            });
        }

        return ticketHTML || (html.includes('<!DOCTYPE') ? '' : html);
    };

    const showLoading = () => {
        modalContent.innerHTML = `
            <div class="ticket-modal__loading" role="status">
                <div class="ticket-modal__spinner" aria-hidden="true"></div>
                <p class="mb-0">در حال بارگذاری اطلاعات تیکت...</p>
            </div>
        `;
    };

    const showError = (message) => {
        modalContent.innerHTML = `<div class="alert alert-danger mb-0">${message}</div>`;
    };

    const loadTicketInfo = (ticketCode) => {
        if (!ticketCode) return;

        if (typeof AbortController !== 'undefined') {
            if (activeFetchController) {
                activeFetchController.abort();
            }
            activeFetchController = new AbortController();
        } else {
            activeFetchController = null;
        }

        showLoading();
        showModal();

        fetch(`?page=info_ticket&code=${encodeURIComponent(ticketCode)}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: activeFetchController ? activeFetchController.signal : undefined
        })
            .then(response => response.text())
            .then(html => {
                if (!isOpen()) return;

                if (!html) {
                    showError('خطا در بارگذاری اطلاعات تیکت');
                    return;
                }

                const rendered = renderTicketContent(html);
                modalContent.innerHTML = rendered
                    ? rendered
                    : '<div class="alert alert-danger mb-0">محتوای تیکت یافت نشد.</div>';
            })
            .catch(error => {
                if (error && error.name === 'AbortError') {
                    return;
                }
                console.error('Error loading ticket info:', error);
                showError('خطا در بارگذاری اطلاعات تیکت');
            })
            .finally(() => {
                activeFetchController = null;
            });
    };

    closeElements.forEach(el => {
        el.addEventListener('click', hideModal);
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && isOpen()) {
            hideModal();
        }
    });

    ticketCards.forEach(card => {
        let mouseDownTime = 0;
        let mouseDownX = 0;
        let mouseDownY = 0;

        card.addEventListener('mousedown', event => {
            mouseDownTime = Date.now();
            mouseDownX = event.clientX;
            mouseDownY = event.clientY;
            isDragging = false;
        });

        card.addEventListener('mousemove', event => {
            if (mouseDownTime > 0) {
                const deltaX = Math.abs(event.clientX - mouseDownX);
                const deltaY = Math.abs(event.clientY - mouseDownY);
                if (deltaX > 5 || deltaY > 5) {
                    isDragging = true;
                }
            }
        });

        card.addEventListener('mouseup', () => {
            const mouseUpTime = Date.now();
            const timeDiff = mouseUpTime - mouseDownTime;
            const ticketCode = card.dataset.ticketCode;

            if (!isDragging && timeDiff < 300 && mouseDownTime > 0 && ticketCode) {
                loadTicketInfo(ticketCode);
            }

            mouseDownTime = 0;
            isDragging = false;
        });

        card.addEventListener('click', event => {
            if (isDragging) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });

    const drake = window.dragula && window.dragula.getElements ? window.dragula.getElements() : null;
    if (drake && drake.length > 0) {
        drake.forEach(instance => {
            instance.on('drag', () => {
                isDragging = true;
            });
            instance.on('dragend', () => {
                setTimeout(() => {
                    isDragging = false;
                }, 150);
            });
        });
    }
})();
</script>
