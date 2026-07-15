<?php
// Active users indexed by personnel code
$usersQuery = "SELECT name, code_p, semat, avatar FROM karbar WHERE vaziat='y' ORDER BY name ASC LIMIT 500";
$usersByCode = [];
if ($result = mysqli_query($Link, $usersQuery)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row['ticket_code'] = '';
        $row['ticket_titr'] = '';
        $usersByCode[$row['code_p']] = $row;
    }
    mysqli_free_result($result);
}

// Attach latest "working" ticket (status = 'w') to each active user
foreach ($usersByCode as $code => &$user) {
    $userCode = mysqli_real_escape_string($Link, (string) $code);
    $ticketQuery = "SELECT code, titr FROM ticket WHERE code_p_karbar_anjam = '{$userCode}' AND vaziat = 'w' ORDER BY i_ticket DESC LIMIT 1";
    if ($ticketResult = mysqli_query($Link, $ticketQuery)) {
        if ($ticketRow = mysqli_fetch_assoc($ticketResult)) {
            $user['ticket_code'] = $ticketRow['code'];
            $user['ticket_titr'] = $ticketRow['titr'];
        }
        mysqli_free_result($ticketResult);
    }
}
unset($user);

// Ensure the connection table exists
mysqli_query($Link, "CREATE TABLE IF NOT EXISTS department_user (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    department_id VARCHAR(255) NOT NULL,
    user_code VARCHAR(64) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_department_user (department_id, user_code),
    KEY idx_department_id (department_id),
    KEY idx_user_code (user_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

// Group users by department (company -> division -> department) via connections
$deptGroups = [];
$companyOptions = [];
$divisionOptions = [];
$departmentOptions = [];
$assignedCodes = [];

$connSql = "SELECT du.user_code, d.id AS dept_id, d.name AS dept_name,
                   d.default_company_code, d.default_company_name,
                   d.division_code, d.division_name
            FROM department_user du
            INNER JOIN departman d ON d.id = du.department_id
            WHERE d.vaziat = 'y'
            ORDER BY d.default_company_name ASC, d.division_name ASC, d.name ASC";
if ($connRes = mysqli_query($Link, $connSql)) {
    while ($connRow = mysqli_fetch_assoc($connRes)) {
        $deptId = $connRow['dept_id'];
        if (!isset($deptGroups[$deptId])) {
            $deptGroups[$deptId] = [
                'dept_id'       => $deptId,
                'dept_name'     => $connRow['dept_name'],
                'company_code'  => $connRow['default_company_code'] ?? '',
                'company_name'  => $connRow['default_company_name'] ?? '',
                'division_code' => $connRow['division_code'] ?? '',
                'division_name' => $connRow['division_name'] ?? '',
                'users'         => [],
            ];
            if (!empty($connRow['default_company_code'])) {
                $companyOptions[$connRow['default_company_code']] = $connRow['default_company_name'] ?: $connRow['default_company_code'];
            }
            if (!empty($connRow['division_code'])) {
                $divisionOptions[$connRow['division_code']] = $connRow['division_name'] ?: $connRow['division_code'];
            }
            $departmentOptions[$deptId] = $connRow['dept_name'];
        }
        if (isset($usersByCode[$connRow['user_code']])) {
            $deptGroups[$deptId]['users'][] = $usersByCode[$connRow['user_code']];
            $assignedCodes[$connRow['user_code']] = true;
        }
    }
    mysqli_free_result($connRes);
}

asort($companyOptions, SORT_FLAG_CASE | SORT_STRING);
asort($divisionOptions, SORT_FLAG_CASE | SORT_STRING);
asort($departmentOptions, SORT_FLAG_CASE | SORT_STRING);

// Users not connected to any department
$unassignedUsers = [];
foreach ($usersByCode as $code => $u) {
    if (empty($assignedCodes[$code])) {
        $unassignedUsers[] = $u;
    }
}

$hasAnyGroup = !empty($deptGroups) || !empty($unassignedUsers);
?>

<style>
.ticket-card {
    border: 1px solid rgba(148, 163, 184, 0.18);
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.ticket-card:hover {
    border-color: rgba(59, 130, 246, 0.45);
    box-shadow: 0 18px 38px -20px rgba(37, 99, 235, 0.55);
    transform: translateY(-2px);
}

.ticket-card[data-has-ticket="0"] {
    cursor: not-allowed;
    opacity: 0.65;
    border-style: dashed;
}

.ticket-card[data-has-ticket="1"] {
    cursor: pointer;
}

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
    width: min(1080px, 100%);
    max-height: min(88vh, 840px);
    background: linear-gradient(160deg, #111827, #0f172a);
    border: 1px solid rgba(148, 163, 184, 0.35);
    border-radius: 18px;
    box-shadow: 0 38px 68px -40px rgba(15, 23, 42, 0.9);
    color: #f3f4f6;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.ticket-modal__header,
.ticket-modal__footer {
    padding: 18px 24px;
    background: rgba(15, 23, 42, 0.85);
    border-bottom: 1px solid rgba(148, 163, 184, 0.22);
}

.ticket-modal__footer {
    border-bottom: none;
    border-top: 1px solid rgba(148, 163, 184, 0.22);
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
    background: linear-gradient(160deg, rgba(17, 24, 39, 0.85), rgba(15, 23, 42, 0.78));
}

.ticket-modal__close {
    border: none;
    background: rgba(31, 41, 55, 0.65);
    color: #e5e7eb;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
}

.ticket-modal__close:hover {
    background: rgba(59, 130, 246, 0.35);
    transform: translateY(-1px);
}

.ticket-modal__button {
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, rgba(55, 65, 81, 0.95), rgba(30, 41, 59, 0.95));
    color: #e2e8f0;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
}

.ticket-modal__button:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.85), rgba(37, 99, 235, 0.85));
    box-shadow: 0 16px 32px -26px rgba(37, 99, 235, 0.75);
    transform: translateY(-1px);
}

