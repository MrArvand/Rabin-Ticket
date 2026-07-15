<?php
/**
 * Script to assign all unassigned "ثبت اولیه" tickets to their department's default user
 * 
 * This script:
 * 1. Finds all tickets with status 'a' (ثبت اولیه) that have no assigned responder
 * 2. For each ticket, gets the department's default user
 * 3. Assigns the ticket to the default user and changes status to 'm' (در حال بررسی)
 */

// Include database connection
include('../../inf/f1.php');

// Check if user is logged in and has permission
if (!isset($_SESSION['ok_login_user_i']) || $_SESSION['ok_login_user_i'] != 'y') {
    die("Access denied. Please login first.");
}

// Only allow specific users to run this script (optional security)
// Uncomment and modify as needed:
// $allowed_users = ["24277", "25662", "1100056"];
// if (!in_array($_SESSION['code_p'], $allowed_users)) {
//     die("Access denied. You don't have permission to run this script.");
// }

$results = [
    'total_found' => 0,
    'assigned' => 0,
    'skipped_no_dept' => 0,
    'skipped_no_default' => 0,
    'errors' => 0,
    'details' => []
];

// Find all unassigned "ثبت اولیه" tickets
$query = "SELECT code, titr, daste, name_daste, code_p_karbar, name_karbar, log_txt, tarikh_sabt, saat_sabt
          FROM ticket 
          WHERE vaziat = 'a' 
          AND (code_p_karbar_anjam IS NULL OR code_p_karbar_anjam = '' OR code_p_karbar_anjam = '0')
          ORDER BY i_ticket ASC";

if ($result = mysqli_query($Link, $query)) {
    $results['total_found'] = mysqli_num_rows($result);
    
    while ($ticket = mysqli_fetch_array($result)) {
        $ticket_code = $ticket['code'];
        $ticket_code_escaped = mysqli_real_escape_string($Link, $ticket_code);
        $daste = $ticket['daste'];
        
        // Skip if no department assigned
        if (empty($daste)) {
            $results['skipped_no_dept']++;
            $results['details'][] = [
                'ticket' => $ticket_code,
                'status' => 'skipped',
                'reason' => 'No department assigned'
            ];
            continue;
        }
        
        // Get department's default user
        $daste_escaped = mysqli_real_escape_string($Link, $daste);
        $query_dept = "SELECT default_user_code, default_user_name, name as dept_name 
                       FROM departman 
                       WHERE id = '$daste_escaped' AND vaziat = 'y' 
                       LIMIT 1";
        
        $default_user_code = '';
        $default_user_name = '';
        $dept_name = '';
        
        if ($result_dept = mysqli_query($Link, $query_dept)) {
            if ($row_dept = mysqli_fetch_array($result_dept)) {
                $default_user_code = $row_dept['default_user_code'];
                $default_user_name = $row_dept['default_user_name'];
                $dept_name = $row_dept['dept_name'];
            }
        }
        
        // Skip if department has no default user
        if (empty($default_user_code) || empty($default_user_name)) {
            $results['skipped_no_default']++;
            $results['details'][] = [
                'ticket' => $ticket_code,
                'status' => 'skipped',
                'reason' => 'Department "' . ($dept_name ?: $daste) . '" has no default user'
            ];
            continue;
        }
        
        // Prepare update query
        $default_user_code_escaped = mysqli_real_escape_string($Link, $default_user_code);
        $default_user_name_escaped = mysqli_real_escape_string($Link, $default_user_name);
        
        // Update log text
        $log_txt = $ticket['log_txt'];
        $log_txt .= " اختصاص خودکار به کاربر پیش‌فرض دپارتمان (" . $default_user_name . ") در تاریخ $tarikh - $saat <br>";
        $log_txt_escaped = mysqli_real_escape_string($Link, $log_txt);
        
        // Update ticket: assign to default user and change status to 'm' (در حال بررسی)
        $update_query = "UPDATE ticket SET 
                        vaziat = 'm',
                        code_p_karbar_anjam = '$default_user_code_escaped',
                        name_karbar_anjam = '$default_user_name_escaped',
                        log_txt = '$log_txt_escaped'
                        WHERE code = '$ticket_code_escaped'";
        
        if (mysqli_query($Link, $update_query)) {
            $results['assigned']++;
            $results['details'][] = [
                'ticket' => $ticket_code,
                'status' => 'assigned',
                'to' => $default_user_name,
                'department' => $dept_name ?: $daste
            ];
        } else {
            $results['errors']++;
            $results['details'][] = [
                'ticket' => $ticket_code,
                'status' => 'error',
                'message' => mysqli_error($Link)
            ];
        }
    }
} else {
    $results['errors']++;
    $results['details'][] = [
        'status' => 'error',
        'message' => 'Query failed: ' . mysqli_error($Link)
    ];
}

// Display results
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختصاص خودکار تیکت‌ها</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-arrow-repeat me-2"></i>
                            نتایج اختصاص خودکار تیکت‌ها
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h3><?php echo $results['total_found']; ?></h3>
                                        <p class="mb-0">کل تیکت‌های یافت شده</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3><?php echo $results['assigned']; ?></h3>
                                        <p class="mb-0">اختصاص داده شده</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h3><?php echo $results['skipped_no_default']; ?></h3>
                                        <p class="mb-0">بدون کاربر پیش‌فرض</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h3><?php echo $results['errors']; ?></h3>
                                        <p class="mb-0">خطا</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($results['details'])): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>شماره تیکت</th>
                                        <th>وضعیت</th>
                                        <th>جزئیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results['details'] as $detail): ?>
                                    <tr>
                                        <td><code><?php echo htmlspecialchars($detail['ticket'] ?? 'N/A'); ?></code></td>
                                        <td>
                                            <?php if (($detail['status'] ?? '') === 'assigned'): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>اختصاص داده شد
                                                </span>
                                            <?php elseif (($detail['status'] ?? '') === 'skipped'): ?>
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-skip-forward me-1"></i>رد شد
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>خطا
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (($detail['status'] ?? '') === 'assigned'): ?>
                                                به <strong><?php echo htmlspecialchars($detail['to'] ?? ''); ?></strong>
                                                (دپارتمان: <?php echo htmlspecialchars($detail['department'] ?? ''); ?>)
                                            <?php else: ?>
                                                <?php echo htmlspecialchars($detail['reason'] ?? $detail['message'] ?? ''); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>

                        <div class="mt-4 text-center">
                            <a href="?page=list_ticket" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i>بازگشت به لیست تیکت‌ها
                            </a>
                            <a href="?page=setting" class="btn btn-outline-secondary">
                                <i class="bi bi-gear me-1"></i>تنظیمات
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
