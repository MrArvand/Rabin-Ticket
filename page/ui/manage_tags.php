<?php
require_once(__DIR__ . '/../../inf/ticket_tags.php');

$current_user_code = $_SESSION['code_p'] ?? '';
$tags = ticket_tag_load_user_tags($Link, $current_user_code);
$tags_table_ok = ticket_tags_table_exists($Link);
$preset_colors = ticket_tag_preset_colors();
$default_color = '#e11d48';
$edit_tag = null;
$edit_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : 0;
if ($edit_id > 0 && $tags_table_ok) {
    $potential = ticket_tag_get_by_id($Link, $edit_id);
    if ($potential && $potential['owner_code_p'] === $current_user_code) {
        $edit_tag = $potential;
    }
}
?>
<style>
    .tt-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .tt-header {
        background: linear-gradient(135deg, var(--color-bg-tertiary, #2d3748) 0%, var(--color-bg-card, #1a202c) 100%);
        padding: 24px 32px;
        margin: 0 -4px 20px -4px;
        border-radius: 16px;
        box-shadow: 0 4px 20px var(--color-shadow-md, rgba(0, 0, 0, 0.2));
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        text-align: center;
    }

    .tt-header h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--color-text-primary, #e2e8f0);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tt-header p {
        color: var(--color-text-secondary, #a0aec0);
        font-size: 0.95rem;
        margin: 0;
    }

    .tt-card {
        background: var(--color-bg-card, var(--bs-card-bg, #fff));
        border: 1px solid var(--color-border-primary, var(--bs-border-color, #dee2e6));
        border-radius: 16px;
        box-shadow: 0 4px 24px var(--color-shadow-sm, rgba(0, 0, 0, 0.06));
        overflow: hidden;
        margin-bottom: 24px;
    }

    .tt-card-header {
        background: var(--color-bg-tertiary, var(--bs-tertiary-bg, #f8f9fa));
        padding: 16px 24px;
        border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color, #dee2e6));
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tt-card-header h5 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--bs-body-color);
    }

    .tt-card-body {
        padding: 24px;
    }

    .tt-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 12px;
    }

    .tt-tag-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: var(--color-bg-secondary, var(--bs-secondary-bg));
        border: 1px solid var(--color-border-primary, var(--bs-border-color));
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .tt-tag-item:hover {
        border-color: var(--color-primary, var(--bs-primary));
        box-shadow: 0 2px 8px var(--color-shadow-sm, rgba(0, 0, 0, 0.1));
    }

    .tt-tag-color-dot {
        width: 20px;
        height: 20px;
        border-radius: 6px;
        flex-shrink: 0;
        border: 2px solid rgba(0, 0, 0, 0.08);
    }

    .tt-tag-title {
        flex: 1;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--bs-body-color);
    }

    .tt-tag-actions {
        display: flex;
        gap: 4px;
        flex-shrink: 0;
    }

    .tt-tag-actions .btn {
        padding: 4px 10px;
        font-size: 0.75rem;
        border-radius: 8px;
    }

    .tt-color-picker {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tt-color-option {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 3px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .tt-color-option:hover {
        transform: scale(1.1);
    }

    .tt-color-option.selected {
        border-color: var(--bs-body-color);
        box-shadow: 0 0 0 2px var(--bs-body-bg), 0 0 0 4px var(--bs-body-color);
    }

    .tt-color-input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
    }

    .tt-color-input-wrapper input[type="color"] {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 10px;
        padding: 0;
        cursor: pointer;
        background: none;
    }

    .tt-color-hex {
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        color: var(--bs-secondary-color);
        direction: ltr;
    }

    .empty-tags {
        text-align: center;
        padding: 40px 20px;
        color: var(--bs-secondary-color);
    }

    .empty-tags i {
        font-size: 3rem;
        opacity: 0.3;
        margin-bottom: 16px;
    }

    .empty-tags p {
        margin: 0;
        font-size: 0.95rem;
    }
</style>

<div class="tt-container">
    <?php if (!$tags_table_ok): ?>
        <div class="alert alert-warning" role="alert" style="margin-bottom:14px;">
            ابتدا migration مربوط به برچسب‌ها را اجرا کنید:
            <code>migrations/021_migration_ticket_tags.sql</code>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['p']) && $_GET['p'] === 'y'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom:14px;">
            <i class="bi bi-check-circle me-1"></i> برچسب با موفقیت ذخیره شد.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['p']) && $_GET['p'] === 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom:14px;">
            <i class="bi bi-check-circle me-1"></i> برچسب حذف شد.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom:14px;">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <?php
            $error_msgs = [
                'invalid_name' => 'عنوان برچسب نامعتبر است.',
                'duplicate' => 'این عنوان برچسب قبلاً توسط شما ثبت شده است.',
                'save_failed' => 'خطا در ذخیره برچسب. لطفاً دوباره تلاش کنید.',
                'not_found' => 'برچسب مورد نظر یافت نشد.',
                'no_session' => 'نشست کاربری معتبر نیست.',
                'db' => 'خطای پایگاه داده.',
            ];
            echo $error_msgs[$_GET['error']] ?? 'خطا در انجام عملیات.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="tt-header">
        <h1>
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M5 2v20l7-4 7 4V2H5z"/>
            </svg>
            مدیریت برچسب‌های تیکت
        </h1>
        <p>برچسب‌های شخصی خود را برای تیکت‌ها ایجاد، ویرایش و مدیریت کنید</p>
    </div>

    <div class="row gx-3">
        <!-- Create/Edit form -->
        <div class="col-lg-5 mb-4">
            <div class="tt-card">
                <div class="tt-card-header">
                    <h5>
                        <i class="bi bi-tag-fill text-primary"></i>
                        <?php echo $edit_tag ? 'ویرایش برچسب' : 'ایجاد برچسب جدید'; ?>
                    </h5>
                    <?php if ($edit_tag): ?>
                        <a href="?page=manage_tags" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="bi bi-plus-lg"></i> ایجاد جدید
                        </a>
                    <?php endif; ?>
                </div>
                <div class="tt-card-body">
                    <form method="post" action="?page=s_save_tag">
                        <?php if ($edit_tag): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_tag['id']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label" for="tag_title">عنوان برچسب</label>
                            <input type="text" name="tag_title" id="tag_title"
                                class="form-control"
                                placeholder="مثال: اولویت بالا، پیگیری، ..."
                                value="<?php echo $edit_tag ? htmlspecialchars($edit_tag['title'], ENT_QUOTES, 'UTF-8') : (isset($_GET['title']) ? htmlspecialchars($_GET['title'], ENT_QUOTES, 'UTF-8') : ''); ?>"
                                maxlength="100" required
                                style="border-radius: 10px; padding: 10px 15px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">رنگ برچسب</label>
                            <div class="tt-color-picker" id="colorPicker">
                                <?php
                                $selected_color = $edit_tag ? $edit_tag['color'] : (isset($_GET['color']) ? $_GET['color'] : $default_color);
                                foreach ($preset_colors as $pc) {
                                    $sel = ($pc === $selected_color) ? 'selected' : '';
                                    echo '<div class="tt-color-option ' . $sel . '" style="background:' . $pc . ';" data-color="' . $pc . '" onclick="selectTagColor(this, \'' . $pc . '\')"></div>';
                                }
                                ?>
                            </div>
                            <div class="tt-color-input-wrapper">
                                <input type="color" id="tag_color_picker" name="tag_color_picker"
                                    value="<?php echo htmlspecialchars($selected_color); ?>"
                                    onchange="syncColorFromPicker(this)">
                                <span class="tt-color-hex" id="tag_color_hex"><?php echo htmlspecialchars($selected_color); ?></span>
                            </div>
                            <input type="hidden" name="tag_color" id="tag_color"
                                value="<?php echo htmlspecialchars($selected_color); ?>">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="?page=manage_tags" class="btn btn-outline-secondary rounded-pill px-4"
                                style="font-family: 'IranYekanNum', sans-serif;">
                                <i class="bi bi-x-circle me-1"></i> لغو
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4"
                                style="font-family: 'IranYekanNum', sans-serif; background: linear-gradient(135deg, var(--color-primary, var(--bs-primary)), var(--color-info, var(--bs-info))); border: none;">
                                <i class="bi bi-check-lg me-1"></i>
                                <?php echo $edit_tag ? 'ویرایش برچسب' : 'ایجاد برچسب'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tags list -->
        <div class="col-lg-7 mb-4">
            <div class="tt-card">
                <div class="tt-card-header">
                    <h5>
                        <i class="bi bi-tags text-primary"></i>
                        برچسب‌های من (<?php echo count($tags); ?>)
                    </h5>
                </div>
                <div class="tt-card-body">
                    <?php if (empty($tags)): ?>
                        <div class="empty-tags">
                            <i class="bi bi-tag"></i>
                            <p>هنوز برچسبی ایجاد نکرده‌اید. از فرم سمت راست برای ایجاد برچسب جدید استفاده کنید.</p>
                        </div>
                    <?php else: ?>
                        <div class="tt-grid">
                            <?php foreach ($tags as $tag): ?>
                                <div class="tt-tag-item">
                                    <div class="tt-tag-color-dot" style="background: <?php echo htmlspecialchars($tag['color'], ENT_QUOTES, 'UTF-8'); ?>;"></div>
                                    <span class="tt-tag-title"><?php echo htmlspecialchars($tag['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <div class="tt-tag-actions">
                                        <a href="?page=manage_tags&edit_id=<?php echo $tag['id']; ?>"
                                            class="btn btn-sm btn-outline-warning"
                                            title="ویرایش">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger js-delete-tag"
                                            data-tag-id="<?php echo $tag['id']; ?>"
                                            data-tag-title="<?php echo htmlspecialchars($tag['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                            title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectTagColor(el, color) {
        document.querySelectorAll('.tt-color-option').forEach(function(c) {
            c.classList.remove('selected');
        });
        el.classList.add('selected');
        document.getElementById('tag_color').value = color;
        document.getElementById('tag_color_picker').value = color;
        document.getElementById('tag_color_hex').textContent = color;
    }

    function syncColorFromPicker(picker) {
        var color = picker.value;
        document.getElementById('tag_color').value = color;
        document.getElementById('tag_color_hex').textContent = color;
        document.querySelectorAll('.tt-color-option').forEach(function(c) {
            c.classList.toggle('selected', c.getAttribute('data-color') === color);
        });
    }

    (function() {
        // Delete tag confirm
        document.addEventListener('click', function(ev) {
            var btn = ev.target.closest('.js-delete-tag');
            if (!btn) return;
            ev.preventDefault();
            var id = btn.getAttribute('data-tag-id');
            var title = btn.getAttribute('data-tag-title');
            if (!id) return;
            var msg = 'آیا از حذف برچسب "' + title + '" اطمینان دارید؟ این برچسب از همه تیکت‌هایی که به آن اختصاص داده شده نیز حذف خواهد شد.';
            var go = function() {
                window.location.href = '?page=s_delete_tag&id=' + encodeURIComponent(id);
            };
            if (typeof window.showConfirmDelete === 'function') {
                window.showConfirmDelete(msg, go);
            } else if (window.confirm(msg)) {
                go();
            }
        });
    })();
</script>
