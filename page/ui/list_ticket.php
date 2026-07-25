<?php
require_once(__DIR__ . '/../../inf/ticket_tags.php');

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

$my_tickets_filter = '';
if (isset($_POST['my_tickets_filter']) && $_POST['my_tickets_filter'] !== '') {
    $my_tickets_filter = str_p('my_tickets_filter');
} elseif (isset($_GET['my_tickets_filter']) && $_GET['my_tickets_filter'] !== '') {
    $my_tickets_filter = str_g('my_tickets_filter');
}
if ($my_tickets_filter === '0' || $my_tickets_filter === 'all') $my_tickets_filter = '';

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

// Ensure bookmark table exists for per-user ticket bookmarks
mysqli_query(
    $Link,
    "CREATE TABLE IF NOT EXISTS ticket_bookmarks (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        code_p_karbar VARCHAR(64) NOT NULL,
        ticket_code VARCHAR(64) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_user_ticket (code_p_karbar, ticket_code),
        KEY idx_ticket_code (ticket_code)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
);

// Normalize collation on existing installations to prevent join comparison errors
mysqli_query(
    $Link,
    "ALTER TABLE ticket_bookmarks CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci"
);

// Handle bookmark toggle action
if (isset($_POST['bookmark_action']) && isset($_POST['bookmark_ticket_code'])) {
    $bookmark_action = str_p('bookmark_action');
    $bookmark_ticket_code = str_p('bookmark_ticket_code');

    if ($bookmark_ticket_code !== '') {
        $code_p_run_escaped_for_bookmark = mysqli_real_escape_string($Link, $code_p_run);
        $bookmark_ticket_code_escaped = mysqli_real_escape_string($Link, $bookmark_ticket_code);
        $legacy_global_admin_codes = ["24277", "25662", "1100105", "20612", "23056", "1100110", "20072", "1100056", "30613", "1064046037", "1100005", "23882"];

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

        $can_view_all_tickets = in_array($code_p_run, $legacy_global_admin_codes, true);
        $allowed_company_codes_for_user = [];

        if (!$can_view_all_tickets) {
            $permission_user_q = "SELECT can_view_all FROM ticket_view_scope_users WHERE user_code = '$code_p_run_escaped_for_bookmark' LIMIT 1";
            if ($permission_user_res = mysqli_query($Link, $permission_user_q)) {
                if ($permission_user_row = mysqli_fetch_assoc($permission_user_res)) {
                    if (($permission_user_row['can_view_all'] ?? 'n') === 'y') {
                        $can_view_all_tickets = true;
                    }
                }
            }

            if (!$can_view_all_tickets) {
                $permission_companies_q = "SELECT company_code FROM ticket_view_scope_companies WHERE user_code = '$code_p_run_escaped_for_bookmark'";
                if ($permission_companies_res = mysqli_query($Link, $permission_companies_q)) {
                    while ($permission_company_row = mysqli_fetch_assoc($permission_companies_res)) {
                        if (!empty($permission_company_row['company_code'])) {
                            $allowed_company_codes_for_user[] = mysqli_real_escape_string($Link, $permission_company_row['company_code']);
                        }
                    }
                }
            }
        }

        if ($can_view_all_tickets) {
            $access_condition = "i_ticket > 0";
        } elseif (!empty($allowed_company_codes_for_user)) {
            $allowed_companies_list = "'" . implode("','", $allowed_company_codes_for_user) . "'";
            // Company-scoped access must follow "receiver company" mapping from settings:
            // departman.default_company_code (not ticket.code_sherkat which is sender company).
            $access_condition = "(code_p_karbar = '$code_p_run_escaped_for_bookmark' OR code_p_karbar_anjam = '$code_p_run_escaped_for_bookmark' OR EXISTS (SELECT 1 FROM departman d_scope WHERE d_scope.id = daste AND d_scope.default_company_code IN ($allowed_companies_list))) AND i_ticket > 0";
        } else {
            $access_condition = "(code_p_karbar = '$code_p_run_escaped_for_bookmark' OR code_p_karbar_anjam = '$code_p_run_escaped_for_bookmark') AND i_ticket > 0";
        }

        $Query_check_access = "SELECT code FROM ticket WHERE code = '$bookmark_ticket_code_escaped' AND $access_condition LIMIT 1";
        if ($Result_check_access = mysqli_query($Link, $Query_check_access)) {
            if (mysqli_num_rows($Result_check_access) > 0) {
                if ($bookmark_action === 'add') {
                    mysqli_query(
                        $Link,
                        "INSERT INTO ticket_bookmarks (code_p_karbar, ticket_code) 
                         VALUES ('$code_p_run_escaped_for_bookmark', '$bookmark_ticket_code_escaped')
                         ON DUPLICATE KEY UPDATE created_at = created_at"
                    );
                } elseif ($bookmark_action === 'remove') {
                    mysqli_query(
                        $Link,
                        "DELETE FROM ticket_bookmarks 
                         WHERE code_p_karbar = '$code_p_run_escaped_for_bookmark' 
                         AND ticket_code = '$bookmark_ticket_code_escaped'"
                    );
                }
            }
        }
    }

    // Redirect to list page with current filters to prevent form re-submit on refresh
    $redirect_params = ['page' => 'list_ticket'];
    $persist_keys = ['kind', 'faal', 'daste', 'sherkat', 'tarikh_1', 'tarikh_2', 'sn_ticket', 'titr', 'karbar_ersal', 'karbar_paziresh', 'my_tickets_filter', 'per_page', 'page_number', 'selected_tags'];
    foreach ($persist_keys as $persist_key) {
        if (isset($_POST[$persist_key]) && $_POST[$persist_key] !== '') {
            $redirect_params[$persist_key] = $_POST[$persist_key];
        }
    }

    $redirect_url = '?' . http_build_query($redirect_params);
    if (!headers_sent()) {
        header('Location: ' . $redirect_url);
        exit;
    }

    echo '<script>window.location.href=' . json_encode($redirect_url) . ';</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8') . '"></noscript>';
    exit;
}
// ---- Load user tags for filter chips and assignment modal ----
$ticket_tags_available = ticket_tags_table_exists($Link) && ticket_tags_assignments_table_exists($Link);
$list_user_tags = $ticket_tags_available ? ticket_tag_load_user_tags($Link, $code_p_run) : [];

