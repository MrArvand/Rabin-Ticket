<?php
$bpms_view = false;

if ((isset($_SESSION['kind']) && $_SESSION['kind'] === 'bpms')) {
    $bpms_view = true;
}
// Users who can use the "new user access" saved response template
$new_user_response_allowed_codes = [
    '1100097'
];
$current_user_code = isset($_SESSION['code_p']) ? trim((string) $_SESSION['code_p']) : '';
$can_use_new_user_response = in_array($current_user_code, $new_user_response_allowed_codes, true);
require_once(__DIR__ . '/../../inf/mattermost_service.php');
require_once(__DIR__ . '/../../inf/ticket_tags.php');
$can_send_to_bsp = ticket_can_send_to_bsp($current_user_code);
$new_user_response_template = "درود\nدسترسی تیکت کاربر و پالودیار با کد پرسنلی و رمز 123456 ایجاد گردید.";
// Display success/error messages for category change
if (isset($_GET['cat']) && $_GET['cat'] == 'changed' && isset($_GET['p']) && $_GET['p'] == 'y') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle"></i> دسته بندی تیکت با موفقیت تغییر یافت.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
} elseif (isset($_GET['cat']) && $_GET['cat'] == 'error' && isset($_GET['p']) && $_GET['p'] == 'n') {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle"></i> خطا در تغییر دسته بندی تیکت. لطفاً دوباره تلاش کنید.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
} elseif (isset($_GET['bsp_sent']) && $_GET['bsp_sent'] == '1') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle"></i> تیکت با موفقیت به گروه ICT در Mattermost ارسال شد.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
} elseif (isset($_GET['bsp_error']) && $_GET['bsp_error'] == '1') {
    $bsp_error_msg = isset($_GET['bsp_msg']) ? trim((string) $_GET['bsp_msg']) : 'خطا در ارسال تیکت به گروه ICT. لطفاً دوباره تلاش کنید.';
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle"></i> ' . htmlspecialchars($bsp_error_msg, ENT_QUOTES, 'UTF-8') . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

// Extract ticket code from URL parameter - IMPORTANT: Use explicit GET parameter to ensure correct ticket
$code = isset($_GET['code']) ? trim($_GET['code']) : (isset($code) ? $code : '');
if (empty($code) || $code === '0') {
    echo '<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle me-2"></i>
    شماره تیکت مشخص نشده است.
  </div>';
    exit;
}

// Ensure code is properly initialized from GET parameter
$code = str_g('code');
// Escape code for SQL queries
$code_escaped = mysqli_real_escape_string($Link, $code);

// Capture list parameters for back navigation
$list_params = [];
$list_param_keys = ['faal', 'daste', 'sherkat', 'tarikh_1', 'tarikh_2', 'sn_ticket', 'titr', 'karbar_ersal', 'karbar_paziresh', 'per_page', 'page_number', 'kind'];
foreach ($list_param_keys as $key) {
    if (isset($_GET[$key]) && $_GET[$key] !== '' && $_GET[$key] !== '0') {
        $list_params[$key] = $_GET[$key];
    }
}
$back_to_list_url = '?page=list_ticket';
if (!empty($list_params)) {
    $back_to_list_url .= '&' . http_build_query($list_params);
}

$Query_ticket = "SELECT * FROM ticket WHERE code = '$code_escaped' ORDER BY i_ticket DESC LIMIT 1";
$current_ticket = null;
if ($Result_ticket = mysqli_query($Link, $Query_ticket)) {
    if ($q_ticket = mysqli_fetch_array($Result_ticket)) {
        $current_ticket = $q_ticket;
    }
}

// Check if user has access to reassignment (and category change)
$has_reassign_access = true;
if (
    $_SESSION['code_p'] == "23056" || $_SESSION['code_p'] == "24277" || $_SESSION['code_p'] == "25662" ||
    $_SESSION['code_p'] == "21379" || $_SESSION['code_p'] == "21057" || $_SESSION['code_p'] == "20071" ||
    $_SESSION['code_p'] == "20612" || $_SESSION['code_p'] == "1100105" || $_SESSION['code_p'] == "20072" || $_SESSION['code_p'] == "1100056"
) {
    $has_reassign_access = true;
}

// Preserve 'p' parameter for links
$p_param = isset($_GET['p']) ? '&p=' . htmlspecialchars($_GET['p']) : '';

// Check tag tables availability (used both inside the ticket block and in modals)
$ticket_tags_available = ticket_tags_table_exists($Link) && ticket_tags_assignments_table_exists($Link);

if ($current_ticket !== null) {
?>

    <style>
        /* Modern Chat Interface Styles */
        .ticket-chat-container {
            display: flex;
            flex-direction: column;
            background: var(--color-bg-card, var(--bs-card-bg));
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px var(--color-shadow-lg, rgba(0, 0, 0, 0.3));
        }

        /* Header Section */
        .ticket-header {
            background: linear-gradient(135deg, var(--color-bg-tertiary, var(--bs-tertiary-bg)) 0%, var(--color-bg-card, var(--bs-card-bg)) 100%);
            padding: 20px 24px;
            border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color));
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .ticket-header-info {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            min-width: 280px;
        }

        .ticket-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary, var(--bs-primary)), var(--color-info, var(--bs-info)));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--color-white, #ffffff);
            font-weight: bold;
            flex-shrink: 0;
            box-shadow: 0 4px 12px var(--color-shadow-md, rgba(60, 146, 177, 0.4));
        }

        .ticket-title-section h4 {
            margin: 0 0 4px 0;
            color: var(--color-text-primary, var(--bs-body-color));
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .ticket-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .ticket-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--color-text-secondary, var(--bs-secondary-color));
        }

        .ticket-meta-item i {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .ticket-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .ticket-status.status-a {
            background: var(--color-badge-danger-bg, rgba(191, 122, 106, 0.2));
            color: var(--color-danger, var(--bs-danger));
            border: 1px solid var(--color-danger, var(--bs-danger));
        }

        .ticket-status.status-m {
            background: var(--color-badge-info-bg, rgba(111, 180, 206, 0.2));
            color: var(--color-info, var(--bs-info));
            border: 1px solid var(--color-info, var(--bs-info));
        }

        .ticket-status.status-w {
            background: var(--color-badge-primary-bg, rgba(60, 146, 177, 0.2));
            color: var(--color-primary, var(--bs-primary));
            border: 1px solid var(--color-primary, var(--bs-primary));
        }

        .ticket-status.status-b {
            background: var(--color-badge-success-bg, rgba(169, 189, 122, 0.2));
            color: var(--color-success, var(--bs-success));
            border: 1px solid var(--color-success, var(--bs-success));
        }

        .ticket-status.status-k {
            background: var(--color-badge-warning-bg, rgba(210, 169, 104, 0.2));
            color: var(--color-warning, var(--bs-warning));
            border: 1px solid var(--color-warning, var(--bs-warning));
        }

        .ticket-status.status-t {
            background: var(--color-badge-warning-bg, rgba(210, 169, 104, 0.2));
            color: var(--color-warning, var(--bs-warning));
            border: 1px solid var(--color-warning, var(--bs-warning));
        }

        .ticket-status.status-c {
            background: var(--color-badge-secondary-bg, rgba(80, 89, 106, 0.2));
            color: var(--color-secondary, var(--bs-secondary));
            border: 1px solid var(--color-secondary, var(--bs-secondary));
        }

        .ticket-priority {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .priority-1 {
            background: var(--color-danger, var(--bs-danger));
            color: var(--color-white, #ffffff);
        }

        .priority-2 {
            background: var(--color-warning, var(--bs-warning));
            color: var(--color-text-inverse, var(--bs-dark));
        }

        .priority-3 {
            background: var(--color-info, var(--bs-info));
            color: var(--color-white, #ffffff);
        }

        .priority-4 {
            background: var(--color-secondary, var(--bs-secondary));
            color: var(--color-white, #ffffff);
        }

        .ticket-header-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ticket-header-actions .btn {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .ticket-header-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--color-shadow-lg, rgba(0, 0, 0, 0.3));
        }

        /* Info Panel */
        .ticket-info-panel {
            background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
            padding: 16px 24px;
            border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color));
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
        }

        .info-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--color-bg-card, var(--bs-card-bg));
            border-radius: 12px;
            font-size: 0.85rem;
            color: var(--color-text-secondary, var(--bs-secondary-color));
            transition: all 0.2s ease;
        }

        .info-pill:hover {
            background: var(--color-bg-hover, var(--bs-tertiary-bg));
        }

        .info-pill i {
            color: var(--color-primary, var(--bs-primary));
            font-size: 1rem;
        }

        .info-pill strong {
            color: var(--color-text-primary, var(--bs-body-color));
            margin-right: 4px;
        }

        /* Chat Messages Area */
        .chat-messages-container {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            height: 500px;
            min-height: 300px;
            max-height: 70vh;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Blurred background image using pseudo-element - fixed position within container */
        .chat-messages-container::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('assets/images/night.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(0.2rem);
            z-index: 0;
            pointer-events: none;
        }

        /* Light theme background */
        [data-theme="light"] .chat-messages-container::before,
        .theme-light .chat-messages-container::before {
            background-image: url('assets/images/day.webp');
        }

        /* Clip the background to the container boundaries */
        .chat-messages-container {
            clip-path: inset(0);
        }

        /* Ensure content stays above the blurred background */
        .chat-messages-container>* {
            position: relative;
            z-index: 1;
        }

        /* Custom scrollbar for chat messages */
        .chat-messages-container::-webkit-scrollbar {
            width: 8px;
        }

        .chat-messages-container::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb {
            background: rgba(60, 146, 177, 0.5);
            border-radius: 4px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb:hover {
            background: rgba(60, 146, 177, 0.7);
        }


        /* Message Bubbles */
        .message-wrapper {
            display: flex;
            gap: 12px;
            max-width: 85%;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-wrapper.sent {
            flex-direction: row;
            margin-right: 0;
            margin-left: auto;
        }

        .message-wrapper.received {
            flex-direction: row-reverse;
            margin-left: 0;
            margin-right: auto;
        }

        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.85rem;
            color: var(--color-white, #ffffff);
            box-shadow: 0 2px 8px var(--color-shadow-md, rgba(0, 0, 0, 0.2));
        }

        .message-wrapper.sent .message-avatar {
            background: linear-gradient(135deg, var(--color-primary, var(--bs-primary)), var(--color-info, var(--bs-info)));
        }

        .message-wrapper.received .message-avatar {
            background: linear-gradient(135deg, var(--color-secondary, var(--bs-secondary)), var(--color-gray-400, var(--bs-gray-400)));
        }

        .message-content {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .message-wrapper.sent .message-content {
            align-items: flex-start;
        }

        .message-wrapper.received .message-content {
            align-items: flex-end;
        }

        .message-sender {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 8px;
            display: inline-block;
        }

        .message-wrapper.sent .message-sender {
            color: #ffffff;
            background: rgba(60, 146, 177, 0.85);
            text-align: right;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .message-wrapper.received .message-sender {
            color: #1a1a2e;
            background: rgba(255, 255, 255, 0.85);
            text-align: right;
            text-shadow: none;
        }

        /* Dark theme adjustments for received message sender */
        [data-theme="dark"] .message-wrapper.received .message-sender,
        .theme-dark .message-wrapper.received .message-sender {
            color: #e0e0e0;
            background: rgba(40, 44, 52, 0.9);
        }

        .message-bubble {
            padding: 14px 18px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
            line-height: 1.5;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px var(--color-shadow-sm, rgba(0, 0, 0, 0.15));
        }

        .message-wrapper.sent .message-bubble {
            background: linear-gradient(135deg, var(--color-primary, var(--bs-primary)), var(--color-primary-light, rgba(60, 146, 177, 0.8)));
            color: var(--color-white, #ffffff);
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 4px;
        }

        .message-wrapper.received .message-bubble {
            background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
            color: var(--color-text-primary, var(--bs-body-color));
            border-bottom-left-radius: 18px;
            border-bottom-right-radius: 4px;
            border: 1px solid var(--color-border-primary, var(--bs-border-color));
        }

        .message-bubble p {
            margin: 0;
        }

        .message-attachment {
            margin-top: 10px;
            padding: 10px 14px;
            background: var(--color-overlay-light, rgba(0, 0, 0, 0.15));
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            transition: background 0.2s ease;
        }

        .message-wrapper.received .message-attachment {
            background: var(--color-bg-card, var(--bs-card-bg));
        }

        .message-attachment:hover {
            background: var(--color-overlay-medium, rgba(0, 0, 0, 0.25));
        }

        .message-attachment i {
            font-size: 1.2rem;
        }

        .message-attachment span {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .message-time {
            font-size: 0.75rem;
            padding: 4px 10px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 8px;
            font-weight: 500;
        }

        .message-wrapper.sent .message-time {
            justify-content: flex-start;
            color: #ffffff;
            background: rgba(60, 146, 177, 0.7);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .message-wrapper.received .message-time {
            justify-content: flex-end;
            color: #1a1a2e;
            background: rgba(255, 255, 255, 0.8);
            text-shadow: none;
        }

        /* Dark theme adjustments for received message time */
        [data-theme="dark"] .message-wrapper.received .message-time,
        .theme-dark .message-wrapper.received .message-time {
            color: #b0b0b0;
            background: rgba(40, 44, 52, 0.85);
        }

        .message-seen-icon {
            font-size: 0.85rem;
        }

        .message-wrapper.sent .message-seen-icon.seen {
            color: #a8e6cf;
        }

        .message-wrapper.sent .message-seen-icon.unseen {
            color: rgba(255, 255, 255, 0.7);
        }

        .message-wrapper.received .message-seen-icon.seen {
            color: #2e7d32;
        }

        .message-wrapper.received .message-seen-icon.unseen {
            color: #666666;
        }

        /* Dark theme adjustments for received message seen icons */
        [data-theme="dark"] .message-wrapper.received .message-seen-icon.seen,
        .theme-dark .message-wrapper.received .message-seen-icon.seen {
            color: #6fb4ce;
        }

        [data-theme="dark"] .message-wrapper.received .message-seen-icon.unseen,
        .theme-dark .message-wrapper.received .message-seen-icon.unseen {
            color: #888888;
        }

        /* Referral Divider Styles - matches chat-divider */
        .referral-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 8px 0;
        }

        .referral-divider::before,
        .referral-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--color-border-primary, var(--bs-border-color));
        }

        .referral-divider span {
            font-size: 0.75rem;
            color: var(--color-text-secondary, var(--bs-secondary-color));
            padding: 4px 12px;
            background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .referral-divider span i {
            color: var(--color-warning, var(--bs-warning));
            font-size: 0.85rem;
        }

        .referral-divider span strong {
            color: var(--color-warning, var(--bs-warning));
            font-weight: 600;
            margin: 0 3px;
        }

        .original-ticket-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed var(--color-border-primary, var(--bs-border-color));
        }

        .original-ticket-badge {
            background: var(--color-primary, var(--bs-primary));
            color: var(--color-white, #ffffff);
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .original-ticket-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary, var(--bs-body-color));
            margin: 0;
        }

        /* Chat Input Area */
        .chat-input-container {
            background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
            padding: 20px 24px;
            border-top: 1px solid var(--color-border-primary, var(--bs-border-color));
        }

        .chat-input-wrapper {
            display: flex;
            gap: 16px;
            align-items: flex-end;
        }

        .chat-input-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .chat-textarea {
            width: 100%;
            min-height: 80px;
            max-height: 200px;
            padding: 14px 18px;
            border: 2px solid var(--color-border-primary, var(--bs-border-color));
            border-radius: 16px;
            background: var(--color-bg-card, var(--bs-card-bg));
            color: var(--color-text-primary, var(--bs-body-color));
            font-size: 0.95rem;
            resize: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .chat-textarea:focus {
            outline: none;
            border-color: var(--color-primary, var(--bs-primary));
            box-shadow: 0 0 0 3px var(--color-badge-primary-bg, rgba(60, 146, 177, 0.2));
        }

        .chat-textarea::placeholder {
            color: var(--color-text-muted, var(--bs-secondary-color));
        }

        .chat-input-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-file-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-file-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--color-bg-hover, var(--bs-tertiary-bg));
            border-radius: 10px;
            color: var(--color-text-secondary, var(--bs-secondary-color));
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .chat-file-label:hover {
            background: var(--color-bg-active, var(--bs-gray-400));
            color: var(--color-text-primary, var(--bs-body-color));
        }

        .chat-file-label i {
            font-size: 1.1rem;
        }

        .chat-file-label input {
            display: none;
        }

        .chat-submit-btn {
            padding: 12px 28px;
            background: linear-gradient(135deg, var(--color-primary, var(--bs-primary)), var(--color-info, var(--bs-info)));
            border: none;
            border-radius: 12px;
            color: var(--color-white, #ffffff);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px var(--color-shadow-md, rgba(60, 146, 177, 0.3));
        }

        .chat-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--color-shadow-lg, rgba(60, 146, 177, 0.4));
        }

        .chat-submit-btn:active {
            transform: translateY(0);
        }

        /* Action Buttons Row */
        .action-buttons-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 16px 24px;
            background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
            border-top: 1px solid var(--color-border-primary, var(--bs-border-color));
        }

        .action-btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--color-shadow-md, rgba(0, 0, 0, 0.2));
        }

        .action-btn-success {
            background: var(--color-success, var(--bs-success));
            color: var(--color-text-inverse, var(--bs-dark));
        }

        .action-btn-primary {
            background: var(--color-primary, var(--bs-primary));
            color: var(--color-white, #ffffff);
        }

        .action-btn-warning {
            background: var(--color-warning, var(--bs-warning));
            color: var(--color-text-inverse, var(--bs-dark));
        }

        .action-btn-info {
            background: var(--color-info, var(--bs-info));
            color: var(--color-white, #ffffff);
        }

        .action-btn-danger {
            background: var(--color-danger, var(--bs-danger));
            color: var(--color-white, #ffffff);
        }

        /* Divider */
        .chat-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 8px 0;
        }

        .chat-divider::before,
        .chat-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--color-border-primary, var(--bs-border-color));
        }

        .chat-divider span {
            font-size: 0.75rem;
            color: var(--color-text-secondary, var(--bs-secondary-color));
            padding: 4px 12px;
            background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
            border-radius: 12px;
        }

        /* Empty State */
        .no-messages {
            text-align: center;
            padding: 40px;
            color: var(--color-text-muted, var(--bs-secondary-color));
        }

        .no-messages i {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        /* Modal Enhancements */
        .modal-content {
            background: var(--color-bg-card, var(--bs-card-bg));
            border: 1px solid var(--color-border-primary, var(--bs-border-color));
            border-radius: 16px;
        }

        .modal-header {
            border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color));
            padding: 20px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid var(--color-border-primary, var(--bs-border-color));
            padding: 16px 24px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .ticket-header {
                padding: 16px;
            }

            .ticket-header-info {
                min-width: 100%;
            }

            .ticket-info-panel {
                padding: 12px 16px;
            }

            .chat-messages-container {
                padding: 16px;
                height: 400px;
                max-height: 60vh;
            }

            .message-wrapper {
                max-width: 95%;
            }

            .chat-input-container {
                padding: 16px;
            }

            .action-buttons-row {
                padding: 12px 16px;
            }
        }

        /* Visit Log Badge */
        .visit-badge {
            font-size: 0.75rem;
            color: var(--color-text-secondary, var(--bs-secondary-color));
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
            border-radius: 8px;
            margin-top: 16px;
        }

        /* Delete Button */
        .delete-ticket-btn {
            background: var(--color-danger, var(--bs-danger));
            color: var(--color-white, #ffffff);
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .delete-ticket-btn:hover {
            background: var(--color-danger-dark, #a5685a);
            color: var(--color-white, #ffffff);
            transform: translateY(-2px);
        }

        /* Saved Replies Dropdown */
        .saved-replies-dropdown {
            position: relative;
            display: inline-block;
        }

        .saved-replies-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--color-bg-hover, var(--bs-tertiary-bg));
            border: 2px solid var(--color-border-primary, var(--bs-border-color));
            border-radius: 10px;
            color: var(--color-text-secondary, var(--bs-secondary-color));
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .saved-replies-btn:hover {
            background: var(--color-bg-active, var(--bs-gray-400));
            color: var(--color-text-primary, var(--bs-body-color));
            border-color: var(--color-primary, var(--bs-primary));
        }

        .saved-replies-btn i:first-child {
            font-size: 1.1rem;
            color: var(--color-primary, var(--bs-primary));
        }

        .saved-replies-btn i:last-child {
            font-size: 0.7rem;
            transition: transform 0.2s ease;
        }

        .saved-replies-dropdown.open .saved-replies-btn i:last-child {
            transform: rotate(180deg);
        }

        .saved-replies-menu {
            position: absolute;
            bottom: 100%;
            left: 0;
            min-width: 280px;
            background: var(--color-bg-card, var(--bs-card-bg));
            border: 1px solid var(--color-border-primary, var(--bs-border-color));
            border-radius: 12px;
            box-shadow: 0 8px 32px var(--color-shadow-lg, rgba(0, 0, 0, 0.3));
            margin-bottom: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease;
            z-index: 1000;
            overflow: hidden;
        }

        .saved-replies-dropdown.open .saved-replies-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .saved-replies-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: var(--color-bg-tertiary, var(--bs-tertiary-bg));
            border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color));
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--color-text-secondary, var(--bs-secondary-color));
        }

        .saved-replies-header i {
            color: var(--color-warning, var(--bs-warning));
        }

        .saved-reply-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.15s ease;
            border-bottom: 1px solid var(--color-border-secondary, rgba(255, 255, 255, 0.05));
        }

        .saved-reply-item:last-child {
            border-bottom: none;
        }

        .saved-reply-item:hover {
            background: var(--color-bg-hover, var(--bs-tertiary-bg));
        }

        .saved-reply-item i {
            font-size: 1rem;
            color: var(--color-info, var(--bs-info));
            flex-shrink: 0;
        }

        .saved-reply-item span {
            font-size: 0.85rem;
            color: var(--color-text-primary, var(--bs-body-color));
            font-weight: 500;
        }

        .saved-reply-item:hover i {
            color: var(--color-primary, var(--bs-primary));
        }

        /* RTL adjustments for saved replies */
        .saved-replies-menu {
            left: auto;
            right: 0;
        }

        @media (max-width: 768px) {
            .saved-replies-menu {
                min-width: 250px;
            }

            .saved-replies-btn span {
                display: none;
            }
        }

        /* Ticket Tag Badges */
        .ticket-tag-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.6rem;
            font-weight: 600;
            line-height: 1.4;
            white-space: nowrap;
        }

        /* Tag Management Modal */
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

        .tt-modal-empty a {
            color: var(--color-primary, var(--bs-primary));
            font-weight: 600;
        }
    </style>

    <!-- Back to List Button -->
    <div class="mb-3">
        <a href="<?php echo htmlspecialchars($back_to_list_url, ENT_QUOTES, 'UTF-8'); ?>"
            class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 0.9rem; transition: all 0.2s ease;">
            <i class="bi bi-arrow-right me-2"></i>
            بازگشت به لیست تیکت‌ها
        </a>
    </div>

    <div class="ticket-chat-container">
        <!-- Header Section -->
        <div class="ticket-header">
            <div class="ticket-header-info">
                <div class="ticket-avatar">
                    <?php echo mb_substr($current_ticket['name_karbar'], 0, 1, 'UTF-8'); ?>
                </div>
                <div class="ticket-title-section">
                    <h4><?php echo $current_ticket['titr']; ?></h4>
                    <div class="ticket-meta">
                        <div class="ticket-meta-item">
                            <i class="bi bi-person"></i>
                            <span><?php echo $current_ticket['name_karbar']; ?></span>
                        </div>
                        <div class="ticket-meta-item">
                            <i class="bi bi-building"></i>
                            <span><?php echo $current_ticket['name_sherkat']; ?></span>
                        </div>
                        <div class="ticket-meta-item">
                            <i class="bi bi-building"></i>
                            <span>شرکت گیرنده : <?php
                                                $targetName = trim($current_ticket['target_sherkat_name'] ?? '');
                                                echo $targetName !== '' ? $targetName : 'نامشخص';
                                                ?>
                            </span>
                        </div>
                        <div class="ticket-meta-item">
                            <i class="bi bi-telephone"></i>
                            <a href="tel:<?php echo $current_ticket['tel_karbar']; ?>" class="text-info">
                                <?php echo $current_ticket['tel_karbar']; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <!-- Status Badge -->
                <span class="ticket-status status-<?php echo $current_ticket['vaziat']; ?>">
                    <?php
                    $status_labels = [
                        'a' => 'ثبت اولیه',
                        'm' => 'درحال بررسی',
                        'w' => 'روی میز',
                        'b' => 'بسته شده',
                        'k' => 'انجام شد',
                        't' => 'بررسی مجدد',
                        'c' => 'کنسل شده'
                    ];
                    echo $status_labels[$current_ticket['vaziat']] ?? 'نامشخص';
                    ?>
                </span>

                <!-- Priority Badge -->
                <span class="ticket-priority priority-<?php echo $current_ticket['olaviat']; ?>">
                    <?php
                    $priority_labels = [
                        '1' => 'ضروری',
                        '2' => 'متوسط',
                        '3' => 'معمولی',
                        '4' => 'پایین'
                    ];
                    echo $priority_labels[$current_ticket['olaviat']] ?? 'نامشخص';
                    ?>
                </span>
            </div>

            <div class="ticket-header-actions">
                <?php if ($current_ticket['code_p_karbar_anjam'] == "") { ?>
                    <?php if ($has_reassign_access) { ?>
                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#selector_mod">
                            <i class="bi bi-person-plus"></i> ارجاع به
                        </button>
                    <?php } ?>
                <?php } else { ?>
                    <?php if ($has_reassign_access) { ?>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#selector_mod">
                            <i class="bi bi-arrow-repeat"></i> ارجاع مجدد
                        </button>
                    <?php } ?>
                <?php } ?>

                <?php if ($has_reassign_access) { ?>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#category_change_modal">
                        <i class="bi bi-tag"></i> تغییر دسته بندی
                    </button>
                <?php } ?>

                <?php if ($can_send_to_bsp) { ?>
                <a href="?page=send_ticket_to_bsp&code=<?php echo urlencode($code); ?><?php echo $p_param; ?>"
                    class="btn btn-primary"
                    onclick="return confirm('آیا از ارسال این تیکت به گروه ICT در Mattermost مطمئن هستید؟');">
                    <i class="bi bi-send"></i> ارسال به گروه ICT
                </a>
                <?php } ?>
                <?php if ($ticket_tags_available): ?>
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#tags_modal">
                        <i class="bi bi-tags"></i> برچسب‌ها
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="ticket-info-panel">
            <div class="info-pill">
                <i class="bi bi-hash"></i>
                <span>شماره تیکت:</span>
                <strong><?php echo $current_ticket['code']; ?></strong>
            </div>
            <div class="info-pill">
                <i class="bi bi-folder2"></i>
                <span>دپارتمان:</span>
                <strong><?php echo $current_ticket['name_daste']; ?></strong>
            </div>
            <div class="info-pill">
                <i class="bi bi-calendar-event"></i>
                <span>زمان درخواست:</span>
                <strong><?php echo $current_ticket['tarikh_sabt']; ?> - <?php echo $current_ticket['saat_sabt']; ?></strong>
            </div>
            <?php if ($current_ticket['code_p_karbar_anjam'] != "") { ?>
                <div class="info-pill">
                    <i class="bi bi-person-check"></i>
                    <span>پاسخگو:</span>
                    <strong><?php echo $current_ticket['name_karbar_anjam']; ?></strong>
                </div>
            <?php } ?>
            <?php if (!empty($current_ticket['tarikh_anjam'])) { ?>
                <div class="info-pill">
                    <i class="bi bi-calendar-check"></i>
                    <span>زمان اتمام:</span>
                    <strong><?php echo $current_ticket['tarikh_anjam']; ?> -
                        <?php echo $current_ticket['saat_anjam']; ?></strong>
                </div>
            <?php } ?>
        </div>

        <!-- Tags Section -->
        <?php
        $current_user_tags = [];
        $ticket_assigned_tags = [];
        if ($ticket_tags_available) {
            $current_user_tags = ticket_tag_load_user_tags($Link, $_SESSION['code_p']);
            $assigned_map = ticket_tag_load_assignments($Link, $_SESSION['code_p'], [$code]);
            $ticket_assigned_tags = $assigned_map[$code] ?? [];
        }
        ?>
        <?php if (!empty($ticket_assigned_tags)): ?>
            <div style="display: flex; flex-wrap: wrap; gap: 6px; padding: 12px 24px; border-bottom: 1px solid var(--color-border-primary, var(--bs-border-color));">
                <?php foreach ($ticket_assigned_tags as $tt): ?>
                    <span class="ticket-tag-badge"
                        style="background: <?php echo ticket_tag_hex_to_rgba($tt['color'], 0.2); ?>; color: <?php echo $tt['color']; ?>; border: 1px solid <?php echo $tt['color']; ?>; padding: 3px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                        <?php echo htmlspecialchars($tt['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Chat Messages Area -->
        <div class="chat-messages-container" id="chatMessages">

            <div class="chat-divider">
                <span><i class="bi bi-chat-dots"></i>گفتگوها</span>
            </div>

            <?php
            // PERFORMANCE OPTIMIZED: Get all responses and their attachments in batch queries
            // This eliminates N+1 query problem where each response triggered a file query
            // $code_escaped is already defined above

            // Get all responses in one query
            $Query_pasokh = "SELECT * FROM pasokh WHERE code_ticket = '$code_escaped' ORDER BY i_pasokh ASC LIMIT 200";
            $responses = [];
            $response_codes = [];
            if ($Result_pasokh = mysqli_query($Link, $Query_pasokh)) {
                while ($q_pasokh = mysqli_fetch_array($Result_pasokh)) {
                    $responses[] = $q_pasokh;
                    $response_codes[] = "'" . mysqli_real_escape_string($Link, $q_pasokh['code']) . "'";
                }
            }

            // PERFORMANCE: Batch fetch ALL file attachments for this ticket in one query
            $files_by_response = [];
            if (!empty($response_codes)) {
                $response_codes_str = implode(',', $response_codes);
                $Query_all_files = "SELECT * FROM file_pasokh 
                            WHERE code_ticket = '$code_escaped' 
                            AND code_pasokh IN ($response_codes_str) 
                            ORDER BY i_file DESC";
                if ($Result_all_files = mysqli_query($Link, $Query_all_files)) {
                    while ($file_row = mysqli_fetch_array($Result_all_files)) {
                        $pasokh_code = $file_row['code_pasokh'];
                        if (!isset($files_by_response[$pasokh_code])) {
                            $files_by_response[$pasokh_code] = [];
                        }
                        // Limit to 10 files per response
                        if (count($files_by_response[$pasokh_code]) < 10) {
                            $files_by_response[$pasokh_code][] = $file_row;
                        }
                    }
                }
            }

            if (empty($responses)) {
            ?>
                <div class="no-messages">
                    <i class="bi bi-chat-square-text"></i>
                    <p>هنوز پاسخی ثبت نشده است</p>
                    <small>اولین نفری باشید که پاسخ می‌دهید</small>
                </div>
                <?php
            } else {
                foreach ($responses as $q_ticket2) {
                    $code_pasokh = $q_ticket2['code'];

                    // Check if this is a referral message
                    $is_referral = (isset($q_ticket2['kind']) && $q_ticket2['kind'] == 'referral') ||
                        (!empty($q_ticket2['code_karbar2']) && !empty($q_ticket2['name_karbar2']) &&
                            strpos($q_ticket2['matn'], 'مسئول پاسخگویی') !== false);

                    // Check if this is a reopen (status restored) system message
                    $is_reopen = (isset($q_ticket2['kind']) && $q_ticket2['kind'] == 'reopen');
                    $is_cancel = (isset($q_ticket2['kind']) && $q_ticket2['kind'] == 'cancel');
                    $is_done = (isset($q_ticket2['kind']) && $q_ticket2['kind'] == 'done');
                    $is_system_status = $is_reopen || $is_cancel || $is_done;

                    if ($is_system_status) {
                        $icon_sys = $is_reopen ? 'bi-arrow-counterclockwise' : ($is_done ? 'bi-check2-square' : 'bi-x-circle');
                ?>
                        <div class="referral-divider">
                            <span>
                                <i class="bi <?php echo $icon_sys; ?>"></i>
                                <?php echo htmlspecialchars($q_ticket2['matn'], ENT_QUOTES, 'UTF-8'); ?>
                                <span style="margin-right: 6px; color: var(--color-text-muted, var(--bs-secondary-color));">•</span>
                                <?php echo $q_ticket2['tarikh_sabt']; ?> - <?php echo $q_ticket2['saat_sabt']; ?>
                            </span>
                        </div>
                    <?php
                    } elseif ($is_referral) {
                        // Display referral as minimal divider (like chat-divider)
                        $referring_admin = !empty($q_ticket2['name_karbar2']) ? $q_ticket2['name_karbar2'] : 'مدیر سیستم';
                        $assigned_user = !empty($q_ticket2['name_karbar_sabt']) ? $q_ticket2['name_karbar_sabt'] : 'کاربر';
                    ?>

                        <div class="referral-divider">
                            <span>
                                <i class="bi bi-arrow-repeat"></i>
                                درخواست پشتیبانی توسط <strong><?php echo htmlspecialchars($referring_admin); ?></strong> به کاربر
                                پشتیبان <strong><?php echo htmlspecialchars($assigned_user); ?></strong> ارجاع شد
                                <span style="margin-right: 6px; color: var(--color-text-muted, var(--bs-secondary-color));">•</span>
                                <?php echo $q_ticket2['tarikh_sabt']; ?> - <?php echo $q_ticket2['saat_sabt']; ?>
                            </span>
                        </div>

                    <?php
                    } else {
                        // Regular message display
                        // Check if message is from current logged-in user
                        $is_sent = (isset($q_ticket2['code_karbar_sabt']) && $q_ticket2['code_karbar_sabt'] == $_SESSION['code_p']);
                        $wrapper_class = $is_sent ? 'sent' : 'received';

                        // Get first letter of sender name for avatar
                        $avatar_letter = mb_substr($q_ticket2['name_karbar_sabt'], 0, 1, 'UTF-8');
                    ?>

                        <div class="message-wrapper <?php echo $wrapper_class; ?>">
                            <div class="message-avatar">
                                <?php echo $avatar_letter; ?>
                            </div>
                            <div class="message-content">
                                <span class="message-sender"><?php echo $q_ticket2['name_karbar_sabt']; ?></span>
                                <div class="message-bubble">
                                    <p><?php echo nl2br($q_ticket2['matn']); ?></p>

                                    <?php
                                    // PERFORMANCE: Use pre-fetched attachments instead of individual queries
                                    if (isset($files_by_response[$code_pasokh])) {
                                        foreach ($files_by_response[$code_pasokh] as $q_fticket) {
                                    ?>
                                            <a href="files/peyvast/<?php echo htmlspecialchars($q_fticket['code_file'] . "." . $q_fticket['kind']); ?>"
                                                class="message-attachment" target="_blank">
                                                <i class="bi bi-paperclip"></i>
                                                <span><?php echo htmlspecialchars($q_fticket['titr']); ?></span>
                                            </a>
                                    <?php }
                                    } ?>
                                </div>
                                <div class="message-time">
                                    <?php if ($q_ticket2['oksee'] == 'y') { ?>
                                        <i class="bi bi-check2-all message-seen-icon seen" title="خوانده شده"></i>
                                    <?php } else { ?>
                                        <i class="bi bi-check2 message-seen-icon unseen" title="خوانده نشده"></i>
                                    <?php } ?>
                                    <?php echo $q_ticket2['tarikh_sabt']; ?> - <?php echo $q_ticket2['saat_sabt']; ?>
                                </div>
                            </div>
                        </div>

            <?php
                    }
                }
            }
            ?>

        </div>

        <!-- Action Buttons -->
        <?php if (1) { // Access check - keeping original condition 
        ?>
            <div class="action-buttons-row">
                <?php if ($current_ticket['vaziat'] == "k") { ?>
                    <a href="?page=end_ticket&code=<?php echo $code; ?>&kind=b<?php echo $p_param; ?>"
                        class="action-btn action-btn-success">
                        <i class="bi bi-check-circle"></i> بستن تیکت
                    </a>
                <?php } ?>

                <?php if ($current_ticket['vaziat'] == "m" || $current_ticket['vaziat'] == "w" || $current_ticket['vaziat'] == "a") { ?>
                    <a href="?page=anjam_ticket&code=<?php echo $code; ?>&kind=b<?php echo $p_param; ?>"
                        class="action-btn action-btn-success">
                        <i class="bi bi-check2-square"></i> تسک انجام شد
                    </a>
                <?php } ?>

                <?php if ($current_ticket['vaziat'] == "m" && $current_ticket['code_p_karbar_anjam'] == $_SESSION['code_p']) { ?>
                    <a href="?page=set_working_on&code=<?php echo $code; ?><?php echo $p_param; ?>"
                        class="action-btn action-btn-primary">
                        <i class="bi bi-briefcase"></i> روی میز
                    </a>
                <?php } ?>

                <?php if ($current_ticket['vaziat'] == "m" || $current_ticket['vaziat'] == "a") { ?>
                    <a href="?page=end_ticket&code=<?php echo $code; ?>&kind=c<?php echo $p_param; ?>"
                        class="action-btn action-btn-warning">
                        <i class="bi bi-x-circle"></i> کنسل کردن
                    </a>
                <?php } ?>

                <?php if ($current_ticket['vaziat'] == "y") { ?>
                    <a href="?page=end_ticket&code=<?php echo $code; ?>&kind=t<?php echo $p_param; ?>"
                        class="action-btn action-btn-info">
                        <i class="bi bi-arrow-repeat"></i> بررسی مجدد
                    </a>
                <?php } ?>

                <?php if ($current_ticket['vaziat'] == "b") { ?>
                    <a href="?page=s_reopen_ticket&code=<?php echo $code; ?><?php echo $p_param; ?>"
                        class="action-btn action-btn-info" id="btn-reopen-ticket"
                        onclick="return confirm('این عمل موجب تغییر وضعیت تیکت به درحال بررسی و بازگشت آن به کارتابل پشتیبان می شود. آیا از این عمل مطمئن هستید؟');">
                        <i class="bi bi-arrow-counterclockwise"></i> باز کردن مجدد تیکت
                    </a>
                <?php } ?>
            </div>
        <?php } ?>

        <!-- Chat Input Area -->
        <div class="chat-input-container" id="chat-input-container">
            <?php if ($current_ticket['vaziat'] != 'b' && $current_ticket['vaziat'] != 'c') { ?>
                <form method="post" action="?page=s_new_pasokh" enctype="multipart/form-data">
                    <input type="hidden" name="code_ticket" id="code_ticket" value="<?php echo $code; ?>">

                    <div class="chat-input-wrapper">
                        <img src="assets/images/<?php echo $avatar; ?>.png" class="rounded-circle" width="48" height="48" alt=""
                            style="box-shadow: 0 2px 8px var(--color-shadow-md, rgba(0,0,0,0.2));" />

                        <div class="chat-input-main">
                            <textarea name="matn_pasokh" id="matn_pasokh" class="chat-textarea"
                                placeholder="پاسخ خود را اینجا بنویسید..." required></textarea>

                            <div class="chat-input-actions">
                                <div class="chat-file-input">
                                    <label class="chat-file-label">
                                        <i class="bi bi-paperclip"></i>
                                        <span>پیوست فایل</span>
                                        <input name="file_peyvast[]" type="file" id="file_peyvast" multiple />
                                    </label>
                                    <span id="file-name" class="text-muted" style="font-size: 0.85rem;"></span>
                                </div>

                                <div style="display: flex; gap: 5px">
                                    <!-- Saved Replies Dropdown -->
                                    <div class="saved-replies-dropdown">
                                        <button type="button" class="saved-replies-btn" id="savedRepliesBtn">
                                            <i class="bi bi-chat-quote"></i>
                                            <span>پاسخ‌های آماده</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </button>
                                        <div class="saved-replies-menu" id="savedRepliesMenu">
                                            <div class="saved-replies-header">
                                                <i class="bi bi-lightning-charge"></i>
                                                پاسخ‌های آماده
                                            </div>
                                            <div class="saved-reply-item" data-reply="درخواست شما در حال پیگیری است. از صبر و شکیبایی شما سپاسگزاریم.">
                                                <i class="bi bi-clock-history"></i>
                                                <span>در حال پیگیری</span>
                                            </div>
                                            <div class="saved-reply-item" data-reply="جهت پیگیری ادامهٔ فرآیند، درخواست شما به همکاران مربوطه ارجاع خواهد شد. با تشکر.">
                                                <i class="bi bi-arrow-repeat"></i>
                                                <span>ارجاع به همکاران</span>
                                            </div>
                                            <div class="saved-reply-item" data-reply="این تیکت تکراری است و موضوع مرتبط در تیکت دیگری پیگیری می‌شود.">
                                                <i class="bi bi-files"></i>
                                                <span>تیکت تکراری</span>
                                            </div>
                                            <div class="saved-reply-item" data-reply="خواهشمند است اطلاعات زیر را ارسال فرمایید: کد پرسنلی، شماره موبایل، سمت، نام مدیر، کد ملی، وضعیت تأهل و تاریخ تولد.">
                                                <i class="bi bi-info-circle"></i>
                                                <span>درخواست اطلاعات</span>
                                            </div>
                                            <div class="saved-reply-item" data-reply="با تشکر از طرح موضوع، به استحضار می‌رساند مورد مطرح‌شده خارج از حوزه وظایف و مسئولیت‌های این تیم می‌باشد.">
                                                <i class="bi bi-info-circle"></i>
                                                <span>عدم ارتباط</span>
                                            </div>
                                            <div class="saved-reply-item" data-reply="درخواست شما انجام گردید. با تشکر.">
                                                <i class="bi bi-check-circle"></i>
                                                <span>انجام شد</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($bpms_view): ?>

                                        <!-- Saved Replies Dropdown -->
                                        <div class="saved-replies-dropdown">
                                            <button type="button" class="saved-replies-btn" id="savedRepliesBPMSBtn">
                                                <i class="bi bi-chat-quote"></i>
                                                <span>پاسخ های آماده BPMS</span>
                                                <i class="bi bi-chevron-down"></i>
                                            </button>
                                            <div class="saved-replies-menu" id="savedRepliesBPMSMenu">
                                                <div class="saved-replies-header">
                                                    <i class="bi bi-lightning-charge"></i>
                                                    پاسخ های آماده BPMS
                                                </div>
                                                <div class="saved-reply-item" data-reply="✅اعلام پایان کار جریان کار
📅تاریخ اتمام طراحی:
📌نام جریان کار:
🖥️بستر طراحی:
🏢شرکت درخواست‌دهنده:
لیست مجریان:
شناسه جریان کار:
فرآیند آماده استفاده می‌باشد.">
                                                    <i class="bi bi-clock-history"></i>
                                                    <span>فرمت اعلام تحویل</span>
                                                </div>
                                                <div class="saved-reply-item" data-reply="✅اعلام اصلاحیه جریان کار
📌نام جریان کار:
📅تاریخ اتمام اصلاح:
⁉️شرح اصلاح:
🖥️بستر طراحی:
🏢شرکت درخواست‌دهنده:
🎫شماره تیکت درخواست:
فرآیند آماده استفاده می‌باشد.">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                    <span>فرمت اعلام اصلاحیه</span>
                                                </div>
                                                <div class="saved-reply-item" data-reply="باسلام و احترام؛ جریان کار آماده ی تست تیم فرآیند می باشد و در صدگان مربوطه به خانم قدمگاهی اساین داده شد.">
                                                    <i class="bi bi-files"></i>
                                                    <span>تست فرآیند</span>
                                                </div>
                                                <div class="saved-reply-item" data-reply="باسلام و احترام؛ جریان کار آماده ی تست تیم تحویل می باشد و در صدگان مربوطه به خانم دهنوی/دماوندی اساین داده شد.">
                                                    <i class="bi bi-info-circle"></i>
                                                    <span>تست تحویل</span>
                                                </div>
                                                <div class="saved-reply-item" data-reply="باسلام و احترام؛ تقاضا می گردد چارت سازمانی مطابق با فرآیند اعلامی بارگذاری شود.">
                                                    <i class="bi-person-fill"></i>
                                                    <span>درخواست چارت سازمانی</span>
                                                </div>
                                                <div class="saved-reply-item" data-reply="باسلام و احترام؛ لطفا عنوان فرآیند و عنوان مرحله را اعلام فرمایید">
                                                    <i class="bi-phone-fill"></i>
                                                    <span>اطلاعات درخواستی پشتیبانی</span>
                                                </div>
                                                <div class="saved-reply-item" data-reply="باسلام و احترام؛
لطفا ابتدا جدول فرآیند جریان کار مربوطه بررسی گردد، در صورت مطابقت با جدول دسترسی درخواستی لحاظ گردد و در غیر این صورت به واحد فرآیند ارجاع گردد.">
                                                    <i class="bi-table"></i>
                                                    <span>بررسی جدول جریان کار</span>
                                                </div>
                                                <div class="saved-reply-item" data-reply="با سلام و احترام،
با توجه به گذشت مدت‌زمان قابل‌توجه از آخرین پاسخ ارسالی از سوی شما، تیکت به‌صورت خودکار بسته خواهد شد. بدیهی است به‌محض دریافت اولین پاسخ از طرف شما، وضعیت تیکت مجدداً به «در حال بررسی» تغییر خواهد کرد.
منتظر پاسخ شما هستیم.">
                                                    <i class="bi bi-check-circle"></i>
                                                    <span>بیش از یک ماه</span>
                                                </div>
                                                <div class="saved-reply-item" data-reply="باسلام و احترام؛ تغییرات درخواستی شما انجام شد.">
                                                    <i class="bi bi-check-circle"></i>
                                                    <span>اعلام انجام تغییرات</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                <?php if ($can_use_new_user_response): ?>
                                    <div class="saved-replies-dropdown">
                                        <button type="button" class="saved-replies-btn" id="savedRepliesNewUserBtn">
                                            <i class="bi bi-person-plus"></i>
                                            <span>ایجاد کاربر</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </button>
                                        <div class="saved-replies-menu" id="savedRepliesNewUserMenu">
                                            <div class="saved-replies-header">
                                                <i class="bi bi-person-plus"></i>
                                                پاسخ ایجاد کاربر
                                            </div>
                                            <div class="saved-reply-item" data-reply="<?php echo htmlspecialchars($new_user_response_template, ENT_QUOTES, 'UTF-8'); ?>">
                                                <i class="bi bi-person-plus"></i>
                                                <span>اعلام ایجاد دسترسی</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                </div>

                                <button type="submit" class="chat-submit-btn">
                                    <i class="bi bi-send"></i>
                                    ارسال پاسخ
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } else { ?>
                <div class="chat-input-wrapper p-3 text-center text-muted" style="background: var(--color-bg-tertiary, var(--bs-tertiary-bg)); border-radius: 12px;">
                    <i class="bi bi-lock-fill d-block mb-2" style="font-size: 1.5rem;"></i>
                    <span><?php echo ($current_ticket['vaziat'] == 'c') ? 'این تیکت کنسل شده است. امکان ارسال پیام جدید وجود ندارد.' : 'این تیکت بسته شده است. برای ارسال پیام جدید، ابتدا با دکمهٔ «باز کردن مجدد تیکت» وضعیت تیکت را به «در حال بررسی» تغییر دهید.'; ?></span>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
        <?php if ($_SESSION['code_p'] == "24277") { ?>
            <a href="?page=hazf_ticket&code=<?php echo $code; ?><?php echo $p_param; ?>" class="delete-ticket-btn">
                <i class="bi bi-trash"></i>
                حذف کامل این تیکت
            </a>
        <?php } ?>
    </div>

    <!-- Modal for User Assignment -->
    <div class="modal fade" id="selector_mod" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>ارجاع پشتیبانی به
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Filter bar for user search -->
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="userFilter"
                                placeholder="جستجو در لیست کاربران..." autocomplete="off">
                        </div>
                    </div>

                    <div id="userTableContainer" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover m-0" id="userTable">
                            <thead class="sticky-top" style="background: var(--color-bg-tertiary, var(--bs-tertiary-bg));">
                                <tr>
                                    <th style="border-radius: 8px 0 0 0;">نام کاربر</th>
                                    <th style="border-radius: 0 8px 0 0;">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Query_list = "SELECT * from karbar where  vaziat = 'y' ORDER BY code_p DESC LIMIT 300";
                                if ($Result_list = mysqli_query($Link, $Query_list)) {
                                    while ($q_karbar = mysqli_fetch_array($Result_list)) { ?>
                                        <tr class="user-row" data-name="<?php echo strtolower($q_karbar['name']); ?>">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--bs-primary), var(--bs-info)); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: bold;">
                                                        <?php echo mb_substr($q_karbar['name'], 0, 1, 'UTF-8'); ?>
                                                    </div>
                                                    <?php echo $q_karbar['name']; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="?page=erja_ticket&code_ticket=<?php echo $code; ?>&karbar=<?php echo $q_karbar['code_p']; ?><?php echo $p_param; ?>"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-check2"></i> ارجاع
                                                </a>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- No results message -->
                    <div id="noResults" class="text-center text-muted py-4" style="display: none;">
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

    <!-- Modal for Category Change -->
    <?php if ($has_reassign_access) { ?>
        <div class="modal fade" id="category_change_modal" tabindex="-1" aria-labelledby="categoryChangeModalTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-tag me-2"></i>تغییر دسته بندی تیکت
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label text-muted">دسته بندی فعلی:</label>
                            <div class="info-pill" style="display: inline-flex;">
                                <i class="bi bi-folder2"></i>
                                <strong><?php echo $current_ticket['name_daste']; ?></strong>
                                <span class="badge bg-secondary">[<?php echo $current_ticket['daste']; ?>]</span>
                            </div>
                        </div>

                        <form method="get" action="?page=change_category">
                            <input type="hidden" name="page" value="change_category">
                            <input type="hidden" name="code_ticket" value="<?php echo $code; ?>">

                            <div class="mb-4">
                                <label class="form-label" for="daste_new">انتخاب دسته بندی جدید:</label>
                                <select class="form-select" name="daste" id="daste_new" required
                                    style="border-radius: 10px; padding: 12px;">
                                    <option value="">-- انتخاب کنید --</option>
                                    <?php
                                    $Query_dep = "SELECT * from departman where (vaziat = 'y') ORDER BY name ASC LIMIT 200";
                                    if ($Result_dep = mysqli_query($Link, $Query_dep)) {
                                        while ($q_dep = mysqli_fetch_array($Result_dep)) {
                                            $selected = ($q_dep['id'] == $current_ticket['daste']) ? 'selected' : '';
                                    ?>
                                            <option value="<?php echo $q_dep['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo $q_dep['name']; ?>
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="alert"
                                style="background: var(--color-badge-warning-bg, rgba(210, 169, 104, 0.15)); border: 1px solid var(--color-warning, var(--bs-warning)); border-radius: 12px; color: var(--color-warning, var(--bs-warning));">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                توجه: تغییر دسته بندی تیکت در تاریخچه تیکت ثبت خواهد شد.
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                                <button type="submit" class="btn btn-info">
                                    <i class="bi bi-check-circle me-1"></i> تغییر دسته بندی
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- Modal for Tag Management -->
    <?php if ($ticket_tags_available): ?>
        <?php
        $assigned_tag_ids = [];
        foreach ($ticket_assigned_tags as $tt) {
            $assigned_tag_ids[] = $tt['id'];
        }
        ?>
        <div class="modal fade" id="tags_modal" tabindex="-1" aria-labelledby="tagsModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-tags me-2"></i>مدیریت برچسب‌ها
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted" style="font-size: 0.85rem;">
                                برچسب‌های <strong><?php echo htmlspecialchars($current_ticket['code'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </span>
                            <a href="?page=manage_tags" class="btn btn-sm btn-outline-primary rounded-pill px-3" target="_blank">
                                <i class="bi bi-plus-lg me-1"></i> مدیریت برچسب‌ها
                            </a>
                        </div>

                        <?php if (empty($current_user_tags)): ?>
                            <div class="tt-modal-empty">
                                <i class="bi bi-tag"></i>
                                <p>هنوز برچسبی ایجاد نکرده‌اید.</p>
                                <a href="?page=manage_tags" target="_blank" class="btn btn-primary btn-sm mt-2">
                                    <i class="bi bi-plus-lg me-1"></i> ایجاد برچسب جدید
                                </a>
                            </div>
                        <?php else: ?>
                            <div id="tagManagementList">
                                <?php foreach ($current_user_tags as $tag): 
                                    $is_active = in_array($tag['id'], $assigned_tag_ids);
                                ?>
                                    <div class="tt-modal-tag-item <?php echo $is_active ? 'active' : ''; ?>"
                                        data-tag-id="<?php echo $tag['id']; ?>"
                                        data-ticket-code="<?php echo htmlspecialchars($code, ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="tt-modal-tag-color" style="background: <?php echo htmlspecialchars($tag['color'], ENT_QUOTES, 'UTF-8'); ?>;"></div>
                                        <span class="tt-modal-tag-title"><?php echo htmlspecialchars($tag['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <div class="tt-modal-tag-check">
                                            <?php if ($is_active): ?>
                                                <i class="bi bi-check text-white"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Mark responses as read when viewing the ticket
    // IMPORTANT: Only mark as read if user is the ticket creator OR the assigned handler
    // Admin users can view all tickets but should not mark messages as read unless they are creator/handler
    $code_ghl = $_SESSION['code_p'];
    $code_ghl_escaped = mysqli_real_escape_string($Link, $code_ghl);

    // Check if current user is the ticket creator
    $is_ticket_creator = false;
    if ($current_ticket !== null && isset($current_ticket['code_p_karbar']) && $current_ticket['code_p_karbar'] == $code_ghl) {
        $is_ticket_creator = true;
    }

    // Check if current user is the assigned handler
    $is_assigned_handler = false;
    if ($current_ticket !== null && isset($current_ticket['code_p_karbar_anjam']) && $current_ticket['code_p_karbar_anjam'] == $code_ghl) {
        $is_assigned_handler = true;
    }

    // Only mark messages as read if user is the creator OR the assigned handler
    // This prevents admins from marking messages as read when just viewing others' tickets
    if ($is_ticket_creator || $is_assigned_handler) {
        if ($is_ticket_creator) {
            // If user is the ticket creator, mark ALL unread responses as read
            $Qery = "UPDATE `pasokh` SET
       `oksee` = 'y',
        `tarikh_see` = '$tarikh',
         `saat_see` = '$saat'
       WHERE (`code_ticket` ='$code_escaped' AND oksee = 'n'); ";
        } else {
            // If user is the assigned handler (but not creator), mark responses directed to them
            $Qery = "UPDATE `pasokh` SET
       `oksee` = 'y',
        `tarikh_see` = '$tarikh',
         `saat_see` = '$saat'
       WHERE (`code_ticket` ='$code_escaped' AND oksee = 'n' AND (code_karbar2 = '$code_ghl_escaped' || code_karbar2 IS NULL || code_karbar2 = '')); ";
        }

        if ($Link->query($Qery) === TRUE) {
            // Successfully marked as read
        }
    }
    // If user is neither creator nor assigned handler (e.g., admin just viewing), do nothing
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User filter functionality
            const userFilter = document.getElementById('userFilter');
            const userRows = document.querySelectorAll('.user-row');
            const noResults = document.getElementById('noResults');
            const userTableContainer = document.getElementById('userTableContainer');

            if (userFilter) {
                userFilter.addEventListener('input', function() {
                    const filterValue = this.value.toLowerCase().trim();
                    let visibleCount = 0;

                    userRows.forEach(function(row) {
                        const userName = row.getAttribute('data-name');
                        const isVisible = userName.includes(filterValue);

                        if (isVisible) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show/hide no results message
                    if (visibleCount === 0 && filterValue !== '') {
                        noResults.style.display = 'block';
                        userTableContainer.style.display = 'none';
                    } else {
                        noResults.style.display = 'none';
                        userTableContainer.style.display = 'block';
                    }
                });

                // Clear filter when modal is closed
                const modal = document.getElementById('selector_mod');
                if (modal) {
                    modal.addEventListener('hidden.bs.modal', function() {
                        userFilter.value = '';
                        userRows.forEach(function(row) {
                            row.style.display = '';
                        });
                        noResults.style.display = 'none';
                        userTableContainer.style.display = 'block';
                    });
                }

                const adduserModal = document.getElementById('add_extra');
                if (adduserModal) {
                    modal.addEventListener('hidden.bs.modal', function() {
                        userFilter.value = '';
                        userRows.forEach(function(row) {
                            row.style.display = '';
                        });
                        noResults.style.display = 'none';
                        userTableContainer.style.display = 'block';
                    });
                }
            }

            // File input display
            const fileInput = document.getElementById('file_peyvast');
            const fileNameDisplay = document.getElementById('file-name');
            
                        if (fileInput && fileNameDisplay) {
                            fileInput.addEventListener('change', function() {

                if (this.files.length === 0) {
                    fileNameDisplay.textContent = '';
                    return;
                }

                let names = [];

                for (let i = 0; i < this.files.length; i++) {
                    names.push(this.files[i].name);
                }

                fileNameDisplay.textContent = names.join(' , ');
            });

                            
}

            
            // Auto-scroll to bottom to show latest messages
            const chatMessages = document.getElementById('chatMessages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Auto-resize textarea
            const textarea = document.getElementById('matn_pasokh');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 200) + 'px';
                });
            }

            function autoResizeTextarea(el) {
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 200) + 'px';
            }

            document.querySelectorAll('.saved-replies-dropdown').forEach(dropdown => {

                const btn = dropdown.querySelector('.saved-replies-btn');
                const items = dropdown.querySelectorAll('.saved-reply-item');

                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // close others
                    document.querySelectorAll('.saved-replies-dropdown.open').forEach(d => {
                        if (d !== dropdown) d.classList.remove('open');
                    });

                    dropdown.classList.toggle('open');
                });

                items.forEach(item => {
                    item.addEventListener('click', function() {
                        const reply = this.getAttribute('data-reply');
                        if (textarea && reply) {
                            textarea.value += (textarea.value ? '\n' : '') + reply.trim();
                            textarea.focus();
                            autoResizeTextarea(textarea);
                        }
                        dropdown.classList.remove('open');
                    });
                });
            });

            document.addEventListener('click', () => {
                document
                    .querySelectorAll('.saved-replies-dropdown.open')
                    .forEach(d => d.classList.remove('open'));
            });

            // Tag Management: Toggle tag assignment via AJAX
            var tagManagementList = document.getElementById('tagManagementList');
            if (tagManagementList) {
                tagManagementList.addEventListener('click', function(ev) {
                    var item = ev.target.closest('.tt-modal-tag-item');
                    if (!item) return;

                    var tagId = item.getAttribute('data-tag-id');
                    var ticketCode = item.getAttribute('data-ticket-code');
                    if (!tagId || !ticketCode) return;

                    // Optimistic UI update
                    var wasActive = item.classList.contains('active');
                    item.classList.toggle('active');
                    var checkEl = item.querySelector('.tt-modal-tag-check');
                    if (checkEl) {
                        if (wasActive) {
                            checkEl.innerHTML = '';
                        } else {
                            checkEl.innerHTML = '<i class="bi bi-check text-white"></i>';
                        }
                    }

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '?page=toggle_tag_assignment', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.onload = function() {
                        if (xhr.status !== 200) {
                            // Revert on error
                            item.classList.toggle('active');
                            if (checkEl) {
                                checkEl.innerHTML = wasActive ? '<i class="bi bi-check text-white"></i>' : '';
                            }
                        }
                    };
                    xhr.send('tag_id=' + encodeURIComponent(tagId) + '&ticket_code=' + encodeURIComponent(ticketCode));
                });
            }
        });
    </script>

<?php
} else {
    // No ticket found
    echo '<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    تیکت مورد نظر یافت نشد.
  </div>';
}
?>