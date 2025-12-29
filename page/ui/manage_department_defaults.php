<?php
// Admin-only page: Manage Department Default Users
// Only accessible to user code "1002"

$user_code = $_SESSION['code_p'] ?? null;

// Check if user has access (only user 1002)
if ($user_code !== "1100056") {
    include('restricted.php');
    exit;
}

// Handle form submission
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update') {
        $dept_id = mysqli_real_escape_string($Link, $_POST['dept_id'] ?? '');
        $user_code = mysqli_real_escape_string($Link, $_POST['user_code'] ?? '');
        $user_name = mysqli_real_escape_string($Link, $_POST['user_name'] ?? '');

        if (!empty($dept_id)) {
            if (!empty($user_code) && !empty($user_name)) {
                // Update default user
                $update_query = "UPDATE departman SET 
                                default_user_code = '$user_code',
                                default_user_name = '$user_name'
                                WHERE id = '$dept_id'";

                if (mysqli_query($Link, $update_query)) {
                    $message = 'کاربر پیش‌فرض با موفقیت ثبت شد';
                    $message_type = 'success';
                } else {
                    $message = 'خطا در ثبت اطلاعات: ' . mysqli_error($Link);
                    $message_type = 'danger';
                }
            } else {
                // Clear default user
                $update_query = "UPDATE departman SET 
                                default_user_code = '',
                                default_user_name = ''
                                WHERE id = '$dept_id'";

                if (mysqli_query($Link, $update_query)) {
                    $message = 'کاربر پیش‌فرض حذف شد';
                    $message_type = 'success';
                } else {
                    $message = 'خطا در حذف: ' . mysqli_error($Link);
                    $message_type = 'danger';
                }
            }
        }
    }
}

// Get all departments
$departments = [];
$query_depts = "SELECT id, name, default_user_code, default_user_name, vaziat 
                FROM departman 
                WHERE vaziat = 'y' 
                ORDER BY name ASC";
if ($result_depts = mysqli_query($Link, $query_depts)) {
    while ($row = mysqli_fetch_array($result_depts)) {
        $departments[] = $row;
    }
}

// Get all active users for dropdown
$users = [];
$query_users = "SELECT code_p, name, semat 
                FROM karbar 
                WHERE vaziat = 'y' 
                ORDER BY name ASC";
if ($result_users = mysqli_query($Link, $query_users)) {
    while ($row = mysqli_fetch_array($result_users)) {
        $users[] = $row;
    }
}
?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-building me-2"></i>مدیریت کاربر پیش‌فرض دپارتمان‌ها
        </h5>
        <p class="text-muted mb-0 mt-2" style="font-size: 0.85rem;">
            <i class="bi bi-info-circle me-1"></i>
            در این بخش می‌توانید برای هر دپارتمان یک کاربر پیش‌فرض تعیین کنید.
            تیکت‌های جدید در هر دپارتمان به صورت خودکار به کاربر پیش‌فرض آن دپارتمان اختصاص می‌یابند.
        </p>
    </div>
    <div class="card-body">
        <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <i
                class="bi bi-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">نام دپارتمان</th>
                        <th style="width: 25%;">کاربر پیش‌فرض فعلی</th>
                        <th style="width: 35%;">تغییر کاربر پیش‌فرض</th>
                        <th style="width: 5%;">عملیات</th>
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
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($dept['name']); ?></strong>
                            <br>
                            <small class="text-muted">کد: <?php echo htmlspecialchars($dept['id']); ?></small>
                        </td>
                        <td>
                            <?php if (!empty($dept['default_user_code'])): ?>
                            <span class="badge bg-primary">
                                <i class="bi bi-person-fill me-1"></i>
                                <?php echo htmlspecialchars($dept['default_user_name']); ?>
                            </span>
                            <br>
                            <small class="text-muted">کد:
                                <?php echo htmlspecialchars($dept['default_user_code']); ?></small>
                            <?php else: ?>
                            <span class="text-muted">
                                <i class="bi bi-dash-circle me-1"></i>
                                تعیین نشده
                            </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="" class="d-flex gap-2 align-items-end">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="dept_id"
                                    value="<?php echo htmlspecialchars($dept['id']); ?>">

                                <div class="flex-grow-1">
                                    <select name="user_code" class="form-select form-select-sm"
                                        id="user_select_<?php echo $dept['id']; ?>"
                                        onchange="updateUserName('<?php echo $dept['id']; ?>', this.value)">
                                        <option value="">-- بدون کاربر پیش‌فرض --</option>
                                        <?php foreach ($users as $user): ?>
                                        <option value="<?php echo htmlspecialchars($user['code_p']); ?>"
                                            data-name="<?php echo htmlspecialchars($user['name']); ?>"
                                            <?php echo ($dept['default_user_code'] === $user['code_p']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['name']); ?>
                                            (<?php echo htmlspecialchars($user['code_p']); ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="user_name" id="user_name_<?php echo $dept['id']; ?>"
                                        value="<?php echo htmlspecialchars($dept['default_user_name']); ?>">
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>ذخیره
                                </button>
                            </form>
                        </td>
                        <td>
                            <?php if (!empty($dept['default_user_code'])): ?>
                            <form method="POST" action=""
                                onsubmit="return confirm('آیا از حذف کاربر پیش‌فرض اطمینان دارید؟');">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="dept_id"
                                    value="<?php echo htmlspecialchars($dept['id']); ?>">
                                <input type="hidden" name="user_code" value="">
                                <input type="hidden" name="user_name" value="">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف کاربر پیش‌فرض">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
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

<script>
function updateUserName(deptId, userCode) {
    const select = document.getElementById('user_select_' + deptId);
    const nameInput = document.getElementById('user_name_' + deptId);

    if (userCode && select.selectedIndex > 0) {
        const selectedOption = select.options[select.selectedIndex];
        const userName = selectedOption.getAttribute('data-name');
        nameInput.value = userName || '';
    } else {
        nameInput.value = '';
    }
}
</script>