// ---- Tag filter parameter ----
$selected_tags_str = '';
if (isset($_POST['selected_tags']) && $_POST['selected_tags'] !== '') {
    $selected_tags_str = str_p('selected_tags');
} elseif (isset($_GET['selected_tags']) && $_GET['selected_tags'] !== '') {
    $selected_tags_str = str_g('selected_tags');
}
$selected_tags_arr = [];
if ($selected_tags_str !== '') {
    $parts = explode(',', $selected_tags_str);
    foreach ($parts as $part) {
        $id = (int) trim($part);
        if ($id > 0) {
            $selected_tags_arr[] = $id;
        }
    }
    $selected_tags_str = implode(',', $selected_tags_arr);
} else {
    $selected_tags_str = '';
}
?>
<link rel="stylesheet" href="assets/vendor/select2/select2.min.css" />
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

    .chip-button {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
        background: var(--color-bg-card, var(--bs-card-bg));
        color: var(--color-text-primary, var(--bs-body-color));
        text-decoration: none;
        font-family: 'IranYekanNum', sans-serif;
    }

    .chip-button:hover {
        background: var(--color-bg-hover, var(--bs-tertiary-bg));
        transform: translateY(-1px);
        box-shadow: 0 2px 8px var(--color-shadow-sm, rgba(0, 0, 0, 0.1));
        color: var(--color-text-primary, var(--bs-body-color));
        text-decoration: none;
    }

    .chip-button.active {
        background: var(--color-primary, var(--bs-primary));
        color: var(--color-white, #ffffff);
        border-color: var(--color-primary, var(--bs-primary));
        box-shadow: 0 2px 8px var(--color-shadow-md, rgba(13, 110, 253, 0.2));
    }

    .chip-button.active:hover {
        background: var(--color-primary-dark, var(--bs-primary));
        color: var(--color-white, #ffffff);
        box-shadow: 0 4px 12px var(--color-shadow-lg, rgba(13, 110, 253, 0.3));
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        min-height: calc(1.5em + 0.75rem + 2px);
        background-color: var(--color-bg-surface, #353c48);
        border: var(--bs-border-width) solid var(--color-border-primary, #50596a);
        border-radius: 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--color-text-primary, #95a0b1);
        line-height: calc(1.5em + 0.75rem);
        padding-right: 0.75rem;
        padding-left: 2rem;
        font-size: 0.9rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: var(--color-text-secondary, #6a7384);
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem);
        top: 1px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: var(--color-text-primary, #95a0b1) transparent transparent transparent;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent var(--color-text-primary, #95a0b1) transparent;
    }

    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--color-primary, #3c92b1);
        box-shadow: 0 0 0 var(--bs-focus-ring-width, 0.25rem) var(--color-badge-primary-bg, rgba(60, 146, 177, 0.25));
    }

    .select2-dropdown {
        z-index: 1056;
        background-color: var(--color-bg-surface, #353c48);
        border: var(--bs-border-width) solid var(--color-border-primary, #50596a);
        border-radius: var(--bs-border-radius, 0.375rem);
        box-shadow: 0 4px 12px var(--color-shadow-lg, rgba(0, 0, 0, 0.3));
    }

    .select2-container--default .select2-search--dropdown {
        padding: 0.5rem;
        background-color: var(--color-bg-surface, #353c48);
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: var(--color-bg-tertiary, #2c323d);
        border: var(--bs-border-width) solid var(--color-border-primary, #50596a);
        border-radius: var(--bs-border-radius-sm, 0.25rem);
        color: var(--color-text-primary, #95a0b1);
        padding: 0.375rem 0.75rem;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--color-primary, #3c92b1);
        box-shadow: 0 0 0 var(--bs-focus-ring-width, 0.25rem) var(--color-badge-primary-bg, rgba(60, 146, 177, 0.25));
        outline: 0;
    }

    .select2-container--default .select2-results__option {
        color: var(--color-text-primary, #95a0b1);
        background-color: var(--color-bg-surface, #353c48);
        padding: 0.5rem 0.75rem;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--color-bg-secondary, #353c48);
        color: var(--color-text-primary, #95a0b1);
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: rgba(var(--color-primary-rgb, 60, 146, 177), 0.22);
        color: var(--color-text-primary, #95a0b1);
    }

    .select2-container--default .select2-results__option[aria-disabled=true] {
        color: var(--color-text-disabled, #495160);
    }

    .select2-container--default .select2-results>.select2-results__options {
        max-height: 240px;
        background-color: var(--color-bg-surface, #353c48);
    }

    .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: var(--color-bg-hover, rgba(255, 255, 255, 0.05));
        opacity: 0.75;
    }

    /* Tag badges in ticket rows */
    .ticket-tag-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.6rem;
        font-weight: 600;
        line-height: 1.4;
        white-space: nowrap;
    }

    /* Row shading for tagged tickets - theme-aware */
    :root, html:not([data-theme]), html[data-theme="dark"], .theme-dark {
        --tt-shade-alpha: 0.14;
        --tt-shade-alpha-hover: 0.22;
    }

    [data-theme="light"], .theme-light {
        --tt-shade-alpha: 0.22;
        --tt-shade-alpha-hover: 0.32;
    }

    /* Use high-specificity selectors to override theme.css .table td backgrounds */
    .modern-table tbody tr.has-ticket-tag-shade {
        background-color: rgba(var(--tt-row-shade-rgb, 99, 102, 241), var(--tt-shade-alpha, 0.14)) !important;
        transition: background-color 0.2s ease;
    }

    .modern-table tbody tr.has-ticket-tag-shade td {
        background-color: transparent !important;
    }

    .modern-table tbody tr.has-ticket-tag-shade:hover,
    .modern-table tbody tr.has-ticket-tag-shade:hover td {
        background-color: rgba(var(--tt-row-shade-rgb, 99, 102, 241), var(--tt-shade-alpha-hover, 0.22)) !important;
    }

    /* Tag management dropdown/modal */
    .list-tag-btn {
        padding: 2px 6px;
        font-size: 0.7rem;
        border-radius: 6px;
        border: 1px solid var(--bs-border-color);
        background: transparent;
        color: var(--bs-body-color);
        cursor: pointer;
        transition: all 0.15s ease;
        line-height: 1.3;
    }

    .list-tag-btn:hover {
        background: var(--bs-tertiary-bg);
        border-color: var(--color-primary, var(--bs-primary));
        color: var(--color-primary, var(--bs-primary));
    }

    .list-tag-btn.has-tags {
        color: var(--color-primary, var(--bs-primary));
        border-color: var(--color-primary, var(--bs-primary));
        background: var(--color-badge-primary-bg, rgba(13, 110, 253, 0.1));
    }

    /* Reuse tag modal styles from info_ticket */
    .tt-modal-tag-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
        margin-bottom: 8px;
        background: var(--color-bg-secondary, var(--bs-secondary-bg));
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }

    .tt-modal-tag-item:hover {
        border-color: var(--color-primary, var(--bs-primary));
    }

    .tt-modal-tag-item.active {
        border-color: var(--color-primary, var(--bs-primary));
        background: var(--color-badge-primary-bg, rgba(13, 110, 253, 0.08));
    }

    .tt-modal-tag-color {
        width: 18px;
        height: 18px;
        border-radius: 6px;
        flex-shrink: 0;
        border: 2px solid rgba(0, 0, 0, 0.08);
    }

    .tt-modal-tag-title {
        flex: 1;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--bs-body-color);
    }

    .tt-modal-tag-check {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid var(--color-border-primary, var(--bs-border-color));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.15s ease;
    }

    .tt-modal-tag-item.active .tt-modal-tag-check {
        background: var(--color-primary, var(--bs-primary));
        border-color: var(--color-primary, var(--bs-primary));
        color: #fff;
    }

    .tt-modal-tag-item.active .tt-modal-tag-check i {
        font-size: 0.7rem;
    }

    .tt-modal-empty {
        text-align: center;
        padding: 30px 20px;
        color: var(--bs-secondary-color);
    }

    .tt-modal-empty i {
        font-size: 2.5rem;
        opacity: 0.3;
        margin-bottom: 12px;
    }

    .tt-modal-empty p {
        margin: 0;
        font-size: 0.9rem;
    }

    .tt-filter-tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid;
        background: transparent;
        color: var(--color-text-primary, #95a0b1);
        user-select: none;
        font-family: 'IranYekanNum', sans-serif;
        line-height: 1.4;
    }

    .tt-filter-tag-chip:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px var(--color-shadow-sm, rgba(0, 0, 0, 0.15));
    }

    .tt-filter-tag-chip.active {
        color: #fff;
    }

    .tt-filter-tag-chip.active i.bi-x {
        font-size: 0.65rem;
        margin-right: -2px;
    }

    .tt-filter-tag-chip i.bi-tag-fill {
        font-size: 0.65rem;
    }

    .tt-filter-tag-clear {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        color: var(--color-text-secondary, #6a7384);
        border-radius: 20px;
        transition: all 0.15s ease;
        border: 1px solid transparent;
        user-select: none;
    }

    .tt-filter-tag-clear:hover {
        color: var(--color-danger, #bf7a6a);
        background: var(--color-badge-danger-bg, rgba(191, 122, 106, 0.1));
        border-color: var(--color-danger, #bf7a6a);
    }

    .tt-filter-tag-clear i {
        font-size: 0.6rem;
    }
    /* Tag filter collapsible */
    .tt-filter-collapse {
        position: relative;
    }

    .tt-filter-collapse.collapsed .tt-chip-folded {
        display: none;
    }

    .tt-filter-collapse.collapsed .tt-filter-toggle-btn i.bi-chevron-down {
        transform: rotate(0deg);
    }

    .tt-filter-collapse:not(.collapsed) .tt-filter-toggle-btn i.bi-chevron-down {
        transform: rotate(180deg);
    }

    .tt-filter-toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border: 1px solid var(--color-border-primary, #50596a);
        border-radius: 20px;
        background: transparent;
        color: var(--color-primary, #3c92b1);
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: 'IranYekanNum', sans-serif;
        white-space: nowrap;
    }

    .tt-filter-toggle-btn:hover {
        background: var(--color-badge-primary-bg, rgba(60, 146, 177, 0.1));
        border-color: var(--color-primary, #3c92b1);
    }

    .tt-filter-toggle-btn i {
        transition: transform 0.25s ease;
        font-size: 0.6rem;
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
                                                <?php echo $q_dep['name']; ?>
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
                    <input type="hidden" name="my_tickets_filter" id="my_tickets_filter" value="<?php echo htmlspecialchars($my_tickets_filter, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="selected_tags" id="selected_tags" value="<?php echo htmlspecialchars($selected_tags_str, ENT_QUOTES, 'UTF-8'); ?>">

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

        window.setMyTicketsFilter = function(filterValue) {
            if (!filterForm) {
                return;
            }
            var filterField = document.getElementById('my_tickets_filter');
            if (filterField) {
                // Toggle: if clicking the same filter, clear it
                if (filterField.value === filterValue) {
                    filterField.value = '';
                } else {
                    filterField.value = filterValue;
                }
                // Reset to page 1 when changing filter
                if (pageField) {
                    pageField.value = '1';
                }
                filterForm.submit();
            }
        };

        window.toggleTicketBookmark = function(ticketCode, shouldBookmark) {
            if (!filterForm || !ticketCode) {
                return;
            }

            var tempForm = document.createElement('form');
            tempForm.method = 'post';
            tempForm.action = filterForm.action || '?page=list_ticket';

            var currentData = new FormData(filterForm);
            currentData.forEach(function(value, key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                tempForm.appendChild(input);
            });

            var actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'bookmark_action';
            actionInput.value = shouldBookmark ? 'add' : 'remove';
            tempForm.appendChild(actionInput);

            var ticketInput = document.createElement('input');
            ticketInput.type = 'hidden';
            ticketInput.name = 'bookmark_ticket_code';
            ticketInput.value = ticketCode;
            tempForm.appendChild(ticketInput);

            document.body.appendChild(tempForm);
            tempForm.submit();
        };
    })();
</script>

<div class="row gx-3">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header" style="flex-wrap: wrap; gap: 8px;">
                <h5 class="modern-card-title">
                    <i class="bi bi-list-task text-primary"></i>
                    لیست تیکت‌ها
                </h5>
                <div class="d-flex flex-column align-items-end gap-2">
                    <div class="d-flex gap-2">
                        <a href="#"
                            class="chip-button <?php echo ($my_tickets_filter === 'sent') ? 'active' : ''; ?>"
                            onclick="event.preventDefault(); setMyTicketsFilter('sent');">
                            <i class="bi bi-send me-1"></i>
                            تیکت‌های ارسالی من
                        </a>
                        <a href="#"
                            class="chip-button <?php echo ($my_tickets_filter === 'received') ? 'active' : ''; ?>"
                            onclick="event.preventDefault(); setMyTicketsFilter('received');">
                            <i class="bi bi-inbox me-1"></i>
                            تیکت‌های دریافتی من
                        </a>
                        <a href="#"
                            class="chip-button <?php echo ($my_tickets_filter === 'bookmarked') ? 'active' : ''; ?>"
                            onclick="event.preventDefault(); setMyTicketsFilter('bookmarked');">
                            <i class="bi bi-bookmark-fill me-1"></i>
                            تیکت‌های نشان‌شده
                        </a>
                        <a href="?page=manage_tags" class="chip-button">
                            <i class="bi bi-tags me-1"></i>
                            برچسب‌ها
                        </a>
                    </div>
                    <?php if ($ticket_tags_available && !empty($list_user_tags)): ?>
                        <?php 
                        $tt_total = count($list_user_tags);
                        $tt_threshold = 6;
                        $tt_has_many = $tt_total > $tt_threshold;
                        $tt_active_count = count($selected_tags_arr);
                        $tt_should_expand = $tt_active_count > 0;
                        ?>
                        <div class="tt-filter-collapse <?php echo ($tt_has_many && !$tt_should_expand) ? 'collapsed' : ''; ?>" id="tagFilterChipsWrapper">
                            <div class="d-flex align-items-center flex-wrap gap-2" id="tagFilterChips">
                                <?php
                                $active_tag_ids = $selected_tags_arr;
                                foreach ($list_user_tags as $i => $tag):
                                    $is_active = in_array($tag['id'], $active_tag_ids);
                                    $is_hidden = ($tt_has_many && $i >= $tt_threshold && !$tt_should_expand && !$is_active);
                                ?>
                                    <span class="tt-filter-tag-chip <?php echo $is_active ? 'active' : ''; ?> <?php echo $is_hidden ? 'tt-chip-folded' : ''; ?>"
                                          data-tag-id="<?php echo $tag['id']; ?>"
                                          data-color="<?php echo htmlspecialchars($tag['color'], ENT_QUOTES, 'UTF-8'); ?>"
                                          onclick="toggleTagFilter(<?php echo $tag['id']; ?>)">
                                        <i class="bi bi-tag-fill" style="color: <?php echo $tag['color']; ?>"></i>
                                        <?php echo htmlspecialchars($tag['title'], ENT_QUOTES, 'UTF-8'); ?>
                                        <?php if ($is_active): ?>
                                            <i class="bi bi-x"></i>
                                        <?php endif; ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if (!empty($selected_tags_arr)): ?>
                                    <span class="tt-filter-tag-clear" onclick="clearTagFilter()" title="پاک کردن فیلتر برچسب">
                                        <i class="bi bi-x-circle"></i> پاک کردن همه
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if ($tt_has_many): ?>
                                <button type="button" class="tt-filter-toggle-btn" id="tagChipsToggleBtn" onclick="toggleTagChips()">
                                    <i class="bi bi-chevron-down"></i>
                                    <span id="tagChipsToggleLabel">نمایش <?php echo $tt_total - $tt_threshold; ?> برچسب دیگر</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
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
                            // Escape current user code once for reuse
                            $code_p_run_escaped = mysqli_real_escape_string($Link, $code_p_run);

                            // Resolve dynamic ticket view scope:
                            // - legacy hard-coded admins => full access
                            // - configured users can be full-access or company-scoped via setting page
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

                            $legacy_global_admin_codes = ["24277", "25662", "1100105", "20612", "23056", "1100110", "20072", "1100056", "30613", "1064046037", "1100005", "23882"];
                            $can_view_all_tickets = in_array($code_p_run, $legacy_global_admin_codes, true);
                            $allowed_company_codes_for_user = [];

                            if (!$can_view_all_tickets) {
                                $Query_scope_user = "SELECT can_view_all FROM ticket_view_scope_users WHERE user_code = '$code_p_run_escaped' LIMIT 1";
                                if ($Result_scope_user = mysqli_query($Link, $Query_scope_user)) {
                                    if ($q_scope_user = mysqli_fetch_assoc($Result_scope_user)) {
                                        if (($q_scope_user['can_view_all'] ?? 'n') === 'y') {
                                            $can_view_all_tickets = true;
                                        }
                                    }
                                }

                                if (!$can_view_all_tickets) {
                                    $Query_scope_companies = "SELECT company_code FROM ticket_view_scope_companies WHERE user_code = '$code_p_run_escaped'";
                                    if ($Result_scope_companies = mysqli_query($Link, $Query_scope_companies)) {
                                        while ($q_scope_company = mysqli_fetch_assoc($Result_scope_companies)) {
                                            if (!empty($q_scope_company['company_code'])) {
                                                $allowed_company_codes_for_user[] = mysqli_real_escape_string($Link, $q_scope_company['company_code']);
                                            }
                                        }
                                    }
                                }
                            }

                            $default_scope_condition = "ticket.code_p_karbar_anjam = '$code_p_run_escaped' AND ticket.i_ticket > 0";
                            $scope_condition_for_bookmarks = "(ticket.code_p_karbar = '$code_p_run_escaped' OR ticket.code_p_karbar_anjam = '$code_p_run_escaped') AND ticket.i_ticket > 0";
                            if ($can_view_all_tickets) {
                                $default_scope_condition = "ticket.i_ticket > 0";
                                $scope_condition_for_bookmarks = "ticket.i_ticket > 0";
                            } elseif (!empty($allowed_company_codes_for_user)) {
                                $allowed_companies_in = "'" . implode("','", $allowed_company_codes_for_user) . "'";
                                // Use department receiver-company mapping from settings page.
                                $default_scope_condition = "(ticket.code_p_karbar = '$code_p_run_escaped' OR ticket.code_p_karbar_anjam = '$code_p_run_escaped' OR EXISTS (SELECT 1 FROM departman d_scope WHERE d_scope.id = ticket.daste AND d_scope.default_company_code IN ($allowed_companies_in))) AND ticket.i_ticket > 0";
                                $scope_condition_for_bookmarks = $default_scope_condition;
                            }

                            // Build base condition based on my_tickets_filter
                            // When a filter is applied, it overrides the default user restriction
                            if ($my_tickets_filter === "sent") {
                                // Show tickets created by current user (sent by me)
                                $shart = " ticket.code_p_karbar = '$code_p_run_escaped' AND ticket.i_ticket > 0 ";
                            } elseif ($my_tickets_filter === "received") {
                                // Show tickets assigned to current user (received by me)
                                $shart = " ticket.code_p_karbar_anjam = '$code_p_run_escaped' AND ticket.i_ticket > 0 ";
                            } elseif ($my_tickets_filter === "bookmarked") {
                                // Show only bookmarked tickets for current user
                                $shart = " ticket.code COLLATE utf8mb4_general_ci IN (SELECT ticket_code COLLATE utf8mb4_general_ci FROM ticket_bookmarks WHERE code_p_karbar = '$code_p_run_escaped') AND ($scope_condition_for_bookmarks) ";
                            } else {
                                // Default view follows resolved scope (assigned-only / company-scoped / full-access)
                                $shart = $default_scope_condition;
                            }

                            if (!empty($faal) && $faal != "0" && $faal != "all") {
                                $faal_escaped = mysqli_real_escape_string($Link, $faal);
                                $shart = $shart . " AND ticket.vaziat = '$faal_escaped' ";
                            }
                            if ($daste != "0" and $daste != "all"  and $daste != "") {
                                $shart = $shart . " AND ticket.daste = '$daste' ";
                            }
                            if ($sherkat != "0" and $sherkat != "all"  and $sherkat != "") {
                                $shart = $shart . " AND ticket.code_sherkat = '$sherkat' ";
                            }
                            if ($tarikh_1 != "0" and $tarikh_1 != "all"  and $tarikh_1 != "") {
                                $shart = $shart . " AND ticket.tarikh_sabt > '$tarikh_1' ";
                            }
                            if ($tarikh_2 != "0" and $tarikh_2 != "all"  and $tarikh_2 != "") {
                                $shart = $shart . " AND ticket.tarikh_sabt < '$tarikh_2' ";
                            }
                            if ($sn_ticket != "0" and $sn_ticket != "all"  and $sn_ticket != "") {
                                $sn_ticket_escaped = mysqli_real_escape_string($Link, $sn_ticket);
                                $shart = $shart . " AND ticket.code like '%$sn_ticket_escaped%' ";
                            }
                            if ($titr != "0" and $titr != "all"  and $titr != "") {
                                $titr_escaped = mysqli_real_escape_string($Link, $titr);
                                $shart = $shart . " AND ticket.titr like '%$titr_escaped%' ";
                            }
                            if ($karbar_ersal != "0" and $karbar_ersal != "all"  and $karbar_ersal != "") {
                                $karbar_ersal_escaped = mysqli_real_escape_string($Link, $karbar_ersal);
                                $shart = $shart . " AND (ticket.name_karbar like '%$karbar_ersal_escaped%' OR ticket.code_p_karbar like '%$karbar_ersal_escaped%') ";
                            }
                            if ($karbar_paziresh != "0" and $karbar_paziresh != "all"  and $karbar_paziresh != "") {
                                $karbar_paziresh_escaped = mysqli_real_escape_string($Link, $karbar_paziresh);
                                $shart = $shart . " AND (ticket.name_karbar_anjam like '%$karbar_paziresh_escaped%' OR ticket.code_p_karbar_anjam like '%$karbar_paziresh_escaped%') ";
                            }

                            if ($kind != "0" and $kind != "all" and  $kind != "") {
                                $shart = $shart . " AND ticket.daste = '$kind' ";
                            }

                            // Tag filter
                            if (!empty($selected_tags_arr)) {
                                $tag_ids_safe = implode(',', array_map('intval', $selected_tags_arr));
                                $shart = $shart . " AND EXISTS (
                                    SELECT 1 FROM ticket_tag_assignments tta 
                                    INNER JOIN ticket_tags tt ON tt.id = tta.tag_id AND tt.owner_code_p = '$code_p_run_escaped'
                                    WHERE tta.ticket_code COLLATE utf8mb4_general_ci = ticket.code COLLATE utf8mb4_general_ci
                                    AND tta.tag_id IN ($tag_ids_safe)
                                ) ";
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
                            if (!empty($my_tickets_filter)) $list_state_params['my_tickets_filter'] = $my_tickets_filter;
                            if (!empty($selected_tags_str)) $list_state_params['selected_tags'] = $selected_tags_str;
                            if ($per_page !== 50) $list_state_params['per_page'] = $per_page_selected_value;
                            if ($page_number > 1) $list_state_params['page_number'] = $page_number;
                            if (!empty($kind)) $list_state_params['kind'] = $kind;
                            $list_state_query = !empty($list_state_params) ? '&' . http_build_query($list_state_params) : '';

                            // $code_p_run_escaped is already escaped at the beginning of query building
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
                                     CASE WHEN ticket_bookmarks.ticket_code IS NULL THEN 0 ELSE 1 END as is_bookmarked,
                                     COALESCE(pasokh_counts_assigned.new_answers_count, 0) as new_answers_assigned,
                                     COALESCE(pasokh_counts_creator.new_answers_count, 0) as new_answers_creator,
                                     CASE WHEN ticket.code_p_karbar = '$code_p_run_escaped' THEN COALESCE(pasokh_counts_creator.new_answers_count, 0) ELSE COALESCE(pasokh_counts_assigned.new_answers_count, 0) END as has_new_responses,
                                     COALESCE(NULLIF(ticket.last_activity, ''), latest_response.last_response_time, CONCAT(ticket.tarikh_sabt, ' ', ticket.saat_sabt)) as sort_time
                                     FROM ticket 
                                     LEFT JOIN departman ON ticket.daste = departman.id
                                     LEFT JOIN ticket_bookmarks ON ticket.code COLLATE utf8mb4_general_ci = ticket_bookmarks.ticket_code COLLATE utf8mb4_general_ci AND ticket_bookmarks.code_p_karbar = '$code_p_run_escaped'
                                     LEFT JOIN (
                                         SELECT code_ticket, COUNT(*) as new_answers_count
                                         FROM pasokh
                                         WHERE oksee = 'n'
                                         AND (
                                             (kind IN ('referral', 'dept_ref') AND code_karbar_sabt = '$code_p_run_escaped')
                                             OR (
                                                 (kind IS NULL OR kind = '' OR kind NOT IN ('referral', 'dept_ref'))
                                                 AND matn NOT LIKE '%$monhh_escaped%'
                                                 AND (code_karbar_sabt IS NULL OR code_karbar_sabt = '' OR code_karbar_sabt != '$code_p_run_escaped')
                                                 AND code_karbar2 = '$code_p_run_escaped'
                                             )
                                         )
                                         GROUP BY code_ticket
                                     ) pasokh_counts_assigned ON ticket.code = pasokh_counts_assigned.code_ticket
                                     LEFT JOIN (
                                         SELECT code_ticket, COUNT(*) as new_answers_count
                                         FROM pasokh
                                         WHERE oksee = 'n'
                                         AND (
                                             (kind IN ('referral', 'dept_ref') AND code_karbar_sabt = '$code_p_run_escaped')
                                             OR (
                                                 (kind IS NULL OR kind = '' OR kind NOT IN ('referral', 'dept_ref'))
                                                 AND matn NOT LIKE '%$monhh_escaped%'
                                                 AND (code_karbar_sabt IS NULL OR code_karbar_sabt = '' OR code_karbar_sabt != '$code_p_run_escaped')
                                                 AND code_karbar2 = '$code_p_run_escaped'
                                             )
                                         )
                                         GROUP BY code_ticket
                                     ) pasokh_counts_creator ON ticket.code = pasokh_counts_creator.code_ticket
                                     LEFT JOIN (
                                         SELECT code_ticket, MAX(CONCAT(tarikh_sabt, ' ', saat_sabt)) as last_response_time
                                         FROM pasokh
                                         GROUP BY code_ticket
                                     ) latest_response ON ticket.code = latest_response.code_ticket
                                     WHERE ($shart) 
                                     ORDER BY is_user_ticket DESC, 
                                              has_new_responses DESC, 
                                              sort_time DESC" . $limit_clause;

                            // First pass: fetch all results into an array and collect ticket codes for tag loading
                            $all_tickets = [];
                            $ticket_codes_for_tags = [];

                            if ($Result_list = mysqli_query($Link, $Query_list)) {
                                if (mysqli_num_rows($Result_list) > 0) {
                                    while ($row_list = mysqli_fetch_array($Result_list)) {
                                        $all_tickets[] = $row_list;
                                        $ticket_codes_for_tags[] = $row_list['code'];
                                    }

                                    // Batch-load tag assignments for all displayed tickets
                                    $tag_assignments = [];
                                    if ($ticket_tags_available && !empty($ticket_codes_for_tags)) {
                                        $tag_assignments = ticket_tag_load_assignments($Link, $code_p_run, $ticket_codes_for_tags);
                                    }

                                    // Second pass: display each row with tag data
                                    foreach ($all_tickets as $q_list) {
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

                                        // Check tag assignments for this ticket
                                        $ticket_code_for_tag = $q_list['code'];
                                        $ticket_assigned_tags = $tag_assignments[$ticket_code_for_tag] ?? [];
                                        $row_shade_style = ticket_tag_row_shade_style($ticket_assigned_tags);
                                        ?>
                                        <tr style="<?php echo $row_shade_style; ?>"
                                            class="<?php echo !empty($ticket_assigned_tags) ? 'has-ticket-tag-shade' : ''; ?>">
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
                                                <?php if (!empty($ticket_assigned_tags)): ?>
                                                    <div class="d-flex flex-wrap align-items-center gap-1 mt-1">
                                                        <?php foreach ($ticket_assigned_tags as $tt): ?>
                                                            <span class="ticket-tag-badge"
                                                                style="background: <?php echo ticket_tag_hex_to_rgba($tt['color'], 0.2); ?>; color: <?php echo $tt['color']; ?>; border: 1px solid <?php echo $tt['color']; ?>;">
                                                                <?php echo htmlspecialchars($tt['title'], ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
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
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if ($ticket_tags_available && !empty($list_user_tags)): ?>
                                                        <button type="button"
                                                            class="list-tag-btn <?php echo !empty($ticket_assigned_tags) ? 'has-tags' : ''; ?>"
                                                            onclick="openListTagModal('<?php echo htmlspecialchars($q_list['code'], ENT_QUOTES, 'UTF-8'); ?>')"
                                                            title="مدیریت برچسب‌ها">
                                                            <i class="bi bi-tag"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button"
                                                        class="btn btn-sm <?php echo ((int) $q_list['is_bookmarked'] === 1) ? 'btn-warning' : 'btn-outline-warning'; ?> rounded-pill px-2 py-1"
                                                        style="font-size: 0.75rem; font-family: 'IranYekanNum', sans-serif;"
                                                        onclick="toggleTicketBookmark('<?php echo htmlspecialchars($q_list['code'], ENT_QUOTES, 'UTF-8'); ?>', <?php echo ((int) $q_list['is_bookmarked'] === 1) ? 'false' : 'true'; ?>)">
                                                        <i class="bi <?php echo ((int) $q_list['is_bookmarked'] === 1) ? 'bi-bookmark-fill' : 'bi-bookmark'; ?>"></i>
                                                    </button>
                                                    <a href="?page=info_ticket&code=<?php echo $q_list['code']; ?><?php echo htmlspecialchars($list_state_query, ENT_QUOTES, 'UTF-8'); ?>"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1"
                                                        style="font-size: 0.75rem; font-family: 'IranYekanNum', sans-serif;">
                                                        مشاهده
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    } // End foreach
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

                <!-- Tag Management Modal (shared) -->
                <?php if ($ticket_tags_available && !empty($list_user_tags)): ?>
                    <?php
                    // Collect all assigned tag IDs into a map for quick lookup
                    $list_assigned_ids_map = [];
                    foreach ($tag_assignments as $tcode => $tlist) {
                        foreach ($tlist as $t) {
                            if (!isset($list_assigned_ids_map[$tcode])) {
                                $list_assigned_ids_map[$tcode] = [];
                            }
                            $list_assigned_ids_map[$tcode][] = $t['id'];
                        }
                    }
                    ?>
                    <div class="modal fade" id="listTagModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="bi bi-tags me-2"></i>مدیریت برچسب‌ها</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-muted" style="font-size:0.85rem;">
                                            برچسب‌های <strong id="listTagModalTicketCode"></strong>
                                        </span>
                                        <a href="?page=manage_tags" class="btn btn-sm btn-outline-primary rounded-pill px-3" target="_blank">
                                            <i class="bi bi-plus-lg me-1"></i> مدیریت برچسب‌ها
                                        </a>
                                    </div>
                                    <div id="listTagModalItems">
                                        <?php foreach ($list_user_tags as $tag): ?>
                                            <div class="tt-modal-tag-item"
                                                data-tag-id="<?php echo $tag['id']; ?>"
                                                data-color="<?php echo htmlspecialchars($tag['color'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-title="<?php echo htmlspecialchars($tag['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <div class="tt-modal-tag-color" style="background:<?php echo htmlspecialchars($tag['color'], ENT_QUOTES, 'UTF-8'); ?>;"></div>
                                                <span class="tt-modal-tag-title"><?php echo htmlspecialchars($tag['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                <div class="tt-modal-tag-check"></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

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

<script>
    (function() {
        var SEARCHABLE_SELECT_IDS = ['faal', 'daste', 'sherkat', 'per_page'];

        function initSearchableDropdown(selectEl) {
            if (!selectEl || typeof jQuery === 'undefined' || !jQuery.fn.select2) {
                return;
            }

            var $el = jQuery(selectEl);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }

            $el.select2({
                dir: 'rtl',
                width: '100%',
                minimumResultsForSearch: 0,
                language: {
                    noResults: function() {
                        return 'نتیجه‌ای یافت نشد';
                    },
                    searching: function() {
                        return 'در حال جستجو...';
                    }
                }
            });
        }

        function initAllSearchableDropdowns() {
            SEARCHABLE_SELECT_IDS.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) {
                    initSearchableDropdown(el);
                }
            });
        }

        function loadSelect2Assets(callback) {
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                callback();
                return;
            }

            var script = document.createElement('script');
            script.src = 'assets/vendor/select2/select2.min.js';
            script.onload = callback;
            document.head.appendChild(script);
        }

        window.addEventListener('load', function() {
            loadSelect2Assets(initAllSearchableDropdowns);
        });

        // ---- Tag filter collapsible toggle ----

        window.toggleTagChips = function() {
            var wrapper = document.getElementById('tagFilterChipsWrapper');
            var btn = document.getElementById('tagChipsToggleBtn');
            var label = document.getElementById('tagChipsToggleLabel');
            if (!wrapper || !btn || !label) return;
            var isCollapsed = wrapper.classList.contains('collapsed');
            if (isCollapsed) {
                wrapper.classList.remove('collapsed');
                label.textContent = 'کمتر دیدن';
                btn.querySelector('i').className = 'bi bi-chevron-up';
            } else {
                wrapper.classList.add('collapsed');
                label.textContent = 'نمایش ' + wrapper.querySelectorAll('.tt-chip-folded').length + ' برچسب دیگر';
                btn.querySelector('i').className = 'bi bi-chevron-down';
            }
        };

        // ---- Tag filter toggling - auto-submit form on tag selection ----
        window.toggleTagFilter = function(tagId) {
            var input = document.getElementById('selected_tags');
            if (!input) return;
            var current = input.value ? input.value.split(',').map(v => parseInt(v, 10)).filter(v => !isNaN(v)) : [];
            var idx = current.indexOf(tagId);
            if (idx > -1) {
                current.splice(idx, 1);
            } else {
                current.push(tagId);
            }
            input.value = current.join(',');
            var pageField = document.getElementById('page_number');
            if (pageField) pageField.value = '1';
            var form = document.getElementById('ticketFilterForm');
            if (form) form.submit();
        };

        window.clearTagFilter = function() {
            var input = document.getElementById('selected_tags');
            if (input) input.value = '';
            var pageField = document.getElementById('page_number');
            if (pageField) pageField.value = '1';
            var form = document.getElementById('ticketFilterForm');
            if (form) form.submit();
        };

        // ---- Tag management in list view ----
        var listTagAssignments = <?php echo json_encode($tag_assignments ?? []); ?>;

        window.openListTagModal = function(ticketCode) {
            var modalEl = document.getElementById('listTagModal');
            if (!modalEl) return;

            // Update ticket code display
            var codeEl = document.getElementById('listTagModalTicketCode');
            if (codeEl) codeEl.textContent = ticketCode;

            // Get current assigned tag IDs for this ticket
            var assignedIds = [];
            if (listTagAssignments[ticketCode]) {
                assignedIds = listTagAssignments[ticketCode].map(function(t) { return t.id; });
            }

            // Update each tag item
            var items = modalEl.querySelectorAll('#listTagModalItems .tt-modal-tag-item');
            items.forEach(function(item) {
                var tagId = parseInt(item.getAttribute('data-tag-id'));
                var isActive = assignedIds.indexOf(tagId) !== -1;
                item.classList.toggle('active', isActive);
                var checkEl = item.querySelector('.tt-modal-tag-check');
                if (checkEl) {
                    checkEl.innerHTML = isActive ? '<i class="bi bi-check text-white"></i>' : '';
                }
                // Store current ticket code for toggle
                item.setAttribute('data-ticket-code', ticketCode);
            });

            // Show modal
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        };

        // Tag toggle in list modal
        document.addEventListener('click', function(ev) {
            var item = ev.target.closest('#listTagModalItems .tt-modal-tag-item');
            if (!item) return;

            var tagId = item.getAttribute('data-tag-id');
            var ticketCode = item.getAttribute('data-ticket-code');
            if (!tagId || !ticketCode) return;

            // Optimistic UI update
            var wasActive = item.classList.contains('active');
            item.classList.toggle('active');
            var checkEl = item.querySelector('.tt-modal-tag-check');
            if (checkEl) {
                checkEl.innerHTML = wasActive ? '' : '<i class="bi bi-check text-white"></i>';
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '?page=toggle_tag_assignment', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Update local assignments cache
                            listTagAssignments[ticketCode] = response.assigned || [];
                            // Update the tag button in the row and badges
                            updateListRowTags(ticketCode, response.assigned || []);
                        } else {
                            // Revert
                            item.classList.toggle('active');
                            if (checkEl) {
                                checkEl.innerHTML = wasActive ? '<i class="bi bi-check text-white"></i>' : '';
                            }
                        }
                    } catch(e) {
                        item.classList.toggle('active');
                        if (checkEl) {
                            checkEl.innerHTML = wasActive ? '<i class="bi bi-check text-white"></i>' : '';
                        }
                    }
                } else {
                    // Revert
                    item.classList.toggle('active');
                    if (checkEl) {
                        checkEl.innerHTML = wasActive ? '<i class="bi bi-check text-white"></i>' : '';
                    }
                }
            };
            xhr.onerror = function() {
                item.classList.toggle('active');
                if (checkEl) {
                    checkEl.innerHTML = wasActive ? '<i class="bi bi-check text-white"></i>' : '';
                }
            };
            xhr.send('tag_id=' + encodeURIComponent(tagId) + '&ticket_code=' + encodeURIComponent(ticketCode));
        });

        function updateListRowTags(ticketCode, assignedTags) {
            // Find all rows in the table (look for the ticket code in the title link)
            var rows = document.querySelectorAll('.modern-table tbody tr');
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var link = row.querySelector('.ticket-title-link');
                if (!link) continue;
                var href = link.getAttribute('href');
                if (!href || href.indexOf('code=' + ticketCode) === -1) continue;

                // Update tag button
                var tagBtn = row.querySelector('.list-tag-btn');
                if (tagBtn) {
                    tagBtn.classList.toggle('has-tags', assignedTags.length > 0);
                }

                // Update row shade
                var shadeStyle = '';
                if (assignedTags.length > 0) {
                    var oldest = assignedTags[0];
                    // Use CSS alpha variables for theme-aware opacity - just set the RGB components
                    var rgb = hexToRgb(oldest.color);
                    shadeStyle = '--tt-row-shade-rgb:' + rgb;
                }
                row.setAttribute('style', shadeStyle);
                row.classList.toggle('has-ticket-tag-shade', assignedTags.length > 0);

                // Update tag badges
                var titleCell = row.querySelector('td:nth-child(4)');
                if (titleCell) {
                    // Remove old tag badge container
                    var oldBadges = titleCell.querySelector('.ticket-tag-badges-row');
                    if (oldBadges) oldBadges.remove();

                    if (assignedTags.length > 0) {
                        var badgesDiv = document.createElement('div');
                        badgesDiv.className = 'd-flex flex-wrap align-items-center gap-1 mt-1 ticket-tag-badges-row';
                        assignedTags.forEach(function(t) {
                            var span = document.createElement('span');
                            span.className = 'ticket-tag-badge';
                            span.style.background = hexToRgba(t.color, 0.2);
                            span.style.color = t.color;
                            span.style.border = '1px solid ' + t.color;
                            span.textContent = t.title;
                            badgesDiv.appendChild(span);
                        });
                        titleCell.appendChild(badgesDiv);
                    }
                }
                break;
            }
        }

        function hexToRgb(hex) {
            hex = hex.replace('#', '');
            if (hex.length === 3) hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
            var r = parseInt(hex.substring(0, 2), 16);
            var g = parseInt(hex.substring(2, 4), 16);
            var b = parseInt(hex.substring(4, 6), 16);
            return r + ',' + g + ',' + b;
        }
    })();
</script>