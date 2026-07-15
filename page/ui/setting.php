<?php
$current_user_code = isset($_SESSION['code_p']) ? trim((string) $_SESSION['code_p']) : '';
$full_admin_codes = ["25662", "1100056", "1100085", "1100074", "1100097", "1064046037", "23056","22695","24277","20612"];
$is_full_admin = in_array($current_user_code, $full_admin_codes, true);

// Departments the current user manages (they are the default user of these)
$managed_departments = [];
if ($current_user_code !== '') {
    $cuc_esc = mysqli_real_escape_string($Link, $current_user_code);
    $managed_q = "SELECT id, name, default_company_code, default_company_name, division_code, division_name
                  FROM departman WHERE vaziat = 'y' AND default_user_code = '$cuc_esc' ORDER BY name ASC";
    if ($managed_res = mysqli_query($Link, $managed_q)) {
        while ($managed_row = mysqli_fetch_assoc($managed_res)) {
            $managed_departments[] = $managed_row;
        }
    }
}
$is_department_manager = !empty($managed_departments);
$can_access_settings = $is_full_admin || $is_department_manager;

// ---------------------------------------------------------------------
// Shared: department <-> user connections (table, POST handlers, data)
// Available to full admins (all departments) and to department managers
// (only the departments they are the default user of).
// ---------------------------------------------------------------------
$conn_message = '';
$conn_message_type = 'success';
$dept_users = [];
$dept_connections = [];

if ($can_access_settings) {
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

    $managed_dept_ids = array_map(function ($d) {
        return (string) $d['id'];
    }, $managed_departments);

    $can_manage_connection = function ($deptId) use ($is_full_admin, $managed_dept_ids, $Link) {
        $deptId = (string) $deptId;
        if ($deptId === '') {
            return false;
        }
        if ($is_full_admin) {
            $deptIdEsc = mysqli_real_escape_string($Link, $deptId);
            $chk = mysqli_query($Link, "SELECT 1 FROM departman WHERE id = '$deptIdEsc' AND vaziat = 'y' LIMIT 1");
            return $chk && mysqli_num_rows($chk) > 0;
        }
        return in_array($deptId, $managed_dept_ids, true);
    };

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn_action = $_POST['action'] ?? '';
        if ($conn_action === 'add_department_user') {
            $c_dept = trim((string) ($_POST['dept_id'] ?? ''));

            // Accept multiple selected users (user_codes[]), with single user_code fallback
            $c_users_raw = $_POST['user_codes'] ?? [];
            if (!is_array($c_users_raw)) {
                $c_users_raw = [];
            }
            if (empty($c_users_raw) && !empty($_POST['user_code'])) {
                $c_users_raw = [$_POST['user_code']];
            }
            $c_users = [];
            foreach ($c_users_raw as $raw_user) {
                $trimmed_user = trim((string) $raw_user);
                if ($trimmed_user !== '') {
                    $c_users[] = $trimmed_user;
                }
            }
            $c_users = array_values(array_unique($c_users));

            if ($c_dept === '' || empty($c_users)) {
                $conn_message = 'دپارتمان یا کاربر مشخص نشده است.';
                $conn_message_type = 'warning';
            } elseif (!$can_manage_connection($c_dept)) {
                $conn_message = 'شما اجازه مدیریت این دپارتمان را ندارید.';
                $conn_message_type = 'danger';
            } else {
                $c_dept_esc = mysqli_real_escape_string($Link, $c_dept);
                $added_count = 0;
                foreach ($c_users as $c_user) {
                    $c_user_esc = mysqli_real_escape_string($Link, $c_user);
                    if (mysqli_query($Link, "INSERT IGNORE INTO department_user (department_id, user_code) VALUES ('$c_dept_esc', '$c_user_esc')")) {
                        $added_count += mysqli_affected_rows($Link) > 0 ? 1 : 0;
                    }
                }
                $conn_message = $added_count . ' کاربر به دپارتمان متصل شد.';
                $conn_message_type = 'success';
            }
        } elseif ($conn_action === 'remove_department_user') {
            $c_dept = trim((string) ($_POST['dept_id'] ?? ''));
            $c_user = trim((string) ($_POST['user_code'] ?? ''));
            if ($c_dept !== '' && $c_user !== '' && $can_manage_connection($c_dept)) {
                $c_dept_esc = mysqli_real_escape_string($Link, $c_dept);
                $c_user_esc = mysqli_real_escape_string($Link, $c_user);
                mysqli_query($Link, "DELETE FROM department_user WHERE department_id = '$c_dept_esc' AND user_code = '$c_user_esc'");
                $conn_message = 'اتصال کاربر حذف شد.';
                $conn_message_type = 'success';
            } elseif (!$can_manage_connection($c_dept)) {
                $conn_message = 'شما اجازه مدیریت این دپارتمان را ندارید.';
                $conn_message_type = 'danger';
            }
        }
    }

    // Active users (for the connect modal)
    $conn_users_q = "SELECT code_p, name, semat FROM karbar WHERE vaziat = 'y' ORDER BY name ASC";
    if ($conn_users_res = mysqli_query($Link, $conn_users_q)) {
        while ($conn_users_row = mysqli_fetch_array($conn_users_res)) {
            $dept_users[] = $conn_users_row;
        }
    }

    // Existing connections grouped by department id
    $conn_q = "SELECT du.department_id, du.user_code, k.name AS user_name
               FROM department_user du
               LEFT JOIN karbar k ON k.code_p = du.user_code
               ORDER BY k.name ASC";
    if ($conn_res = mysqli_query($Link, $conn_q)) {
        while ($conn_row = mysqli_fetch_assoc($conn_res)) {
            $dept_connections[$conn_row['department_id']][] = [
                'user_code' => $conn_row['user_code'],
                'user_name' => $conn_row['user_name'],
            ];
        }
    }
}

