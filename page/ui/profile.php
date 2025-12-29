<?php
// Get current user information
$code_p = $_SESSION['code_p'];
$user_info = null;

$Query_user = "SELECT * FROM karbar WHERE code_p = '$code_p' LIMIT 1";
if ($Result_user = mysqli_query($Link, $Query_user)) {
    if ($user_row = mysqli_fetch_array($Result_user)) {
        $user_info = $user_row;
    }
}

// Display success/error messages
if (isset($_GET['p'])) {
    if ($_GET['p'] == 'y') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> تغییرات با موفقیت اعمال شد.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } elseif ($_GET['p'] == 'n') {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> خطا در اعمال تغییرات. لطفاً دوباره تلاش کنید.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } elseif ($_GET['p'] == 't') {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> رمز عبور فعلی اشتباه است.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } elseif ($_GET['p'] == 'm') {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> رمزهای عبور جدید با یکدیگر مطابقت ندارند.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}

if ($user_info) {
    $current_avatar = !empty($user_info['avatar']) ? $user_info['avatar'] : 'karbar';
    $user_name = $user_info['name'];
    $user_semat = $user_info['semat'];
    $user_email = $user_info['email'];
    $user_tel = $user_info['tel'];
?>

<style>
.profile-container {
    max-width: 900px;
    margin: 0 auto;
}

.profile-card {
    background: var(--bs-card-bg, #fff);
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-bottom: 24px;
}

.profile-card-header {
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
    padding: 32px 24px;
    text-align: center;
    color: white;
}

.profile-avatar-section {
    position: relative;
    display: inline-block;
    margin-bottom: 16px;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    object-fit: cover;
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
}


.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 8px 0;
}

.profile-semat {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
}

.profile-card-body {
    padding: 32px;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--bs-body-color);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--bs-border-color);
}

.section-title i {
    color: var(--bs-primary);
    font-size: 1.3rem;
}

.form-group-modern {
    margin-bottom: 24px;
}

.form-label-modern {
    font-weight: 600;
    color: var(--bs-body-color);
    margin-bottom: 8px;
    display: block;
    font-size: 0.95rem;
}

.form-control-modern {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--bs-border-color);
    border-radius: 10px;
    background: var(--bs-card-bg);
    color: var(--bs-body-color);
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 3px rgba(60, 146, 177, 0.2);
}

.btn-modern {
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary-modern {
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
    color: white;
    box-shadow: 0 4px 12px rgba(60, 146, 177, 0.3);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(60, 146, 177, 0.4);
    color: white;
}

.btn-success-modern {
    background: linear-gradient(135deg, var(--bs-success), rgba(25, 135, 84, 0.8));
    color: white;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.btn-success-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(25, 135, 84, 0.4);
    color: white;
}

.avatar-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 16px;
    max-height: 300px;
    overflow-y: auto;
    padding: 16px;
    background: var(--bs-tertiary-bg);
    border-radius: 12px;
}

.avatar-option {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.2s ease;
    object-fit: cover;
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
}

.avatar-option:hover {
    transform: scale(1.1);
    border-color: var(--bs-primary);
    box-shadow: 0 4px 12px rgba(60, 146, 177, 0.4);
}

.avatar-option.selected {
    border-color: var(--bs-success);
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.3);
}

.password-strength {
    margin-top: 8px;
    font-size: 0.85rem;
}

.password-strength.weak {
    color: var(--bs-danger);
}

.password-strength.medium {
    color: var(--bs-warning);
}

.password-strength.strong {
    color: var(--bs-success);
}