.ticket-modal__loading {
    text-align: center;
    padding: 48px 16px;
    color: rgba(203, 213, 225, 0.85);
}

.ticket-modal__spinner {
    width: 46px;
    height: 46px;
    border: 4px solid rgba(148, 163, 184, 0.25);
    border-top-color: rgba(59, 130, 246, 0.85);
    border-radius: 50%;
    margin: 0 auto 16px;
    animation: ticket-modal-spin 0.8s linear infinite;
}

@keyframes ticket-modal-spin {
    to {
        transform: rotate(360deg);
    }
}
</style>


<div class="row gx-3">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-2">لیست کارکنان - تیکت‌های در حال انجام</h5>
                <p class="text-muted small mb-2">
                    روی کاربرانی که تیکت «در حال انجام» دارند کلیک کنید تا جزئیات همان تیکت در یک پنجره بازشو نمایش داده
                    شود.
                </p>
                <?php if ($hasAnyGroup): ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1"><i class="bi bi-building me-1"></i>شرکت</label>
                        <select class="form-select form-select-sm" id="workingCompanyFilter">
                            <option value="">همه شرکت‌ها</option>
                            <?php foreach ($companyOptions as $ccode => $cname): ?>
                                <option value="<?php echo htmlspecialchars($ccode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($cname, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1"><i class="bi bi-diagram-3 me-1"></i>معاونت</label>
                        <select class="form-select form-select-sm" id="workingDivisionFilter">
                            <option value="">همه معاونت‌ها</option>
                            <?php foreach ($divisionOptions as $dcode => $dname): ?>
                                <option value="<?php echo htmlspecialchars($dcode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($dname, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-1"><i class="bi bi-folder me-1"></i>دپارتمان</label>
                        <select class="form-select form-select-sm" id="workingDepartmentFilter">
                            <option value="">همه دپارتمان‌ها</option>
                            <?php foreach ($departmentOptions as $depCode => $depName): ?>
                                <option value="<?php echo htmlspecialchars($depCode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($depName, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12 col-lg-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="workingResetFilters">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>حذف فیلترها
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Renders a single user card
$renderUserCard = function ($user) {
    $displayName = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
    $role = htmlspecialchars($user['semat'] ?? '', ENT_QUOTES, 'UTF-8');
    $avatar = htmlspecialchars($user['avatar'] ?? 'karbar', ENT_QUOTES, 'UTF-8');
    $ticketCode = $user['ticket_code'] ? htmlspecialchars($user['ticket_code'], ENT_QUOTES, 'UTF-8') : '';
    $ticketTitr = $user['ticket_titr'] ? htmlspecialchars($user['ticket_titr'], ENT_QUOTES, 'UTF-8') : 'فاقد تیکت در حال انجام';
    $hasTicket = $ticketCode !== '' ? '1' : '0';
    ?>
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card h-100 ticket-card ticket-card-clickable" data-ticket-code="<?php echo $ticketCode; ?>"
            data-has-ticket="<?php echo $hasTicket; ?>">
            <div class="card-body d-flex align-items-start gap-3">
                <img src="assets/images/<?php echo $avatar; ?>.png" class="img-3x rounded-3" alt="<?php echo $displayName; ?>">
                <div class="flex-fill">
                    <h6 class="mb-1"><?php echo $displayName; ?></h6>
                    <div class="small text-muted mb-2"><?php echo $role; ?></div>
                    <div class="badge w-100 text-truncate<?php echo $hasTicket === '1' ? ' badge-has-ticket' : ' bg-light text-dark border'; ?>"
                        title="<?php echo $ticketTitr; ?>"
                        <?php if ($hasTicket === '1') { echo ' style="background:rgb(35,212,90);color:#fff;border:none;"'; } ?>>
                        <?php
                        $maxLen = 60;
                        echo (mb_strlen($ticketTitr) > $maxLen)
                            ? htmlspecialchars(mb_substr($ticketTitr, 0, $maxLen), ENT_QUOTES, 'UTF-8') . '...'
                            : $ticketTitr;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
};
?>