if ($is_full_admin) {

    // =====================================================================
    // POST handlers + data loading (run before any output)
    // =====================================================================

    // ---- Department default / division assignment handlers ----
    $dept_message = '';
    $dept_message_type = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $action = $_POST['action'] ?? '';

        if ($action === 'update_dept_default') {

            $dept_id = mysqli_real_escape_string($Link, $_POST['dept_id'] ?? '');
            if (empty($dept_id)) {
                return;
            }

            // 👤 اگر کاربر ارسال شده
            if (isset($_POST['user_code'])) {

                $dept_user_code = mysqli_real_escape_string($Link, $_POST['user_code'] ?? '');
                $dept_user_name = mysqli_real_escape_string($Link, $_POST['user_name'] ?? '');

                $update_query = "UPDATE departman SET
                default_user_code = '$dept_user_code',
                default_user_name = '$dept_user_name'
                WHERE id = '$dept_id'";

                if (!mysqli_query($Link, $update_query)) {
                    $dept_message = 'خطا: ' . mysqli_error($Link);
                    $dept_message_type = 'danger';
                    return;
                }
            }

            // 🏢 اگر شرکت ارسال شده (تغییر شرکت، معاونت قبلی را پاک می‌کند)
            if (isset($_POST['company_code'])) {

                $dept_company_code = mysqli_real_escape_string($Link, $_POST['company_code'] ?? '');
                $dept_company_name = mysqli_real_escape_string($Link, $_POST['company_name'] ?? '');

                $update_query = "UPDATE departman SET
                default_company_code = '$dept_company_code',
                default_company_name = '$dept_company_name',
                division_code = NULL,
                division_name = NULL
                WHERE id = '$dept_id'";

                if (!mysqli_query($Link, $update_query)) {
                    $dept_message = 'خطا: ' . mysqli_error($Link);
                    $dept_message_type = 'danger';
                    return;
                }
            }

            // 🗂️ اگر معاونت ارسال شده
            if (isset($_POST['division_code'])) {

                $dept_division_code = mysqli_real_escape_string($Link, $_POST['division_code'] ?? '');
                $dept_division_name = mysqli_real_escape_string($Link, $_POST['division_name'] ?? '');

                if ($dept_division_code === '') {
                    $update_query = "UPDATE departman SET
                    division_code = NULL,
                    division_name = NULL
                    WHERE id = '$dept_id'";
                } else {
                    $update_query = "UPDATE departman SET
                    division_code = '$dept_division_code',
                    division_name = '$dept_division_name'
                    WHERE id = '$dept_id'";
                }

                if (!mysqli_query($Link, $update_query)) {
                    $dept_message = 'خطا: ' . mysqli_error($Link);
                    $dept_message_type = 'danger';
                    return;
                }
            }

            $dept_message = 'اطلاعات با موفقیت ثبت شد';
            $dept_message_type = 'success';
        } elseif ($action === 'create_division') {

            $division_id = mysqli_real_escape_string($Link, trim($_POST['division_id'] ?? ''));
            $division_name = mysqli_real_escape_string($Link, trim($_POST['division_name'] ?? ''));
            $division_company_code = mysqli_real_escape_string($Link, $_POST['division_company_code'] ?? '');
            $division_company_name = '';

            if ($division_id === '' || $division_name === '' || $division_company_code === '') {
                $dept_message = 'لطفا نام، کد و شرکت معاونت را وارد کنید.';
                $dept_message_type = 'warning';
            } else {
                $company_lookup = "SELECT name FROM sherkatha WHERE code = '$division_company_code' LIMIT 1";
                if ($company_result = mysqli_query($Link, $company_lookup)) {
                    if ($company_row = mysqli_fetch_assoc($company_result)) {
                        $division_company_name = mysqli_real_escape_string($Link, $company_row['name']);
                    }
                }

                $insert_query = "INSERT INTO moavenat (id, name, company_code, company_name, vaziat, sort_order)
                    VALUES ('$division_id', '$division_name', '$division_company_code', '$division_company_name', 'y', 0)";

                if (mysqli_query($Link, $insert_query)) {
                    $dept_message = 'معاونت با موفقیت ثبت شد';
                    $dept_message_type = 'success';
                } else {
                    $dept_message = 'خطا در ثبت معاونت: ' . mysqli_error($Link);
                    $dept_message_type = 'danger';
                }
            }
        } elseif ($action === 'toggle_division') {

            $division_id = mysqli_real_escape_string($Link, $_POST['division_id'] ?? '');
            $division_new_state = ($_POST['division_state'] ?? '') === 'y' ? 'y' : 'n';

            if ($division_id !== '') {
                $toggle_query = "UPDATE moavenat SET vaziat = '$division_new_state' WHERE id = '$division_id'";
                if (mysqli_query($Link, $toggle_query)) {
                    $dept_message = $division_new_state === 'y' ? 'معاونت فعال شد' : 'معاونت غیرفعال شد';
                    $dept_message_type = 'success';
                } else {
                    $dept_message = 'خطا: ' . mysqli_error($Link);
                    $dept_message_type = 'danger';
                }
            }
        }
    }

    // ---- Ticket visibility scope configuration (dynamic, per-user/per-company) ----
    $scope_message = '';
    $scope_message_type = '';

    mysqli_query(
        $Link,
        "CREATE TABLE IF NOT EXISTS ticket_view_scope_users (
            user_code VARCHAR(64) NOT NULL PRIMARY KEY,
            can_view_all CHAR(1) NOT NULL DEFAULT 'n',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    );
    mysqli_query(
        $Link,
        "CREATE TABLE IF NOT EXISTS ticket_view_scope_companies (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_code VARCHAR(64) NOT NULL,
            company_code VARCHAR(64) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_user_company (user_code, company_code),
            KEY idx_user_code (user_code),
            KEY idx_company_code (company_code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    );

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'set_ticket_scope_mode') {
            $scope_user_code = mysqli_real_escape_string($Link, $_POST['scope_user_code'] ?? '');
            $scope_mode = mysqli_real_escape_string($Link, $_POST['scope_mode'] ?? 'custom');

            if ($scope_user_code === '') {
                $scope_message = 'کاربر انتخاب نشده است.';
                $scope_message_type = 'warning';
            } else {
                if ($scope_mode === 'all') {
                    mysqli_query($Link, "INSERT INTO ticket_view_scope_users (user_code, can_view_all) VALUES ('$scope_user_code', 'y') ON DUPLICATE KEY UPDATE can_view_all = 'y'");
                    mysqli_query($Link, "DELETE FROM ticket_view_scope_companies WHERE user_code = '$scope_user_code'");
                    $scope_message = 'دسترسی مشاهده همه شرکت‌ها با موفقیت ثبت شد.';
                    $scope_message_type = 'success';
                } elseif ($scope_mode === 'none') {
                    mysqli_query($Link, "DELETE FROM ticket_view_scope_companies WHERE user_code = '$scope_user_code'");
                    mysqli_query($Link, "DELETE FROM ticket_view_scope_users WHERE user_code = '$scope_user_code'");
                    $scope_message = 'دسترسی ویژه کاربر حذف شد.';
                    $scope_message_type = 'success';
                } else {
                    mysqli_query($Link, "INSERT INTO ticket_view_scope_users (user_code, can_view_all) VALUES ('$scope_user_code', 'n') ON DUPLICATE KEY UPDATE can_view_all = 'n'");
                    $scope_message = 'حالت دسترسی کاربر روی "شرکت‌های منتخب" قرار گرفت.';
                    $scope_message_type = 'success';
                }
            }
        } elseif ($action === 'add_ticket_scope_company') {
            $scope_user_code = mysqli_real_escape_string($Link, $_POST['scope_user_code'] ?? '');
            $scope_company_codes_raw = $_POST['scope_company_codes'] ?? [];

            // Backward compatibility for old single-select field (if still posted)
            if (empty($scope_company_codes_raw) && !empty($_POST['scope_company_code'])) {
                $scope_company_codes_raw = [$_POST['scope_company_code']];
            }

            $scope_company_codes = [];
            if (is_array($scope_company_codes_raw)) {
                foreach ($scope_company_codes_raw as $raw_company_code) {
                    $trimmed_company_code = trim((string) $raw_company_code);
                    if ($trimmed_company_code !== '') {
                        $scope_company_codes[] = mysqli_real_escape_string($Link, $trimmed_company_code);
                    }
                }
            }
            $scope_company_codes = array_values(array_unique($scope_company_codes));

            if ($scope_user_code === '' || empty($scope_company_codes)) {
                $scope_message = 'کاربر یا شرکت انتخاب نشده است.';
                $scope_message_type = 'warning';
            } else {
                mysqli_query($Link, "INSERT INTO ticket_view_scope_users (user_code, can_view_all) VALUES ('$scope_user_code', 'n') ON DUPLICATE KEY UPDATE can_view_all = 'n'");
                $added_count = 0;
                foreach ($scope_company_codes as $scope_company_code) {
                    if (mysqli_query($Link, "INSERT INTO ticket_view_scope_companies (user_code, company_code) VALUES ('$scope_user_code', '$scope_company_code') ON DUPLICATE KEY UPDATE company_code = company_code")) {
                        $added_count++;
                    }
                }
                $scope_message = $added_count . ' شرکت به دسترسی کاربر اضافه شد.';
                $scope_message_type = 'success';
            }
        } elseif ($action === 'remove_ticket_scope_company') {
            $scope_user_code = mysqli_real_escape_string($Link, $_POST['scope_user_code'] ?? '');
            $scope_company_code = mysqli_real_escape_string($Link, $_POST['scope_company_code'] ?? '');

            if ($scope_user_code !== '' && $scope_company_code !== '') {
                mysqli_query($Link, "DELETE FROM ticket_view_scope_companies WHERE user_code = '$scope_user_code' AND company_code = '$scope_company_code'");
                $scope_message = 'شرکت از دسترسی کاربر حذف شد.';
                $scope_message_type = 'success';
            }
        }
    }

    // ---- Data loading ----
    // All departments
    $departments = [];
    $query_depts = "SELECT id, name,
       default_user_code, default_user_name,
       default_company_code, default_company_name,
       division_code, division_name,
       vaziat
FROM departman
                WHERE vaziat = 'y'
                ORDER BY name ASC";
    if ($result_depts = mysqli_query($Link, $query_depts)) {
        while ($row = mysqli_fetch_array($result_depts)) {
            $departments[] = $row;
        }
    }

    // All divisions (معاونت) with department counts
    $divisions = [];
    $query_divisions = "SELECT m.id, m.name, m.company_code, m.company_name, m.vaziat,
                               (SELECT COUNT(*) FROM departman d WHERE d.division_code = m.id AND d.vaziat = 'y') AS dept_count
                        FROM moavenat m
                        ORDER BY m.company_name ASC, m.sort_order ASC, m.name ASC";
    if ($result_divisions = mysqli_query($Link, $query_divisions)) {
        while ($row = mysqli_fetch_assoc($result_divisions)) {
            $divisions[] = $row;
        }
    }

    // All active users (for dropdowns)
    $dept_users = [];
    $query_users = "SELECT code_p, name, semat
                FROM karbar
                WHERE vaziat = 'y'
                ORDER BY name ASC";
    if ($result_users = mysqli_query($Link, $query_users)) {
        while ($row = mysqli_fetch_array($result_users)) {
            $dept_users[] = $row;
        }
    }

    // All companies (for dropdowns)
    $dept_companys  = [];
    $query_companys = "SELECT code, name
                FROM sherkatha
                ORDER BY name ASC";
    if ($result_companys = mysqli_query($Link, $query_companys)) {
        while ($row = mysqli_fetch_array($result_companys)) {
            $dept_companys[] = $row;
        }
    }

    // Current ticket scope assignments
    $scope_assignments = [];
    $query_scope = "SELECT
                        su.user_code,
                        su.can_view_all,
                        k.name AS user_name,
                        GROUP_CONCAT(sc.company_code ORDER BY sc.company_code SEPARATOR ',') AS company_codes,
                        GROUP_CONCAT(sh.name ORDER BY sh.name SEPARATOR ' | ') AS company_names
                    FROM ticket_view_scope_users su
                    LEFT JOIN karbar k ON k.code_p = su.user_code
                    LEFT JOIN ticket_view_scope_companies sc ON sc.user_code = su.user_code
                    LEFT JOIN sherkatha sh ON sh.code = sc.company_code
                    GROUP BY su.user_code, su.can_view_all, k.name
                    ORDER BY k.name ASC, su.user_code ASC";
    if ($result_scope = mysqli_query($Link, $query_scope)) {
        while ($row = mysqli_fetch_assoc($result_scope)) {
            $scope_assignments[] = $row;
        }
    }