.info-box {
    background: var(--bs-tertiary-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
}

.info-box i {
    color: var(--bs-info);
    margin-left: 8px;
}

@media (max-width: 768px) {
    .profile-card-body {
        padding: 20px;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
    }
    
    .avatar-option {
        width: 60px;
        height: 60px;
    }
}
</style>

<div class="profile-container">
    <!-- Profile Header Card -->
    <div class="profile-card">
        <div class="profile-card-header">
            <div class="profile-avatar-section">
                <img src="assets/images/<?php echo $current_avatar; ?>.png" 
                     alt="<?php echo htmlspecialchars($user_name); ?>" 
                     class="profile-avatar" 
                     id="currentAvatarDisplay">
            </div>
            <h2 class="profile-name"><?php echo htmlspecialchars($user_name); ?></h2>
            <p class="profile-semat"><?php echo htmlspecialchars($user_semat); ?></p>
        </div>
    </div>

    <!-- Avatar Selection Card -->
    <div class="profile-card">
        <div class="profile-card-body">
            <h3 class="section-title">
                <i class="bi bi-person-circle"></i>
                انتخاب آواتار
            </h3>
            
            <form method="post" action="?page=s_update_avatar" id="avatarForm">
                <input type="hidden" name="selected_avatar" id="selectedAvatar" value="<?php echo $current_avatar; ?>">
                
                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <span>لطفاً یکی از آواتارهای موجود را انتخاب کنید.</span>
                </div>

                <div class="form-group-modern">
                    <label class="form-label-modern">آواتارهای موجود:</label>
                    <div class="avatar-preview-container" id="avatarOptions">
                        <?php
                        // List available avatars from assets/images directory
                        $avatar_dir = 'assets/images/';
                        $available_avatars = ['karbar', 'maryam', 'mobina', 'morteza', 'sara', 'user1', 'user2', 'user3', 'user4', 'user5'];
                        
                        foreach ($available_avatars as $avatar_name) {
                            $avatar_path = $avatar_dir . $avatar_name . '.png';
                            if (file_exists($avatar_path)) {
                                $is_selected = ($avatar_name == $current_avatar) ? 'selected' : '';
                                echo '<img src="' . $avatar_path . '" 
                                          alt="' . $avatar_name . '" 
                                          class="avatar-option ' . $is_selected . '" 
                                          data-avatar="' . $avatar_name . '"
                                          onclick="selectAvatar(\'' . $avatar_name . '\')">';
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="bi bi-check-circle"></i>
                        ذخیره آواتار
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change Card -->
    <div class="profile-card">
        <div class="profile-card-body">
            <h3 class="section-title">
                <i class="bi bi-shield-lock"></i>
                تغییر رمز عبور
            </h3>

            <form method="post" action="?page=s_change_password" id="passwordForm">
                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <span>برای تغییر رمز عبور، رمز فعلی و رمز جدید خود را وارد کنید.</span>
                </div>

                <div class="form-group-modern">
                    <label class="form-label-modern" for="current_password">
                        رمز عبور فعلی <span class="text-danger">*</span>
                    </label>
                    <input type="password" 
                           class="form-control-modern" 
                           id="current_password" 
                           name="current_password" 
                           required
                           autocomplete="current-password">
                </div>

                <div class="form-group-modern">
                    <label class="form-label-modern" for="new_password">
                        رمز عبور جدید <span class="text-danger">*</span>
                    </label>
                    <input type="password" 
                           class="form-control-modern" 
                           id="new_password" 
                           name="new_password" 
                           required
                           autocomplete="new-password"
                           onkeyup="checkPasswordStrength(this.value)">
                    <div id="passwordStrength" class="password-strength"></div>
                </div>

                <div class="form-group-modern">
                    <label class="form-label-modern" for="confirm_password">
                        تکرار رمز عبور جدید <span class="text-danger">*</span>
                    </label>
                    <input type="password" 
                           class="form-control-modern" 
                           id="confirm_password" 
                           name="confirm_password" 
                           required
                           autocomplete="new-password"
                           onkeyup="checkPasswordMatch()">
                    <div id="passwordMatch" class="password-strength"></div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary">لغو</button>
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="bi bi-key"></i>
                        تغییر رمز عبور
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="profile-card">
        <div class="profile-card-body">
            <h3 class="section-title">
                <i class="bi bi-person-badge"></i>
                اطلاعات کاربری
            </h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label class="form-label-modern">کد کاربری:</label>
                        <input type="text" 
                               class="form-control-modern" 
                               value="<?php echo htmlspecialchars($code_p); ?>" 
                               readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label class="form-label-modern">نام:</label>
                        <input type="text" 
                               class="form-control-modern" 
                               value="<?php echo htmlspecialchars($user_name); ?>" 
                               readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label class="form-label-modern">سمت:</label>
                        <input type="text" 
                               class="form-control-modern" 
                               value="<?php echo htmlspecialchars($user_semat); ?>" 
                               readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label class="form-label-modern">ایمیل:</label>
                        <input type="text" 
                               class="form-control-modern" 
                               value="<?php echo htmlspecialchars($user_email ?: '-'); ?>" 
                               readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label class="form-label-modern">تلفن:</label>
                        <input type="text" 
                               class="form-control-modern" 
                               value="<?php echo htmlspecialchars($user_tel ?: '-'); ?>" 
                               readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Avatar selection
function selectAvatar(avatarName) {
    // Remove selected class from all avatars
    document.querySelectorAll('.avatar-option').forEach(avatar => {
        avatar.classList.remove('selected');
        if (avatar.getAttribute('data-avatar') === avatarName) {
            avatar.classList.add('selected');
        }
    });
    
    // Update hidden input and display
    document.getElementById('selectedAvatar').value = avatarName;
    document.getElementById('currentAvatarDisplay').src = 'assets/images/' + avatarName + '.png';
}

// Password strength checker
function checkPasswordStrength(password) {
    const strengthDiv = document.getElementById('passwordStrength');
    if (!password) {
        strengthDiv.textContent = '';
        return;
    }
    
    let strength = 0;
    let strengthText = '';
    let strengthClass = '';
    
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    if (strength <= 2) {
        strengthText = 'ضعیف';
        strengthClass = 'weak';
    } else if (strength <= 3) {
        strengthText = 'متوسط';
        strengthClass = 'medium';
    } else {
        strengthText = 'قوی';
        strengthClass = 'strong';
    }
    
    strengthDiv.textContent = 'قدرت رمز: ' + strengthText;
    strengthDiv.className = 'password-strength ' + strengthClass;
}

// Password match checker
function checkPasswordMatch() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const matchDiv = document.getElementById('passwordMatch');
    
    if (!confirmPassword) {
        matchDiv.textContent = '';
        return;
    }
    
    if (newPassword === confirmPassword) {
        matchDiv.textContent = '✓ رمزهای عبور مطابقت دارند';
        matchDiv.className = 'password-strength strong';
    } else {
        matchDiv.textContent = '✗ رمزهای عبور مطابقت ندارند';
        matchDiv.className = 'password-strength weak';
    }
}

// Form validation
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('رمزهای عبور جدید با یکدیگر مطابقت ندارند.');
        return false;
    }
    
    if (newPassword.length < 6) {
        e.preventDefault();
        alert('رمز عبور باید حداقل 6 کاراکتر باشد.');
        return false;
    }
});
</script>

<?php
} else {
    echo '<div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        اطلاعات کاربری یافت نشد.
    </div>';
}
?>