<div class="row gx-3" id="userCardsContainer">
    <?php if (!$hasAnyGroup): ?>
    <div class="col-12">
        <div class="alert alert-info mb-0">هیچ دپارتمان یا کاربری برای نمایش وجود ندارد.</div>
    </div>
    <?php else: ?>
        <?php foreach ($deptGroups as $group): ?>
        <div class="col-12 dept-group"
            data-company="<?php echo htmlspecialchars($group['company_code'], ENT_QUOTES, 'UTF-8'); ?>"
            data-division="<?php echo htmlspecialchars($group['division_code'], ENT_QUOTES, 'UTF-8'); ?>"
            data-department="<?php echo htmlspecialchars($group['dept_id'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2 mt-3">
                <span class="fw-bold"><i class="bi bi-folder me-1"></i><?php echo htmlspecialchars($group['dept_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if (!empty($group['company_name'])): ?>
                    <span class="badge bg-primary-subtle text-primary-emphasis"><i class="bi bi-building me-1"></i><?php echo htmlspecialchars($group['company_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
                <?php if (!empty($group['division_name'])): ?>
                    <span class="badge bg-info-subtle text-info-emphasis"><i class="bi bi-diagram-3 me-1"></i><?php echo htmlspecialchars($group['division_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
                <span class="badge bg-secondary-subtle text-secondary-emphasis"><?php echo count($group['users']); ?> کاربر</span>
            </div>
            <div class="row gx-3">
                <?php if (empty($group['users'])): ?>
                    <div class="col-12"><div class="text-muted small mb-2">کاربری به این دپارتمان متصل نشده است.</div></div>
                <?php else: ?>
                    <?php foreach ($group['users'] as $user) { $renderUserCard($user); } ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (!empty($unassignedUsers)): ?>
        <div class="col-12 dept-group" data-company="" data-division="" data-department="">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2 mt-3">
                <span class="fw-bold text-muted"><i class="bi bi-folder-x me-1"></i>بدون دپارتمان</span>
                <span class="badge bg-secondary-subtle text-secondary-emphasis"><?php echo count($unassignedUsers); ?> کاربر</span>
            </div>
            <div class="row gx-3">
                <?php foreach ($unassignedUsers as $user) { $renderUserCard($user); } ?>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Ticket Info Modal -->
<div class="ticket-modal" id="ticketInfoModal" aria-hidden="true" role="dialog">
    <div class="ticket-modal__overlay" data-modal-close></div>
    <div class="ticket-modal__dialog" role="document">
        <div class="ticket-modal__header">
            <h5 class="ticket-modal__title" id="ticketInfoModalLabel">جزئیات تیکت</h5>
            <button type="button" class="ticket-modal__close" data-modal-close aria-label="بستن پنجره">×</button>
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
    // Modal logic (unchanged)
    const modal = document.getElementById('ticketInfoModal');
    const modalContent = document.getElementById('ticketInfoContent');
    if (!modal || !modalContent) return;
    const closeElements = modal.querySelectorAll('[data-modal-close]');
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
        let ticketMarkup = '';
        const firstRow = doc.querySelector('.row.gx-3');
        if (firstRow) {
            let parent = firstRow.parentElement;
            while (parent && parent !== doc.body && !ticketMarkup) {
                const cards = parent.querySelectorAll('.card.mb-3');
                if (cards.length) {
                    cards.forEach(card => ticketMarkup += card.outerHTML);
                }
                parent = parent.parentElement;
            }
            if (!ticketMarkup) {
                firstRow.querySelectorAll('.card').forEach(card => {
                    ticketMarkup += card.outerHTML;
                });
            }
        } else {
            const cards = doc.querySelectorAll('.card.mb-3');
            cards.forEach(card => {
                ticketMarkup += card.outerHTML;
            });
        }
        return ticketMarkup || (html.includes('<!DOCTYPE') ? '' : html);
    };
    const showLoading = () => {
        modalContent.innerHTML =
            `<div class="ticket-modal__loading" role="status"><div class="ticket-modal__spinner" aria-hidden="true"></div><p class="mb-0">در حال بارگذاری اطلاعات تیکت...</p></div>`;
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
        }).then(response => response.text()).then(html => {
            if (!isOpen()) return;
            if (!html) {
                showError('خطا در بارگذاری اطلاعات تیکت.');
                return;
            }
            const rendered = renderTicketContent(html);
            modalContent.innerHTML = rendered ? rendered :
                '<div class="alert alert-danger mb-0">محتوای تیکت یافت نشد.</div>';
        }).catch(err => {
            if (err && err.name === 'AbortError') {
                return;
            }
            console.error('Error loading ticket info:', err);
            showError('خطا در بارگذاری اطلاعات تیکت.');
        }).finally(() => {
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

    // Filtering logic: company / division / department on department groups
    const groups = document.querySelectorAll('.dept-group');
    const companyFilter = document.getElementById('workingCompanyFilter');
    const divisionFilter = document.getElementById('workingDivisionFilter');
    const departmentFilter = document.getElementById('workingDepartmentFilter');
    const resetFiltersBtn = document.getElementById('workingResetFilters');

    function matchSelect(filterValue, groupValue) {
        if (filterValue === '') return true;
        return groupValue === filterValue;
    }

    function applyWorkingFilters() {
        const companyVal = companyFilter ? companyFilter.value : '';
        const divisionVal = divisionFilter ? divisionFilter.value : '';
        const departmentVal = departmentFilter ? departmentFilter.value : '';
        groups.forEach(group => {
            const show = matchSelect(companyVal, group.getAttribute('data-company') || '') &&
                matchSelect(divisionVal, group.getAttribute('data-division') || '') &&
                matchSelect(departmentVal, group.getAttribute('data-department') || '');
            group.style.display = show ? '' : 'none';
        });
    }

    if (companyFilter) companyFilter.addEventListener('change', applyWorkingFilters);
    if (divisionFilter) divisionFilter.addEventListener('change', applyWorkingFilters);
    if (departmentFilter) departmentFilter.addEventListener('change', applyWorkingFilters);
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            if (companyFilter) companyFilter.value = '';
            if (divisionFilter) divisionFilter.value = '';
            if (departmentFilter) departmentFilter.value = '';
            applyWorkingFilters();
        });
    }

    // Modal card click logic
    document.querySelectorAll('.ticket-card-clickable').forEach(card => {
        const hasTicket = card.dataset.hasTicket === '1';
        if (!hasTicket) return;
        card.addEventListener('click', () => {
            loadTicketInfo(card.dataset.ticketCode);
        });
    });
})();
</script>