?>

    <style>
        .settings-wrap .settings-tabs {
            position: sticky;
            top: 0;
            z-index: 5;
            background: var(--bs-body-bg);
            padding: .65rem 0;
            border-bottom: 1px solid var(--bs-border-color);
            margin-bottom: 1rem;
        }
        .settings-wrap .settings-tabs .nav-link {
            border-radius: 10px;
            font-weight: 600;
            padding: .55rem 1.1rem;
            color: var(--bs-secondary-color);
        }
        .settings-wrap .settings-tabs .nav-link.active {
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), .25);
        }
        .settings-wrap .create-card {
            height: 100%;
            border: 1px solid var(--bs-border-color);
        }
        .settings-wrap .create-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--bs-border-color);
            font-weight: 700;
        }
        .settings-wrap .create-card .card-header .bi {
            color: var(--bs-primary);
        }
        .settings-wrap .section-title {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        /* Department linking table cells */
        .settings-wrap .dept-link-table td,
        .settings-wrap .dept-link-table th {
            vertical-align: middle;
        }
        /* Vertical separators between columns */
        .settings-wrap .dept-link-table th:not(:last-child),
        .settings-wrap .dept-link-table td:not(:last-child) {
            border-inline-end: 1px solid var(--bs-border-color);
        }
        .settings-wrap .dept-link-table > :not(caption) > * > * {
            padding-inline: .9rem;
        }
        .settings-wrap .assign-cell {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
        }
        .settings-wrap .assign-badge {
            font-weight: 600;
            font-size: .8rem;
            max-width: 100%;
            white-space: normal;
            text-align: start;
            line-height: 1.5;
        }
        .settings-wrap .assign-empty {
            color: var(--bs-secondary-color);
            font-size: .82rem;
            white-space: nowrap;
        }
        .settings-wrap .assign-actions {
            display: inline-flex;
            gap: .25rem;
            flex: 0 0 auto;
        }
        .settings-wrap .assign-actions .btn {
            --bs-btn-padding-y: .2rem;
            --bs-btn-padding-x: .5rem;
            line-height: 1;
        }
    </style>

    <div class="settings-wrap">

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <h4 class="mb-0 section-title"><i class="bi bi-gear-wide-connected"></i>تنظیمات و مدیریت سیستم</h4>
        </div>

        <ul class="nav nav-pills settings-tabs flex-wrap" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-org-btn" data-bs-toggle="pill" data-bs-target="#tab-org" type="button" role="tab">
                    <i class="bi bi-diagram-3 me-1"></i>ساختار سازمانی
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-users-btn" data-bs-toggle="pill" data-bs-target="#tab-users" type="button" role="tab">
                    <i class="bi bi-people me-1"></i>کاربران و دسته‌بندی
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-connect-btn" data-bs-toggle="pill" data-bs-target="#tab-connect" type="button" role="tab">
                    <i class="bi bi-people me-1"></i>اتصال کاربران
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-access-btn" data-bs-toggle="pill" data-bs-target="#tab-access" type="button" role="tab">
                    <i class="bi bi-shield-check me-1"></i>دسترسی تیکت‌ها
                </button>
            </li>
        </ul>

        <div class="tab-content">

            <!-- ============================ TAB: ساختار سازمانی ============================ -->
            <div class="tab-pane fade show active" id="tab-org" role="tabpanel" tabindex="0">

                <?php if ($dept_message): ?>
                    <div class="alert alert-<?php echo $dept_message_type; ?> alert-dismissible fade show" role="alert">
                        <i class="bi bi-<?php echo $dept_message_type === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                        <?php echo htmlspecialchars($dept_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <p class="text-muted mb-3" style="font-size: .9rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    ساختار به صورت <strong>شرکت ← معاونت ← دپارتمان</strong> است. ابتدا شرکت و معاونت را بسازید،
                    سپس از جدول دپارتمان‌ها هر دپارتمان را به شرکت، معاونت و کاربر پیش‌فرض متصل کنید.
                </p>

                <!-- Unified creation panel: company / division / department -->
                <div class="row g-3 mb-4">
                    <div class="col-lg-4">
                        <div class="card create-card">
                            <div class="card-header"><i class="bi bi-building me-2"></i>ثبت شرکت جدید</div>
                            <div class="card-body">
                                <form method="post" action="?page=s_new_sherkat" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label" for="name_co">نام شرکت</label>
                                        <input type="text" class="form-control" id="name_co" name="name_co" placeholder=" ">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="id_co">ایدی / کد شرکت</label>
                                        <input type="text" class="form-control" id="id_co" name="id_co" placeholder=" ">
                                    </div>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="reset" class="btn btn-outline-secondary">لغو</button>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>ثبت</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card create-card">
                            <div class="card-header"><i class="bi bi-diagram-3 me-2"></i>ثبت معاونت جدید</div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="create_division">
                                    <div class="mb-3">
                                        <label class="form-label" for="division_name">نام معاونت</label>
                                        <input type="text" class="form-control" id="division_name" name="division_name" placeholder="مثلا: منابع انسانی" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="division_id">کد معاونت</label>
                                        <input type="text" class="form-control" id="division_id" name="division_id" placeholder="مثلا: hr" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="division_company_code">شرکت</label>
                                        <select class="form-select" id="division_company_code" name="division_company_code" required>
                                            <option value="">انتخاب شرکت...</option>
                                            <?php foreach ($dept_companys as $company): ?>
                                                <option value="<?php echo htmlspecialchars($company['code']); ?>"><?php echo htmlspecialchars($company['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="reset" class="btn btn-outline-secondary">لغو</button>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>ثبت معاونت</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card create-card">
                            <div class="card-header"><i class="bi bi-folder-plus me-2"></i>ثبت دپارتمان جدید</div>
                            <div class="card-body">
                                <form method="post" action="?page=s_new_daste" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label" for="name_daste">نام دپارتمان</label>
                                        <input type="text" class="form-control" id="name_daste" name="name_daste" placeholder=" ">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="id_daste">ایدی / کد دپارتمان</label>
                                        <input type="text" class="form-control" id="id_daste" name="id_daste" placeholder=" ">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="daste_company_code">شرکت گیرنده <small class="text-muted">(اختیاری)</small></label>
                                        <select class="form-select" id="daste_company_code" name="daste_company_code">
                                            <option value="">بدون شرکت (بعدا تعیین می‌شود)</option>
                                            <?php foreach ($dept_companys as $company): ?>
                                                <option value="<?php echo htmlspecialchars($company['code']); ?>"><?php echo htmlspecialchars($company['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="reset" class="btn btn-outline-secondary">لغو</button>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>ثبت دپارتمان</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Divisions management table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-diagram-3 me-2"></i>معاونت‌ها</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 30%;">نام معاونت</th>
                                        <th style="width: 30%;">شرکت</th>
                                        <th style="width: 15%;">تعداد دپارتمان</th>
                                        <th style="width: 10%;">وضعیت</th>
                                        <th style="width: 10%;">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($divisions)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                                هنوز معاونتی ثبت نشده است
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($divisions as $index => $division): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($division['name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">کد: <?php echo htmlspecialchars($division['id']); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($division['company_name'] ?: $division['company_code']); ?></td>
                                                <td><span class="badge bg-secondary"><?php echo (int) $division['dept_count']; ?></span></td>
                                                <td>
                                                    <?php if ($division['vaziat'] === 'y'): ?>
                                                        <span class="badge bg-success">فعال</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">غیرفعال</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <form method="POST" action="" class="d-inline">
                                                        <input type="hidden" name="action" value="toggle_division">
                                                        <input type="hidden" name="division_id" value="<?php echo htmlspecialchars($division['id']); ?>">
                                                        <input type="hidden" name="division_state" value="<?php echo $division['vaziat'] === 'y' ? 'n' : 'y'; ?>">
                                                        <button type="submit" class="btn btn-sm <?php echo $division['vaziat'] === 'y' ? 'btn-outline-secondary' : 'btn-outline-success'; ?>">
                                                            <?php echo $division['vaziat'] === 'y' ? 'غیرفعال' : 'فعال'; ?>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Department linking table (company / division / default user) -->
                <div class="card dept-link-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-building me-2"></i>اتصال دپارتمان‌ها (شرکت / معاونت / کاربر پیش‌فرض)
                                </h5>
                                <p class="text-muted mb-0 mt-2" style="font-size: 0.85rem;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    برای هر دپارتمان شرکت، معاونت و کاربر پیش‌فرض را تعیین کنید.
                                    تیکت‌های جدید به صورت خودکار به کاربر پیش‌فرض دپارتمان اختصاص می‌یابند.
                                </p>
                            </div>
                            <div>
                                <a href="?page=assign_default_users" class="btn btn-sm btn-outline-primary"
                                    onclick="return confirm('آیا می‌خواهید تمام تیکت‌های ثبت اولیه بدون پاسخگو را به کاربران پیش‌فرض دپارتمان‌ها اختصاص دهید؟');">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    اختصاص خودکار تیکت‌های موجود
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        // Build distinct filter option lists from currently assigned departments
                        $filter_companies = [];
                        $filter_divisions = [];
                        $filter_users = [];
                        foreach ($departments as $dept_opt) {
                            if (!empty($dept_opt['default_company_code'])) {
                                $filter_companies[$dept_opt['default_company_code']] = $dept_opt['default_company_name'] ?: $dept_opt['default_company_code'];
                            }
                            if (!empty($dept_opt['division_code'])) {
                                $filter_divisions[$dept_opt['division_code']] = $dept_opt['division_name'] ?: $dept_opt['division_code'];
                            }
                            if (!empty($dept_opt['default_user_code'])) {
                                $filter_users[$dept_opt['default_user_code']] = $dept_opt['default_user_name'] ?: $dept_opt['default_user_code'];
                            }
                        }
                        asort($filter_companies, SORT_FLAG_CASE | SORT_STRING);
                        asort($filter_divisions, SORT_FLAG_CASE | SORT_STRING);
                        asort($filter_users, SORT_FLAG_CASE | SORT_STRING);
                        ?>
                        <div class="row g-2 align-items-end mb-3">
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label small text-muted mb-1">جستجو</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control border-start-0" id="deptLinkSearch" placeholder="نام یا کد دپارتمان..." autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-6">
                                <label class="form-label small text-muted mb-1"><i class="bi bi-building me-1"></i>شرکت</label>
                                <select class="form-select dept-link-filter" id="deptFilterCompany">
                                    <option value="">همه شرکت‌ها</option>
                                    <option value="__none__">بدون شرکت</option>
                                    <?php foreach ($filter_companies as $code => $name): ?>
                                        <option value="<?php echo htmlspecialchars($code); ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-4 col-6">
                                <label class="form-label small text-muted mb-1"><i class="bi bi-diagram-3 me-1"></i>معاونت</label>
                                <select class="form-select dept-link-filter" id="deptFilterDivision">
                                    <option value="">همه معاونت‌ها</option>
                                    <option value="__none__">بدون معاونت</option>
                                    <?php foreach ($filter_divisions as $code => $name): ?>
                                        <option value="<?php echo htmlspecialchars($code); ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-4 col-6">
                                <label class="form-label small text-muted mb-1"><i class="bi bi-person-fill me-1"></i>کاربر پیش‌فرض</label>
                                <select class="form-select dept-link-filter" id="deptFilterUser">
                                    <option value="">همه کاربران</option>
                                    <option value="__none__">بدون کاربر</option>
                                    <?php foreach ($filter_users as $code => $name): ?>
                                        <option value="<?php echo htmlspecialchars($code); ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                <i class="bi bi-list-ul me-1"></i>
                                نمایش <span id="deptLinkVisibleCount"><?php echo count($departments); ?></span> از <?php echo count($departments); ?> دپارتمان
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deptLinkResetFilters">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>حذف فیلترها
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle dept-link-table">
                                <thead>
                                    <tr>
                                        <th style="width: 4%;">#</th>
                                        <th style="width: 26%;">دپارتمان</th>
                                        <th style="width: 24%;"><i class="bi bi-building me-1"></i>شرکت</th>
                                        <th style="width: 23%;"><i class="bi bi-diagram-3 me-1"></i>معاونت</th>
                                        <th style="width: 23%;"><i class="bi bi-person-fill me-1"></i>کاربر پیش‌فرض</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($departments)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                                هیچ دپارتمانی یافت نشد
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($departments as $index => $dept): ?>
                                            <tr class="dept-link-row"
                                                data-search="<?php echo htmlspecialchars(strtolower($dept['name'] . ' ' . $dept['id'])); ?>"
                                                data-company="<?php echo htmlspecialchars($dept['default_company_code'] ?? ''); ?>"
                                                data-division="<?php echo htmlspecialchars($dept['division_code'] ?? ''); ?>"
                                                data-user="<?php echo htmlspecialchars($dept['default_user_code'] ?? ''); ?>">
                                                <td class="text-muted"><?php echo $index + 1; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($dept['name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">کد: <?php echo htmlspecialchars($dept['id']); ?></small>
                                                </td>

                                                <!-- شرکت -->
                                                <td>
                                                    <div class="assign-cell">
                                                        <?php if (!empty($dept['default_company_code'])): ?>
                                                            <span class="badge bg-primary-subtle text-primary-emphasis assign-badge">
                                                                <i class="bi bi-building me-1"></i>
                                                                <?php echo htmlspecialchars($dept['default_company_name']); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="assign-empty"><i class="bi bi-dash-circle me-1"></i>تعیین نشده</span>
                                                        <?php endif; ?>
                                                        <div class="assign-actions">
                                                            <button type="button" class="btn btn-sm btn-outline-primary open-company-select-modal"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#companySelectModal"
                                                                data-dept-id="<?php echo htmlspecialchars($dept['id']); ?>"
                                                                data-dept-name="<?php echo htmlspecialchars($dept['name']); ?>"
                                                                title="<?php echo !empty($dept['default_company_code']) ? 'تغییر شرکت' : 'انتخاب شرکت'; ?>">
                                                                <i class="bi bi-<?php echo !empty($dept['default_company_code']) ? 'pencil' : 'plus-lg'; ?>"></i>
                                                            </button>
                                                            <?php if (!empty($dept['default_company_code'])): ?>
                                                                <form method="POST" action="" class="d-inline"
                                                                    onsubmit="return confirm('آیا از حذف شرکت پیش‌فرض اطمینان دارید؟');">
                                                                    <input type="hidden" name="action" value="update_dept_default">
                                                                    <input type="hidden" name="dept_id" value="<?php echo htmlspecialchars($dept['id']); ?>">
                                                                    <input type="hidden" name="company_code" value="">
                                                                    <input type="hidden" name="company_name" value="">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف شرکت پیش‌فرض">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- معاونت -->
                                                <td>
                                                    <div class="assign-cell">
                                                        <?php if (!empty($dept['division_code'])): ?>
                                                            <span class="badge bg-info-subtle text-info-emphasis assign-badge">
                                                                <i class="bi bi-diagram-3 me-1"></i>
                                                                <?php echo htmlspecialchars($dept['division_name']); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="assign-empty"><i class="bi bi-dash-circle me-1"></i>تعیین نشده</span>
                                                        <?php endif; ?>
                                                        <div class="assign-actions">
                                                            <button type="button" class="btn btn-sm btn-outline-info open-division-select-modal"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#divisionSelectModal"
                                                                data-dept-id="<?php echo htmlspecialchars($dept['id']); ?>"
                                                                data-dept-name="<?php echo htmlspecialchars($dept['name']); ?>"
                                                                data-company-code="<?php echo htmlspecialchars($dept['default_company_code'] ?? ''); ?>"
                                                                <?php echo empty($dept['default_company_code']) ? 'disabled title="ابتدا شرکت را تعیین کنید"' : 'title="' . (!empty($dept['division_code']) ? 'تغییر معاونت' : 'انتخاب معاونت') . '"'; ?>>
                                                                <i class="bi bi-<?php echo !empty($dept['division_code']) ? 'pencil' : 'plus-lg'; ?>"></i>
                                                            </button>
                                                            <?php if (!empty($dept['division_code'])): ?>
                                                                <form method="POST" action="" class="d-inline"
                                                                    onsubmit="return confirm('آیا از حذف معاونت اطمینان دارید؟');">
                                                                    <input type="hidden" name="action" value="update_dept_default">
                                                                    <input type="hidden" name="dept_id" value="<?php echo htmlspecialchars($dept['id']); ?>">
                                                                    <input type="hidden" name="division_code" value="">
                                                                    <input type="hidden" name="division_name" value="">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف معاونت">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- کاربر پیش‌فرض -->
                                                <td>
                                                    <div class="assign-cell">
                                                        <?php if (!empty($dept['default_user_code'])): ?>
                                                            <span class="badge bg-success-subtle text-success-emphasis assign-badge">
                                                                <i class="bi bi-person-fill me-1"></i>
                                                                <?php echo htmlspecialchars($dept['default_user_name']); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="assign-empty"><i class="bi bi-dash-circle me-1"></i>تعیین نشده</span>
                                                        <?php endif; ?>
                                                        <div class="assign-actions">
                                                            <button type="button" class="btn btn-sm btn-outline-success open-user-select-modal"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#userSelectModal"
                                                                data-dept-id="<?php echo htmlspecialchars($dept['id']); ?>"
                                                                data-dept-name="<?php echo htmlspecialchars($dept['name']); ?>"
                                                                title="<?php echo !empty($dept['default_user_code']) ? 'تغییر کاربر' : 'انتخاب کاربر'; ?>">
                                                                <i class="bi bi-<?php echo !empty($dept['default_user_code']) ? 'pencil' : 'plus-lg'; ?>"></i>
                                                            </button>
                                                            <?php if (!empty($dept['default_user_code'])): ?>
                                                                <form method="POST" action="" class="d-inline"
                                                                    onsubmit="return confirm('آیا از حذف کاربر پیش‌فرض اطمینان دارید؟');">
                                                                    <input type="hidden" name="action" value="update_dept_default">
                                                                    <input type="hidden" name="dept_id" value="<?php echo htmlspecialchars($dept['id']); ?>">
                                                                    <input type="hidden" name="user_code" value="">
                                                                    <input type="hidden" name="user_name" value="">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف کاربر پیش‌فرض">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr id="deptLinkNoResults" style="display: none;">
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-search fs-3 d-block mb-2 opacity-50"></i>
                                                دپارتمانی با این جستجو یافت نشد
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shared user/company/division selection modals -->
                <div class="modal fade" id="userSelectModal" tabindex="-1" aria-labelledby="userSelectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="userSelectModalLabel">
                                    <i class="bi bi-person-plus me-2"></i>انتخاب کاربر پیش‌فرض
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control border-start-0" id="sharedUserFilter" placeholder="جستجو در لیست کاربران..." autocomplete="off">
                                    </div>
                                </div>
                                <div id="sharedUserTableContainer" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-hover m-0">
                                        <thead class="sticky-top" style="background: var(--bs-tertiary-bg);">
                                            <tr>
                                                <th style="border-radius: 8px 0 0 0;">نام کاربر</th>
                                                <th style="border-radius: 0 8px 0 0;">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dept_users as $user): ?>
                                                <tr class="shared-user-row" data-name="<?php echo strtolower($user['name']); ?>">
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--bs-primary), var(--bs-info)); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: bold;">
                                                                <?php echo mb_substr($user['name'], 0, 1, 'UTF-8'); ?>
                                                            </div>
                                                            <div>
                                                                <div><?php echo htmlspecialchars($user['name']); ?></div>
                                                                <small class="text-muted"><?php echo htmlspecialchars($user['code_p']); ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="">
                                                            <input type="hidden" name="action" value="update_dept_default">
                                                            <input type="hidden" name="dept_id" class="shared-selected-dept-id" value="">
                                                            <input type="hidden" name="user_code" value="<?php echo htmlspecialchars($user['code_p']); ?>">
                                                            <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($user['name']); ?>">
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="bi bi-check2"></i> انتخاب
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center text-muted py-4" id="sharedUserNoResults" style="display: none;">
                                    <i class="bi bi-search fs-1 d-block mb-2" style="opacity: 0.5;"></i>
                                    <p class="mb-0">هیچ کاربری یافت نشد</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="companySelectModal" tabindex="-1" aria-labelledby="companySelectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="companySelectModalLabel">
                                    <i class="bi bi-building me-2"></i>انتخاب شرکت پیش‌فرض
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control border-start-0" id="sharedCompanyFilter" placeholder="جستجو در لیست شرکت‌ها..." autocomplete="off">
                                    </div>
                                </div>
                                <div id="sharedCompanyTableContainer" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-hover m-0">
                                        <thead class="sticky-top" style="background: var(--bs-tertiary-bg);">
                                            <tr>
                                                <th style="border-radius: 8px 0 0 0;">نام شرکت</th>
                                                <th style="border-radius: 0 8px 0 0;">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dept_companys as $company): ?>
                                                <tr class="shared-company-row" data-name="<?php echo strtolower($company['name']); ?>">
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--bs-primary), var(--bs-info)); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: bold;">
                                                                <?php echo mb_substr($company['name'], 0, 1, 'UTF-8'); ?>
                                                            </div>
                                                            <div>
                                                                <div><?php echo htmlspecialchars($company['name']); ?></div>
                                                                <small class="text-muted"><?php echo htmlspecialchars($company['code']); ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="">
                                                            <input type="hidden" name="action" value="update_dept_default">
                                                            <input type="hidden" name="dept_id" class="shared-selected-dept-id" value="">
                                                            <input type="hidden" name="company_code" value="<?php echo htmlspecialchars($company['code']); ?>">
                                                            <input type="hidden" name="company_name" value="<?php echo htmlspecialchars($company['name']); ?>">
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="bi bi-check2"></i> انتخاب
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center text-muted py-4" id="sharedCompanyNoResults" style="display: none;">
                                    <i class="bi bi-search fs-1 d-block mb-2" style="opacity: 0.5;"></i>
                                    <p class="mb-0">هیچ شرکتی یافت نشد</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="divisionSelectModal" tabindex="-1" aria-labelledby="divisionSelectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="divisionSelectModalLabel">
                                    <i class="bi bi-diagram-3 me-2"></i>انتخاب معاونت
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control border-start-0" id="sharedDivisionFilter" placeholder="جستجو در لیست معاونت‌ها..." autocomplete="off">
                                    </div>
                                </div>
                                <div id="sharedDivisionTableContainer" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-hover m-0">
                                        <thead class="sticky-top" style="background: var(--bs-tertiary-bg);">
                                            <tr>
                                                <th style="border-radius: 8px 0 0 0;">نام معاونت</th>
                                                <th style="border-radius: 0 8px 0 0;">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($divisions as $division): ?>
                                                <?php if ($division['vaziat'] !== 'y') continue; ?>
                                                <tr class="shared-division-row"
                                                    data-name="<?php echo strtolower($division['name']); ?>"
                                                    data-company-code="<?php echo htmlspecialchars($division['company_code']); ?>">
                                                    <td>
                                                        <div><?php echo htmlspecialchars($division['name']); ?></div>
                                                        <small class="text-muted"><?php echo htmlspecialchars($division['company_name'] ?: $division['company_code']); ?></small>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="">
                                                            <input type="hidden" name="action" value="update_dept_default">
                                                            <input type="hidden" name="dept_id" class="shared-selected-dept-id" value="">
                                                            <input type="hidden" name="division_code" value="<?php echo htmlspecialchars($division['id']); ?>">
                                                            <input type="hidden" name="division_name" value="<?php echo htmlspecialchars($division['name']); ?>">
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="bi bi-check2"></i> انتخاب
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center text-muted py-4" id="sharedDivisionNoResults" style="display: none;">
                                    <i class="bi bi-search fs-1 d-block mb-2" style="opacity: 0.5;"></i>
                                    <p class="mb-0">معاونتی برای این شرکت یافت نشد</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Department linking table: combined text + dropdown filters
                        var deptLinkSearch = document.getElementById('deptLinkSearch');
                        var deptFilterCompany = document.getElementById('deptFilterCompany');
                        var deptFilterDivision = document.getElementById('deptFilterDivision');
                        var deptFilterUser = document.getElementById('deptFilterUser');
                        var deptLinkResetFilters = document.getElementById('deptLinkResetFilters');
                        var deptLinkVisibleCount = document.getElementById('deptLinkVisibleCount');
                        var deptLinkRows = Array.from(document.querySelectorAll('.dept-link-row'));
                        var deptLinkNoResults = document.getElementById('deptLinkNoResults');

                        function matchSelect(filterValue, rowValue) {
                            if (filterValue === '') return true;            // "all"
                            if (filterValue === '__none__') return rowValue === ''; // unassigned
                            return rowValue === filterValue;
                        }

                        function applyDeptLinkFilters() {
                            var term = deptLinkSearch ? deptLinkSearch.value.toLowerCase().trim() : '';
                            var companyVal = deptFilterCompany ? deptFilterCompany.value : '';
                            var divisionVal = deptFilterDivision ? deptFilterDivision.value : '';
                            var userVal = deptFilterUser ? deptFilterUser.value : '';
                            var visible = 0;

                            deptLinkRows.forEach(function(row) {
                                var hay = row.getAttribute('data-search') || '';
                                var show = hay.indexOf(term) !== -1 &&
                                    matchSelect(companyVal, row.getAttribute('data-company') || '') &&
                                    matchSelect(divisionVal, row.getAttribute('data-division') || '') &&
                                    matchSelect(userVal, row.getAttribute('data-user') || '');
                                row.style.display = show ? '' : 'none';
                                if (show) visible++;
                            });

                            if (deptLinkNoResults) {
                                deptLinkNoResults.style.display = (visible === 0) ? '' : 'none';
                            }
                            if (deptLinkVisibleCount) {
                                deptLinkVisibleCount.textContent = visible;
                            }
                        }

                        if (deptLinkSearch) deptLinkSearch.addEventListener('input', applyDeptLinkFilters);
                        if (deptFilterCompany) deptFilterCompany.addEventListener('change', applyDeptLinkFilters);
                        if (deptFilterDivision) deptFilterDivision.addEventListener('change', applyDeptLinkFilters);
                        if (deptFilterUser) deptFilterUser.addEventListener('change', applyDeptLinkFilters);
                        if (deptLinkResetFilters) {
                            deptLinkResetFilters.addEventListener('click', function() {
                                if (deptLinkSearch) deptLinkSearch.value = '';
                                if (deptFilterCompany) deptFilterCompany.value = '';
                                if (deptFilterDivision) deptFilterDivision.value = '';
                                if (deptFilterUser) deptFilterUser.value = '';
                                applyDeptLinkFilters();
                            });
                        }

                        function setSelectedDeptId(deptId) {
                            document.querySelectorAll('.shared-selected-dept-id').forEach(function(input) {
                                input.value = deptId || '';
                            });
                        }

                        function resetFilter(inputEl, rows, noResultsEl, tableContainerEl) {
                            if (inputEl) inputEl.value = '';
                            rows.forEach(function(row) {
                                row.style.display = '';
                            });
                            if (noResultsEl) noResultsEl.style.display = 'none';
                            if (tableContainerEl) tableContainerEl.style.display = 'block';
                        }

                        function wireFilter(inputEl, rows, noResultsEl, tableContainerEl) {
                            if (!inputEl) return;
                            inputEl.addEventListener('input', function() {
                                var filterValue = inputEl.value.toLowerCase().trim();
                                var visibleCount = 0;
                                rows.forEach(function(row) {
                                    var name = (row.getAttribute('data-name') || '').toLowerCase();
                                    var isVisible = name.includes(filterValue);
                                    row.style.display = isVisible ? '' : 'none';
                                    if (isVisible) visibleCount++;
                                });
                                if (visibleCount === 0 && filterValue !== '') {
                                    if (noResultsEl) noResultsEl.style.display = 'block';
                                    if (tableContainerEl) tableContainerEl.style.display = 'none';
                                } else {
                                    if (noResultsEl) noResultsEl.style.display = 'none';
                                    if (tableContainerEl) tableContainerEl.style.display = 'block';
                                }
                            });
                        }

                        var userModalTitle = document.getElementById('userSelectModalLabel');
                        var companyModalTitle = document.getElementById('companySelectModalLabel');
                        document.querySelectorAll('.open-user-select-modal').forEach(function(button) {
                            button.addEventListener('click', function() {
                                var deptId = button.getAttribute('data-dept-id');
                                var deptName = button.getAttribute('data-dept-name') || '';
                                setSelectedDeptId(deptId);
                                if (userModalTitle) {
                                    userModalTitle.innerHTML = '<i class="bi bi-person-plus me-2"></i>انتخاب کاربر پیش‌فرض برای: ' + deptName;
                                }
                            });
                        });
                        document.querySelectorAll('.open-company-select-modal').forEach(function(button) {
                            button.addEventListener('click', function() {
                                var deptId = button.getAttribute('data-dept-id');
                                var deptName = button.getAttribute('data-dept-name') || '';
                                setSelectedDeptId(deptId);
                                if (companyModalTitle) {
                                    companyModalTitle.innerHTML = '<i class="bi bi-building me-2"></i>انتخاب شرکت پیش‌فرض برای: ' + deptName;
                                }
                            });
                        });

                        var divisionModalTitle = document.getElementById('divisionSelectModalLabel');
                        var divisionRows = Array.from(document.querySelectorAll('.shared-division-row'));
                        var divisionNoResults = document.getElementById('sharedDivisionNoResults');
                        var divisionTableContainer = document.getElementById('sharedDivisionTableContainer');
                        var divisionFilterInput = document.getElementById('sharedDivisionFilter');
                        var activeDivisionCompanyCode = '';

                        function applyDivisionFilters() {
                            var filterValue = (divisionFilterInput && divisionFilterInput.value || '').toLowerCase().trim();
                            var visibleCount = 0;
                            divisionRows.forEach(function(row) {
                                var name = (row.getAttribute('data-name') || '').toLowerCase();
                                var companyCode = row.getAttribute('data-company-code') || '';
                                var matchesCompany = !activeDivisionCompanyCode || companyCode === activeDivisionCompanyCode;
                                var matchesName = name.includes(filterValue);
                                var isVisible = matchesCompany && matchesName;
                                row.style.display = isVisible ? '' : 'none';
                                if (isVisible) visibleCount++;
                            });
                            if (visibleCount === 0) {
                                if (divisionNoResults) divisionNoResults.style.display = 'block';
                                if (divisionTableContainer) divisionTableContainer.style.display = 'none';
                            } else {
                                if (divisionNoResults) divisionNoResults.style.display = 'none';
                                if (divisionTableContainer) divisionTableContainer.style.display = 'block';
                            }
                        }

                        if (divisionFilterInput) {
                            divisionFilterInput.addEventListener('input', applyDivisionFilters);
                        }

                        document.querySelectorAll('.open-division-select-modal').forEach(function(button) {
                            button.addEventListener('click', function() {
                                var deptId = button.getAttribute('data-dept-id');
                                var deptName = button.getAttribute('data-dept-name') || '';
                                activeDivisionCompanyCode = button.getAttribute('data-company-code') || '';
                                setSelectedDeptId(deptId);
                                if (divisionModalTitle) {
                                    divisionModalTitle.innerHTML = '<i class="bi bi-diagram-3 me-2"></i>انتخاب معاونت برای: ' + deptName;
                                }
                                if (divisionFilterInput) divisionFilterInput.value = '';
                                applyDivisionFilters();
                            });
                        });

                        var divisionModal = document.getElementById('divisionSelectModal');
                        if (divisionModal) {
                            divisionModal.addEventListener('hidden.bs.modal', function() {
                                if (divisionFilterInput) divisionFilterInput.value = '';
                            });
                        }

                        var userFilterInput = document.getElementById('sharedUserFilter');
                        var userRows = Array.from(document.querySelectorAll('.shared-user-row'));
                        var userNoResults = document.getElementById('sharedUserNoResults');
                        var userTableContainer = document.getElementById('sharedUserTableContainer');
                        wireFilter(userFilterInput, userRows, userNoResults, userTableContainer);

                        var companyFilterInput = document.getElementById('sharedCompanyFilter');
                        var companyRows = Array.from(document.querySelectorAll('.shared-company-row'));
                        var companyNoResults = document.getElementById('sharedCompanyNoResults');
                        var companyTableContainer = document.getElementById('sharedCompanyTableContainer');
                        wireFilter(companyFilterInput, companyRows, companyNoResults, companyTableContainer);

                        var userModal = document.getElementById('userSelectModal');
                        if (userModal) {
                            userModal.addEventListener('hidden.bs.modal', function() {
                                resetFilter(userFilterInput, userRows, userNoResults, userTableContainer);
                            });
                        }
                        var companyModal = document.getElementById('companySelectModal');
                        if (companyModal) {
                            companyModal.addEventListener('hidden.bs.modal', function() {
                                resetFilter(companyFilterInput, companyRows, companyNoResults, companyTableContainer);
                            });
                        }
                    });
                </script>

            </div>
            <!-- ============================ /TAB: ساختار سازمانی ============================ -->

            <!-- ============================ TAB: کاربران و دسته‌بندی ============================ -->
            <div class="tab-pane fade" id="tab-users" role="tabpanel" tabindex="0">

                <?php
                if (isset($_GET['p'])) {
                    $messages = [
                        'y'          => ['success', 'ثبت کاربر با موفقیت انجام شد ✅'],
                        'n'          => ['danger',  'خطا در ثبت اطلاعات ❌'],
                        'dup_code'   => ['warning', 'کد پرسنلی قبلاً ثبت شده است'],
                        'dup_email'  => ['warning', 'ایمیل قبلاً استفاده شده است'],
                        'dup_tel'    => ['warning', 'شماره تلفن قبلاً ثبت شده است'],
                    ];
                    if (isset($messages[$_GET['p']])) {
                        [$type, $text] = $messages[$_GET['p']];
                        echo "<div class='alert alert-$type alert-dismissible fade show' style = 'color: black;' role='alert'>
                            $text
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                          </div>";
                    }
                }
                ?>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-person-badge me-2"></i>ثبت کاربر پشتیبان</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="?page=s_new_poshtiban" enctype="multipart/form-data">
                            <div class="row gx-3">
                                <div class="col-lg-2 col-sm-3 col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="name_k">نام کاربر</label>
                                        <input type="text" class="form-control" id="name_k" value="<?= htmlspecialchars($_POST['name_k'] ?? '') ?>" name="name_k" placeholder=" ">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3 col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="code_k">کد پرسنلی</label>
                                        <input type="text" class="form-control" id="code_k" value="<?= htmlspecialchars($_POST['code_k'] ?? '') ?>" name="code_k" placeholder=" ">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3 col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="semat_k">سمت</label>
                                        <input type="text" class="form-control" id="semat_k" value="<?= htmlspecialchars($_POST['semat_k'] ?? '') ?>" name="semat_k" placeholder=" ">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3 col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="target_sherkat">شرکت</label>
                                        <select class="form-select" name="target_sherkat" id="target_sherkat">
                                            <?php foreach ($dept_companys as $company): ?>
                                                <option value="<?php echo htmlspecialchars($company['code']); ?>"><?php echo htmlspecialchars($company['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3 col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="tel_k">تلفن</label>
                                        <input type="text" class="form-control" id="tel_k" value="<?= htmlspecialchars($_POST['tel_k'] ?? '') ?>" name="tel_k" placeholder=" ">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3 col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="email_k">ایمیل</label>
                                        <input type="text" class="form-control" id="email_k" value="<?= htmlspecialchars($_POST['email_k'] ?? '') ?>" name="email_k" placeholder=" ">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3 col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="passs">پسورد</label>
                                        <input type="text" class="form-control" id="passs" name="passs" placeholder=" ">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="reset" class="btn btn-outline-secondary">لغو</button>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>ثبت</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-mortarboard me-2"></i>ثبت کتگوری‌های آموزشی</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="?page=s_new_cat">
                            <div class="row gx-3">
                                <div class="col-lg-3 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="name_cat_x">نام شاخه</label>
                                        <input type="text" class="form-control" id="name_cat_x" name="name_cat_x" placeholder=" ">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="id_cat_y">دسته بندی موضوعی :</label>
                                        <select class="form-select" name="id_cat_y" id="id_cat_y">
                                            <option value="new"> عنوان سر شاخه</option>
                                            <?php
                                            $Query_dep = "SELECT*from daste_mohtava where (fader = 'y' ) ORDER BY name_daste ASC LIMIT 200";
                                            if ($Result_dep = mysqli_query($Link, $Query_dep)) {
                                                while ($q_dep = mysqli_fetch_array($Result_dep)) {
                                            ?>
                                                    <option value="<?php echo $q_dep['id_daste']; ?>"><?php echo $q_dep['name_daste']; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="reset" class="btn btn-outline-secondary">لغو</button>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>ثبت</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <!-- ============================ /TAB: کاربران و دسته‌بندی ============================ -->

            <!-- ============================ TAB: دسترسی تیکت‌ها ============================ -->
            <div class="tab-pane fade" id="tab-access" role="tabpanel" tabindex="0">

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-check me-2"></i>مدیریت دسترسی مشاهده تیکت‌ها (داینامیک)
                        </h5>
                        <p class="text-muted mb-0 mt-2" style="font-size: 0.85rem;">
                            در این بخش می‌توانید برای هر کاربر سطح مشاهده تیکت را تعیین کنید:
                            همه شرکت‌ها یا شرکت‌های منتخب. کاربران ادمین قدیمی همچنان دسترسی کامل دارند.
                        </p>
                    </div>
                    <div class="card-body">
                        <?php if ($scope_message !== ''): ?>
                            <div class="alert alert-<?php echo htmlspecialchars($scope_message_type ?: 'info'); ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($scope_message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" id="scopeModeForm" class="row gx-3 gy-2 align-items-end mb-4">
                            <input type="hidden" name="action" value="set_ticket_scope_mode">
                            <input type="hidden" name="scope_user_code" id="scopeModeUserCode" value="">
                            <div class="col-md-4">
                                <label class="form-label">کاربر</label>
                                <div class="input-group">
                                    <input type="text" id="scopeModeUserLabel" class="form-control" value="" placeholder="کاربر انتخاب نشده" readonly required>
                                    <button type="button" class="btn btn-outline-secondary open-scope-user-modal" data-target-code="#scopeModeUserCode" data-target-label="#scopeModeUserLabel">
                                        انتخاب کاربر
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">نوع دسترسی</label>
                                <select name="scope_mode" class="form-select" required>
                                    <option value="custom">شرکت‌های منتخب</option>
                                    <option value="all">همه شرکت‌ها</option>
                                    <option value="none">حذف دسترسی ویژه</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    ثبت / بروزرسانی
                                </button>
                            </div>
                        </form>

                        <form method="post" id="scopeCompanyForm" class="row gx-3 gy-2 align-items-end mb-3">
                            <input type="hidden" name="action" value="add_ticket_scope_company">
                            <input type="hidden" name="scope_user_code" id="scopeCompanyUserCode" value="">
                            <div class="col-md-4">
                                <label class="form-label">کاربر</label>
                                <div class="input-group">
                                    <input type="text" id="scopeCompanyUserLabel" class="form-control" value="" placeholder="کاربر انتخاب نشده" readonly required>
                                    <button type="button" class="btn btn-outline-secondary open-scope-user-modal" data-target-code="#scopeCompanyUserCode" data-target-label="#scopeCompanyUserLabel">
                                        انتخاب کاربر
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">شرکت‌ها</label>
                                <div class="input-group">
                                    <input type="text" id="scopeCompanySelectionSummary" class="form-control" value="" placeholder="هیچ شرکتی انتخاب نشده" readonly>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#scopeCompanySelectModal">
                                        انتخاب شرکت‌ها
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    افزودن شرکت‌های انتخاب شده
                                </button>
                            </div>
                        </form>

                        <div class="modal fade" id="scopeUserSelectModal" tabindex="-1" aria-labelledby="scopeUserSelectModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="scopeUserSelectModalLabel">
                                            <i class="bi bi-person-plus me-2"></i>انتخاب کاربر
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control border-start-0" id="scopeUserFilterInput" placeholder="جستجو کاربر..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div id="scopeUserTableContainer" style="max-height: 360px; overflow-y: auto;">
                                            <table class="table table-hover m-0">
                                                <tbody>
                                                    <?php foreach ($dept_users as $user): ?>
                                                        <tr class="scope-user-row" data-name="<?php echo strtolower($user['name']); ?>">
                                                            <td>
                                                                <div><?php echo htmlspecialchars($user['name']); ?></div>
                                                                <small class="text-muted"><?php echo htmlspecialchars($user['code_p']); ?></small>
                                                            </td>
                                                            <td class="text-end">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-success choose-scope-user"
                                                                    data-user-code="<?php echo htmlspecialchars($user['code_p']); ?>"
                                                                    data-user-label="<?php echo htmlspecialchars($user['name'] . ' (' . $user['code_p'] . ')'); ?>">
                                                                    انتخاب
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center text-muted py-4" id="scopeUserNoResults" style="display: none;">
                                            <i class="bi bi-search fs-1 d-block mb-2" style="opacity: 0.5;"></i>
                                            <p class="mb-0">هیچ کاربری یافت نشد</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="scopeCompanySelectModal" tabindex="-1" aria-labelledby="scopeCompanySelectModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="scopeCompanySelectModalLabel">
                                            <i class="bi bi-building me-2"></i>انتخاب شرکت‌ها
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>شرکت‌های قابل دسترسی</strong>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="scopeCompaniesSelectAll">انتخاب همه</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" id="scopeCompaniesClearAll">لغو همه</button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control border-start-0" id="scopeCompanyFilterInput" placeholder="جستجو شرکت..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div id="scopeCompanyListContainer" style="max-height: 360px; overflow-y: auto;">
                                            <?php foreach ($dept_companys as $company): ?>
                                                <label class="d-flex align-items-center justify-content-between border rounded px-2 py-2 mb-2 scope-company-row"
                                                    data-name="<?php echo strtolower($company['name']); ?>">
                                                    <span>
                                                        <?php echo htmlspecialchars($company['name']); ?>
                                                        <small class="text-muted">(<?php echo htmlspecialchars($company['code']); ?>)</small>
                                                    </span>
                                                    <input class="form-check-input scope-company-checkbox"
                                                        type="checkbox"
                                                        form="scopeCompanyForm"
                                                        name="scope_company_codes[]"
                                                        value="<?php echo htmlspecialchars($company['code']); ?>">
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="text-center text-muted py-4" id="scopeCompanyNoResults" style="display: none;">
                                            <i class="bi bi-search fs-1 d-block mb-2" style="opacity: 0.5;"></i>
                                            <p class="mb-0">هیچ شرکتی یافت نشد</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">تایید انتخاب</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var activeUserCodeTargetSelector = '';
                                var activeUserLabelTargetSelector = '';

                                function updateScopeCompanySummary() {
                                    var checked = document.querySelectorAll('.scope-company-checkbox:checked');
                                    var summaryEl = document.getElementById('scopeCompanySelectionSummary');
                                    if (!summaryEl) return;
                                    if (checked.length === 0) {
                                        summaryEl.value = '';
                                        summaryEl.placeholder = 'هیچ شرکتی انتخاب نشده';
                                        return;
                                    }
                                    summaryEl.value = checked.length + ' شرکت انتخاب شده';
                                }

                                function filterRows(inputEl, rowSelector, noResultsId, containerId) {
                                    var value = (inputEl.value || '').toLowerCase().trim();
                                    var rows = Array.from(document.querySelectorAll(rowSelector));
                                    var visible = 0;
                                    rows.forEach(function(row) {
                                        var name = (row.getAttribute('data-name') || '').toLowerCase();
                                        var isVisible = name.includes(value);
                                        row.style.display = isVisible ? '' : 'none';
                                        if (isVisible) visible++;
                                    });
                                    var noResults = document.getElementById(noResultsId);
                                    var container = document.getElementById(containerId);
                                    if (noResults && container) {
                                        if (visible === 0 && value !== '') {
                                            noResults.style.display = 'block';
                                            container.style.display = 'none';
                                        } else {
                                            noResults.style.display = 'none';
                                            container.style.display = 'block';
                                        }
                                    }
                                }

                                document.querySelectorAll('.open-scope-user-modal').forEach(function(btn) {
                                    btn.addEventListener('click', function() {
                                        activeUserCodeTargetSelector = btn.getAttribute('data-target-code') || '';
                                        activeUserLabelTargetSelector = btn.getAttribute('data-target-label') || '';
                                        var modalEl = document.getElementById('scopeUserSelectModal');
                                        if (modalEl && window.bootstrap && bootstrap.Modal) {
                                            bootstrap.Modal.getOrCreateInstance(modalEl).show();
                                        }
                                    });
                                });

                                document.querySelectorAll('.choose-scope-user').forEach(function(btn) {
                                    btn.addEventListener('click', function() {
                                        var code = btn.getAttribute('data-user-code') || '';
                                        var label = btn.getAttribute('data-user-label') || '';
                                        var codeInput = activeUserCodeTargetSelector ? document.querySelector(activeUserCodeTargetSelector) : null;
                                        var labelInput = activeUserLabelTargetSelector ? document.querySelector(activeUserLabelTargetSelector) : null;
                                        if (codeInput) codeInput.value = code;
                                        if (labelInput) labelInput.value = label;
                                        var modalEl = document.getElementById('scopeUserSelectModal');
                                        if (modalEl && window.bootstrap && bootstrap.Modal) {
                                            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                                        }
                                    });
                                });

                                var scopeUserFilterInput = document.getElementById('scopeUserFilterInput');
                                if (scopeUserFilterInput) {
                                    scopeUserFilterInput.addEventListener('input', function() {
                                        filterRows(scopeUserFilterInput, '.scope-user-row', 'scopeUserNoResults', 'scopeUserTableContainer');
                                    });
                                }

                                var scopeCompanyFilterInput = document.getElementById('scopeCompanyFilterInput');
                                if (scopeCompanyFilterInput) {
                                    scopeCompanyFilterInput.addEventListener('input', function() {
                                        filterRows(scopeCompanyFilterInput, '.scope-company-row', 'scopeCompanyNoResults', 'scopeCompanyListContainer');
                                    });
                                }

                                document.querySelectorAll('.scope-company-checkbox').forEach(function(checkbox) {
                                    checkbox.addEventListener('change', updateScopeCompanySummary);
                                });

                                var selectAllBtn = document.getElementById('scopeCompaniesSelectAll');
                                if (selectAllBtn) {
                                    selectAllBtn.addEventListener('click', function() {
                                        document.querySelectorAll('.scope-company-checkbox').forEach(function(checkbox) {
                                            if (checkbox.closest('.scope-company-row').style.display !== 'none') {
                                                checkbox.checked = true;
                                            }
                                        });
                                        updateScopeCompanySummary();
                                    });
                                }

                                var clearAllBtn = document.getElementById('scopeCompaniesClearAll');
                                if (clearAllBtn) {
                                    clearAllBtn.addEventListener('click', function() {
                                        document.querySelectorAll('.scope-company-checkbox').forEach(function(checkbox) {
                                            checkbox.checked = false;
                                        });
                                        updateScopeCompanySummary();
                                    });
                                }

                                var scopeModeForm = document.getElementById('scopeModeForm');
                                if (scopeModeForm) {
                                    scopeModeForm.addEventListener('submit', function(e) {
                                        var userCode = document.getElementById('scopeModeUserCode');
                                        if (!userCode || userCode.value.trim() === '') {
                                            e.preventDefault();
                                            alert('لطفا کاربر را انتخاب کنید.');
                                        }
                                    });
                                }

                                var scopeCompanyForm = document.getElementById('scopeCompanyForm');
                                if (scopeCompanyForm) {
                                    scopeCompanyForm.addEventListener('submit', function(e) {
                                        var userCode = document.getElementById('scopeCompanyUserCode');
                                        var checked = document.querySelectorAll('.scope-company-checkbox:checked');
                                        if (!userCode || userCode.value.trim() === '') {
                                            e.preventDefault();
                                            alert('لطفا کاربر را انتخاب کنید.');
                                            return;
                                        }
                                        if (checked.length === 0) {
                                            e.preventDefault();
                                            alert('لطفا حداقل یک شرکت انتخاب کنید.');
                                        }
                                    });
                                }

                                updateScopeCompanySummary();
                            });
                        </script>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>کاربر</th>
                                        <th>کد پرسنلی</th>
                                        <th>نوع دسترسی</th>
                                        <th>شرکت‌های مجاز</th>
                                        <th>حذف شرکت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($scope_assignments)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">هنوز دسترسی ویژه‌ای ثبت نشده است.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($scope_assignments as $idx => $scope): ?>
                                            <?php
                                            $company_codes = [];
                                            if (!empty($scope['company_codes'])) {
                                                $company_codes = explode(',', $scope['company_codes']);
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $idx + 1; ?></td>
                                                <td><?php echo htmlspecialchars($scope['user_name'] ?: 'نامشخص'); ?></td>
                                                <td><?php echo htmlspecialchars($scope['user_code']); ?></td>
                                                <td>
                                                    <?php if ($scope['can_view_all'] === 'y'): ?>
                                                        <span class="badge bg-success">همه شرکت‌ها</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">شرکت‌های منتخب</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($scope['company_names'] ?: '-'); ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($company_codes)): ?>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <?php foreach ($company_codes as $code): ?>
                                                                <form method="post" class="d-inline">
                                                                    <input type="hidden" name="action" value="remove_ticket_scope_company">
                                                                    <input type="hidden" name="scope_user_code" value="<?php echo htmlspecialchars($scope['user_code']); ?>">
                                                                    <input type="hidden" name="scope_company_code" value="<?php echo htmlspecialchars($code); ?>">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف <?php echo htmlspecialchars($code); ?>">
                                                                        <?php echo htmlspecialchars($code); ?>
                                                                    </button>
                                                                </form>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- ============================ /TAB: دسترسی تیکت‌ها ============================ -->

            <!-- ============================ TAB: اتصال کاربران ============================ -->
            <div class="tab-pane fade" id="tab-connect" role="tabpanel" tabindex="0">
                <?php
                $connection_departments = $departments;
                include __DIR__ . '/partials/dept_user_connections.php';
                ?>
            </div>
            <!-- ============================ /TAB: اتصال کاربران ============================ -->

        </div>
    </div>

<?php } elseif ($is_department_manager) { ?>

    <div class="settings-wrap">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h4 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-people"></i>اتصال کاربران به دپارتمان‌های شما
            </h4>
        </div>
        <p class="text-muted mb-3" style="font-size: .9rem;">
            <i class="bi bi-info-circle me-1"></i>
            شما به عنوان کاربر پیش‌فرض دپارتمان‌های زیر می‌توانید کاربران مرتبط را به آن‌ها متصل کنید.
        </p>
        <?php
        $connection_departments = $managed_departments;
        include __DIR__ . '/partials/dept_user_connections.php';
        ?>
    </div>

<?php } else { ?>


    <div class="alert bg-danger alert-dismissible fade show" role="alert">شما درسترسی لازم برای مشاهده این بخش را ندارید <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>

<?php } ?>
