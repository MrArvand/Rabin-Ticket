<?php
/**
 * Department <-> user connections manager (reusable section).
 *
 * Expected variables (set by the including page):
 *   $connection_departments : array of department rows to manage. Each row needs
 *                             id, name, default_company_name, division_name.
 *   $dept_users             : array of active users (code_p, name) for the add modal.
 *   $dept_connections       : map [department_id => [ ['user_code'=>..,'user_name'=>..], ... ]].
 *   $conn_message           : optional feedback message string.
 *   $conn_message_type      : optional bootstrap alert type (success/danger/warning).
 */
$connection_departments = $connection_departments ?? [];
$dept_users = $dept_users ?? [];
$dept_connections = $dept_connections ?? [];
$conn_message = $conn_message ?? '';
$conn_message_type = $conn_message_type ?? 'success';
?>
<div class="card dept-connect-card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="bi bi-people me-2"></i>اتصال کاربران به دپارتمان‌ها</h5>
        <p class="text-muted mb-0 mt-2" style="font-size: 0.85rem;">
            <i class="bi bi-info-circle me-1"></i>
            کاربرانی که به یک دپارتمان متصل می‌شوند، شرکت و معاونت آن دپارتمان را دریافت می‌کنند.
            هر کاربر می‌تواند به بیش از یک دپارتمان متصل شود.
        </p>
    </div>
    <div class="card-body">
        <?php if ($conn_message !== ''): ?>
            <div class="alert alert-<?php echo htmlspecialchars($conn_message_type); ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-<?php echo $conn_message_type === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                <?php echo htmlspecialchars($conn_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-2 align-items-center mb-3">
            <div class="col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" id="deptConnectSearch" placeholder="جستجوی دپارتمان بر اساس نام یا کد..." autocomplete="off">
                </div>
            </div>
        </div>

        <?php if (empty($connection_departments)): ?>
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                دپارتمانی برای مدیریت اتصال کاربران وجود ندارد.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle dept-connect-table">
                    <thead>
                        <tr>
                            <th style="width: 4%;">#</th>
                            <th style="width: 22%;">دپارتمان</th>
                            <th style="width: 16%;"><i class="bi bi-building me-1"></i>شرکت</th>
                            <th style="width: 16%;"><i class="bi bi-diagram-3 me-1"></i>معاونت</th>
                            <th style="width: 30%;">کاربران متصل</th>
                            <th style="width: 12%;">افزودن</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($connection_departments as $i => $cdep): ?>
                            <?php
                            $cid = $cdep['id'];
                            $conns = $dept_connections[$cid] ?? [];
                            ?>
                            <tr class="dept-connect-row" data-search="<?php echo htmlspecialchars(strtolower($cdep['name'] . ' ' . $cid)); ?>">
                                <td class="text-muted"><?php echo $i + 1; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($cdep['name']); ?></strong><br>
                                    <small class="text-muted">کد: <?php echo htmlspecialchars($cid); ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($cdep['default_company_name'])): ?>
                                        <span class="badge bg-primary-subtle text-primary-emphasis"><?php echo htmlspecialchars($cdep['default_company_name']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($cdep['division_name'])): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis"><?php echo htmlspecialchars($cdep['division_name']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (empty($conns)): ?>
                                        <span class="text-muted">کاربری متصل نشده است</span>
                                    <?php else: ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($conns as $cn): ?>
                                                <span class="badge bg-success-subtle text-success-emphasis d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-person-fill"></i>
                                                    <?php echo htmlspecialchars($cn['user_name'] ?: $cn['user_code']); ?>
                                                    <form method="POST" action="" class="d-inline" onsubmit="return confirm('حذف اتصال این کاربر از دپارتمان؟');">
                                                        <input type="hidden" name="action" value="remove_department_user">
                                                        <input type="hidden" name="dept_id" value="<?php echo htmlspecialchars($cid); ?>">
                                                        <input type="hidden" name="user_code" value="<?php echo htmlspecialchars($cn['user_code']); ?>">
                                                        <button type="submit" class="btn btn-link text-danger p-0 lh-1 border-0" title="حذف" style="text-decoration:none;">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-success open-connect-user-modal"
                                        data-bs-toggle="modal" data-bs-target="#connectUserModal"
                                        data-dept-id="<?php echo htmlspecialchars($cid); ?>"
                                        data-dept-name="<?php echo htmlspecialchars($cdep['name']); ?>">
                                        <i class="bi bi-person-plus me-1"></i>افزودن کاربر
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr id="deptConnectNoResults" style="display: none;">
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-search fs-3 d-block mb-2 opacity-50"></i>
                                دپارتمانی با این جستجو یافت نشد
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Connect user modal -->
<div class="modal fade" id="connectUserModal" tabindex="-1" aria-labelledby="connectUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="connectUserModalLabel">
                    <i class="bi bi-person-plus me-2"></i>اتصال کاربر
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" id="connectUserForm">
                <input type="hidden" name="action" value="add_department_user">
                <input type="hidden" name="dept_id" id="connectSelectedDeptId" value="">
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <strong>انتخاب کاربران</strong>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="connectSelectAll">انتخاب همه</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="connectClearAll">لغو همه</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-start-0" id="connectUserFilter" placeholder="جستجو در لیست کاربران..." autocomplete="off">
                        </div>
                    </div>
                    <div id="connectUserTableContainer" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover m-0">
                            <thead class="sticky-top" style="background: var(--bs-tertiary-bg);">
                                <tr>
                                    <th style="width: 48px;"></th>
                                    <th>نام کاربر</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dept_users as $user): ?>
                                    <tr class="connect-user-row" data-name="<?php echo strtolower($user['name'] . ' ' . $user['code_p']); ?>">
                                        <td>
                                            <input class="form-check-input connect-user-checkbox" type="checkbox"
                                                name="user_codes[]" value="<?php echo htmlspecialchars($user['code_p']); ?>"
                                                id="connect-user-<?php echo htmlspecialchars($user['code_p']); ?>">
                                        </td>
                                        <td>
                                            <label class="d-block mb-0" style="cursor: pointer;" for="connect-user-<?php echo htmlspecialchars($user['code_p']); ?>">
                                                <div><?php echo htmlspecialchars($user['name']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($user['code_p']); ?></small>
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center text-muted py-4" id="connectUserNoResults" style="display: none;">
                        <i class="bi bi-search fs-1 d-block mb-2" style="opacity: 0.5;"></i>
                        <p class="mb-0">هیچ کاربری یافت نشد</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="connectSelectedCount">۰ کاربر انتخاب شده</span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success" id="connectSubmitBtn">
                            <i class="bi bi-check2-all me-1"></i>اتصال کاربران انتخاب‌شده
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Department table search
        var connectSearch = document.getElementById('deptConnectSearch');
        var connectRows = Array.from(document.querySelectorAll('.dept-connect-row'));
        var connectNoResults = document.getElementById('deptConnectNoResults');
        if (connectSearch) {
            connectSearch.addEventListener('input', function() {
                var term = connectSearch.value.toLowerCase().trim();
                var visible = 0;
                connectRows.forEach(function(row) {
                    var hay = row.getAttribute('data-search') || '';
                    var show = hay.indexOf(term) !== -1;
                    row.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                if (connectNoResults) connectNoResults.style.display = (visible === 0) ? '' : 'none';
            });
        }

        // Multi-select connect modal
        var connectModalTitle = document.getElementById('connectUserModalLabel');
        var connectSelectedDeptId = document.getElementById('connectSelectedDeptId');
        var connectCheckboxes = Array.from(document.querySelectorAll('.connect-user-checkbox'));
        var connectSelectedCount = document.getElementById('connectSelectedCount');
        var connectUserFilter = document.getElementById('connectUserFilter');
        var connectUserRows = Array.from(document.querySelectorAll('.connect-user-row'));
        var connectUserNoResults = document.getElementById('connectUserNoResults');
        var connectUserTableContainer = document.getElementById('connectUserTableContainer');

        function toFa(n) {
            return String(n).replace(/[0-9]/g, function (d) { return '۰۱۲۳۴۵۶۷۸۹'[d]; });
        }

        function updateConnectCount() {
            var n = document.querySelectorAll('.connect-user-checkbox:checked').length;
            if (connectSelectedCount) connectSelectedCount.textContent = toFa(n) + ' کاربر انتخاب شده';
        }

        connectCheckboxes.forEach(function(cb) {
            cb.addEventListener('change', updateConnectCount);
        });

        document.querySelectorAll('.open-connect-user-modal').forEach(function(button) {
            button.addEventListener('click', function() {
                var deptId = button.getAttribute('data-dept-id') || '';
                var deptName = button.getAttribute('data-dept-name') || '';
                if (connectSelectedDeptId) connectSelectedDeptId.value = deptId;
                if (connectModalTitle) {
                    connectModalTitle.innerHTML = '<i class="bi bi-person-plus me-2"></i>اتصال کاربر به: ' + deptName;
                }
                // reset selections + search each time the modal opens
                connectCheckboxes.forEach(function(cb) { cb.checked = false; });
                if (connectUserFilter) connectUserFilter.value = '';
                connectUserRows.forEach(function(row) { row.style.display = ''; });
                if (connectUserNoResults) connectUserNoResults.style.display = 'none';
                if (connectUserTableContainer) connectUserTableContainer.style.display = 'block';
                updateConnectCount();
            });
        });

        var connectSelectAll = document.getElementById('connectSelectAll');
        if (connectSelectAll) {
            connectSelectAll.addEventListener('click', function() {
                connectUserRows.forEach(function(row) {
                    if (row.style.display !== 'none') {
                        var cb = row.querySelector('.connect-user-checkbox');
                        if (cb) cb.checked = true;
                    }
                });
                updateConnectCount();
            });
        }

        var connectClearAll = document.getElementById('connectClearAll');
        if (connectClearAll) {
            connectClearAll.addEventListener('click', function() {
                connectCheckboxes.forEach(function(cb) { cb.checked = false; });
                updateConnectCount();
            });
        }

        if (connectUserFilter) {
            connectUserFilter.addEventListener('input', function() {
                var term = connectUserFilter.value.toLowerCase().trim();
                var visible = 0;
                connectUserRows.forEach(function(row) {
                    var name = (row.getAttribute('data-name') || '').toLowerCase();
                    var show = name.indexOf(term) !== -1;
                    row.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                if (connectUserNoResults) connectUserNoResults.style.display = (visible === 0 && term !== '') ? 'block' : 'none';
                if (connectUserTableContainer) connectUserTableContainer.style.display = (visible === 0 && term !== '') ? 'none' : 'block';
            });
        }

        var connectUserForm = document.getElementById('connectUserForm');
        if (connectUserForm) {
            connectUserForm.addEventListener('submit', function(e) {
                if (document.querySelectorAll('.connect-user-checkbox:checked').length === 0) {
                    e.preventDefault();
                    alert('لطفا حداقل یک کاربر را انتخاب کنید.');
                }
            });
        }
    });
</script>
