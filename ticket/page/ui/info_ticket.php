<?php

$Query_ticket = "SELECT*from ticket where (code = '$code' )ORDER BY i_ticket DESC LIMIT 2";
$current_ticket = null;
if ($Result_ticket = mysqli_query($Link, $Query_ticket)) {
  while ($q_ticket = mysqli_fetch_array($Result_ticket)) {
    if ($current_ticket === null) {
      $current_ticket = $q_ticket;
    }
  }
}

// Preserve 'p' parameter for links
$p_param = isset($_GET['p']) ? '&p=' . htmlspecialchars($_GET['p']) : '';

if ($current_ticket !== null) {
?>

<style>
/* Modern Chat Interface Styles */
.ticket-chat-container {
  display: flex;
  flex-direction: column;
  background: var(--bs-gray-100);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

/* Header Section */
.ticket-header {
  background: linear-gradient(135deg, var(--bs-gray-200) 0%, var(--bs-gray-100) 100%);
  padding: 20px 24px;
  border-bottom: 1px solid var(--bs-gray-300);
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
  background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: var(--bs-white);
  font-weight: bold;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(60, 146, 177, 0.4);
}

.ticket-title-section h4 {
  margin: 0 0 4px 0;
  color: var(--bs-white);
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
  color: var(--bs-gray-500);
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

.ticket-status.status-a { background: rgba(191, 122, 106, 0.2); color: var(--bs-danger); border: 1px solid var(--bs-danger); }
.ticket-status.status-m { background: rgba(111, 180, 206, 0.2); color: var(--bs-info); border: 1px solid var(--bs-info); }
.ticket-status.status-w { background: rgba(60, 146, 177, 0.2); color: var(--bs-primary); border: 1px solid var(--bs-primary); }
.ticket-status.status-b { background: rgba(169, 189, 122, 0.2); color: var(--bs-success); border: 1px solid var(--bs-success); }
.ticket-status.status-k { background: rgba(210, 169, 104, 0.2); color: var(--bs-warning); border: 1px solid var(--bs-warning); }
.ticket-status.status-t { background: rgba(210, 169, 104, 0.2); color: var(--bs-warning); border: 1px solid var(--bs-warning); }
.ticket-status.status-c { background: rgba(80, 89, 106, 0.2); color: var(--bs-secondary); border: 1px solid var(--bs-secondary); }

.ticket-priority {
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.priority-1 { background: var(--bs-danger); color: var(--bs-white); }
.priority-2 { background: var(--bs-warning); color: var(--bs-dark); }
.priority-3 { background: var(--bs-info); color: var(--bs-white); }
.priority-4 { background: var(--bs-secondary); color: var(--bs-white); }

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
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

/* Info Panel */
.ticket-info-panel {
  background: var(--bs-gray-200);
  padding: 16px 24px;
  border-bottom: 1px solid var(--bs-gray-300);
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
  background: var(--bs-gray-100);
  border-radius: 12px;
  font-size: 0.85rem;
  color: var(--bs-gray-600);
  transition: all 0.2s ease;
}

.info-pill:hover {
  background: var(--bs-gray-300);
}

.info-pill i {
  color: var(--bs-primary);
  font-size: 1rem;
}

.info-pill strong {
  color: var(--bs-white);
  margin-right: 4px;
}

/* Chat Messages Area */
.chat-messages-container {
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  background: var(--bs-gray-100);
  max-height: none;
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
  color: var(--bs-white);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.message-wrapper.sent .message-avatar {
  background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
}

.message-wrapper.received .message-avatar {
  background: linear-gradient(135deg, var(--bs-secondary), var(--bs-gray-400));
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
  color: var(--bs-gray-500);
  padding: 0 8px;
}

.message-wrapper.sent .message-sender {
  color: var(--bs-info);
  text-align: right;
}

.message-wrapper.received .message-sender {
  text-align: right;
}

.message-bubble {
  padding: 14px 18px;
  border-radius: 18px;
  position: relative;
  word-wrap: break-word;
  line-height: 1.5;
  font-size: 0.95rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.message-wrapper.sent .message-bubble {
  background: linear-gradient(135deg, var(--bs-primary), rgba(60, 146, 177, 0.8));
  color: var(--bs-white);
  border-bottom-right-radius: 18px;
  border-bottom-left-radius: 4px;
}

.message-wrapper.received .message-bubble {
  background: var(--bs-gray-200);
  color: var(--bs-gray-700);
  border-bottom-left-radius: 18px;
  border-bottom-right-radius: 4px;
  border: 1px solid var(--bs-gray-300);
}

.message-bubble p {
  margin: 0;
}

.message-attachment {
  margin-top: 10px;
  padding: 10px 14px;
  background: rgba(0, 0, 0, 0.15);
  border-radius: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  color: inherit;
  transition: background 0.2s ease;
}

.message-wrapper.received .message-attachment {
  background: var(--bs-gray-100);
}

.message-attachment:hover {
  background: rgba(0, 0, 0, 0.25);
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
  color: var(--bs-gray-500);
  padding: 0 8px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.message-wrapper.sent .message-time {
  justify-content: flex-start;
}

.message-wrapper.received .message-time {
  justify-content: flex-end;
}

.message-seen-icon {
  font-size: 0.85rem;
}

.message-seen-icon.seen {
  color: var(--bs-info);
}

.message-seen-icon.unseen {
  color: var(--bs-gray-500);
}

.original-ticket-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 1px dashed var(--bs-gray-300);
}

.original-ticket-badge {
  background: var(--bs-primary);
  color: var(--bs-white);
  padding: 4px 12px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 600;
}

.original-ticket-title {
  font-size: 1rem;
  font-weight: 600;
  color: var(--bs-white);
  margin: 0;
}

/* Chat Input Area */
.chat-input-container {
  background: var(--bs-gray-200);
  padding: 20px 24px;
  border-top: 1px solid var(--bs-gray-300);
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
  border: 2px solid var(--bs-gray-300);
  border-radius: 16px;
  background: var(--bs-gray-100);
  color: var(--bs-white);
  font-size: 0.95rem;
  resize: none;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.chat-textarea:focus {
  outline: none;
  border-color: var(--bs-primary);
  box-shadow: 0 0 0 3px rgba(60, 146, 177, 0.2);
}

.chat-textarea::placeholder {
  color: var(--bs-gray-500);
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
  background: var(--bs-gray-300);
  border-radius: 10px;
  color: var(--bs-gray-600);
  cursor: pointer;
  font-size: 0.85rem;
  transition: all 0.2s ease;
}

.chat-file-label:hover {
  background: var(--bs-gray-400);
  color: var(--bs-white);
}

.chat-file-label i {
  font-size: 1.1rem;
}

.chat-file-label input {
  display: none;
}

.chat-submit-btn {
  padding: 12px 28px;
  background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
  border: none;
  border-radius: 12px;
  color: var(--bs-white);
  font-size: 0.85rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s ease;
  box-shadow: 0 4px 12px rgba(60, 146, 177, 0.3);
}

.chat-submit-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(60, 146, 177, 0.4);
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
  background: var(--bs-gray-200);
  border-top: 1px solid var(--bs-gray-300);
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
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.action-btn-success {
  background: var(--bs-success);
  color: var(--bs-dark);
}

.action-btn-primary {
  background: var(--bs-primary);
  color: var(--bs-white);
}

.action-btn-warning {
  background: var(--bs-warning);
  color: var(--bs-dark);
}

.action-btn-info {
  background: var(--bs-info);
  color: var(--bs-white);
}

.action-btn-danger {
  background: var(--bs-danger);
  color: var(--bs-white);
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
  background: var(--bs-gray-300);
}

.chat-divider span {
  font-size: 0.75rem;
  color: var(--bs-gray-500);
  padding: 4px 12px;
  background: var(--bs-gray-200);
  border-radius: 12px;
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
  background: var(--bs-gray-300);
}

.referral-divider span {
  font-size: 0.75rem;
  color: var(--bs-gray-500);
  padding: 4px 12px;
  background: var(--bs-gray-200);
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.referral-divider span i {
  color: var(--bs-warning);
  font-size: 0.85rem;
}

.referral-divider span strong {
  color: var(--bs-warning);
  font-weight: 600;
  margin: 0 3px;
}

/* Empty State */
.no-messages {
  text-align: center;
  padding: 40px;
  color: var(--bs-gray-500);
}

.no-messages i {
  font-size: 3rem;
  margin-bottom: 16px;
  opacity: 0.5;
}

/* Modal Enhancements */
.modal-content {
  background: var(--bs-gray-200);
  border: 1px solid var(--bs-gray-300);
  border-radius: 16px;
}

.modal-header {
  border-bottom: 1px solid var(--bs-gray-300);
  padding: 20px 24px;
}

.modal-body {
  padding: 24px;
}

.modal-footer {
  border-top: 1px solid var(--bs-gray-300);
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
  color: var(--bs-gray-500);
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: var(--bs-gray-200);
  border-radius: 8px;
  margin-top: 16px;
}

/* Delete Button */
.delete-ticket-btn {
  background: var(--bs-danger);
  color: var(--bs-white);
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
  background: #a5685a;
  color: var(--bs-white);
  transform: translateY(-2px);
}
</style>

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
      <strong><?php echo $current_ticket['tarikh_anjam']; ?> - <?php echo $current_ticket['saat_anjam']; ?></strong>
    </div>
              <?php } ?>
            </div>
  
  <!-- Chat Messages Area -->
  <div class="chat-messages-container" id="chatMessages">
    
    <div class="chat-divider">
      <span><i class="bi bi-chat-dots"></i>گفتگوها</span>
          </div>
    
    <?php
    // Get all responses
    $Query_pasokh = "SELECT * from pasokh where (code_ticket = '$code') ORDER BY i_pasokh ASC LIMIT 200";
    $responses = [];
    if ($Result_pasokh = mysqli_query($Link, $Query_pasokh)) {
      while ($q_pasokh = mysqli_fetch_array($Result_pasokh)) {
        $responses[] = $q_pasokh;
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
        
        if ($is_referral) {
          // Display referral as minimal divider (like chat-divider)
          $referring_admin = !empty($q_ticket2['name_karbar2']) ? $q_ticket2['name_karbar2'] : 'مدیر سیستم';
          $assigned_user = !empty($q_ticket2['name_karbar_sabt']) ? $q_ticket2['name_karbar_sabt'] : 'کاربر';
    ?>
    
    <div class="referral-divider">
      <span>
        <i class="bi bi-arrow-repeat"></i>
        درخواست پشتیبانی توسط <strong><?php echo htmlspecialchars($referring_admin); ?></strong> به کاربر پشتیبان <strong><?php echo htmlspecialchars($assigned_user); ?></strong> ارجاع شد
        <span style="margin-right: 6px; color: var(--bs-gray-400);">•</span>
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
          // Get attachments for this response
          $Query_fpasokh = "SELECT * from file_pasokh where (code_ticket = '$code' AND code_pasokh = '$code_pasokh') ORDER BY i_file DESC LIMIT 10";
          if ($Result_fpasokh = mysqli_query($Link, $Query_fpasokh)) {
            while ($q_fticket = mysqli_fetch_array($Result_fpasokh)) {
          ?>
            <a href="../files/peyvast/<?php echo $q_fticket['code_file'] . "." . $q_fticket['kind']; ?>" class="message-attachment">
              <i class="bi bi-paperclip"></i>
              <span><?php echo $q_fticket['titr']; ?></span>
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
  <?php if (1) { // Access check - keeping original condition ?>
  <div class="action-buttons-row">
    <?php if ($current_ticket['vaziat'] == "k") { ?>
      <a href="?page=end_ticket&code=<?php echo $code; ?>&kind=b<?php echo $p_param; ?>" class="action-btn action-btn-success">
        <i class="bi bi-check-circle"></i> بستن تیکت
                    </a>
                  <?php } ?>
    
    <?php if ($current_ticket['vaziat'] == "m" || $current_ticket['vaziat'] == "w") { ?>
      <a href="?page=anjam_ticket&code=<?php echo $code; ?>&kind=b<?php echo $p_param; ?>" class="action-btn action-btn-success">
        <i class="bi bi-check2-square"></i> تسک انجام شد
                    </a>
                  <?php } ?>
    
    <?php if ($current_ticket['vaziat'] == "m" && $current_ticket['code_p_karbar_anjam'] == $_SESSION['code_p']) { ?>
      <a href="?page=set_working_on&code=<?php echo $code; ?><?php echo $p_param; ?>" class="action-btn action-btn-primary">
        <i class="bi bi-briefcase"></i> روی میز
                    </a>
                  <?php } ?>
    
    <?php if ($current_ticket['vaziat'] == "m") { ?>
      <a href="?page=end_ticket&code=<?php echo $code; ?>&kind=c<?php echo $p_param; ?>" class="action-btn action-btn-warning">
        <i class="bi bi-x-circle"></i> کنسل کردن
                    </a>
                  <?php } ?>
    
    <?php if ($current_ticket['vaziat'] == "y") { ?>
      <a href="?page=end_ticket&code=<?php echo $code; ?>&kind=t<?php echo $p_param; ?>" class="action-btn action-btn-info">
        <i class="bi bi-arrow-repeat"></i> بررسی مجدد
                    </a>
                  <?php } ?>
                </div>
  <?php } ?>
  
  <!-- Chat Input Area -->
  <div class="chat-input-container">
    <form method="post" action="?page=s_new_pasokh" enctype="multipart/form-data">
      <input type="hidden" name="code_ticket" id="code_ticket" value="<?php echo $code; ?>">
      
      <div class="chat-input-wrapper">
        <img src="../assets/images/karbar.png" class="rounded-circle" width="48" height="48" alt="" style="box-shadow: 0 2px 8px rgba(0,0,0,0.2);" />
        
        <div class="chat-input-main">
          <textarea name="matn_pasokh" id="matn_pasokh" class="chat-textarea" placeholder="پاسخ خود را اینجا بنویسید..." required></textarea>
          
          <div class="chat-input-actions">
            <div class="chat-file-input">
              <label class="chat-file-label">
                                <i class="bi bi-paperclip"></i>
                <span>پیوست فایل</span>
                <input name="file_peyvast" type="file" id="file_peyvast" />
              </label>
              <span id="file-name" class="text-muted" style="font-size: 0.85rem;"></span>
                          </div>
            
            <button type="submit" class="chat-submit-btn">
              <i class="bi bi-send"></i>
              ارسال پاسخ
            </button>
                        </div>
                      </div>
                    </div>
    </form>
                  </div>
                </div>

<!-- Footer Info -->
<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
  <div class="visit-badge">
    <i class="bi bi-eye"></i>
    ثبت بازدید
      </div>

  <?php if ($_SESSION['code_p'] == "24277") { ?>
    <a href="?page=hazf_ticket&code=<?php echo $code; ?><?php echo $p_param; ?>" class="delete-ticket-btn">
      <i class="bi bi-trash"></i>
      حذف کامل این تیکت
    </a>
    <?php } ?>
    </div>

<?php
// Update visit records
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
         WHERE (`code_ticket` ='$code' AND oksee = 'n'); ";
    } else {
        // If user is the assigned handler (but not creator), mark responses directed to them
        $Qery = "UPDATE `pasokh` SET
         `oksee` = 'y',
          `tarikh_see` = '$tarikh',
           `saat_see` = '$saat'
         WHERE (`code_ticket` ='$code' AND oksee = 'n' AND (code_karbar2 = '$code_ghl_escaped' || code_karbar2 IS NULL || code_karbar2 = '')); ";
    }

    if ($Link->query($Qery) === TRUE) {	
        // Successfully marked as read
    }
}
// If user is neither creator nor assigned handler (e.g., admin just viewing), do nothing
?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
  // File input display
  const fileInput = document.getElementById('file_peyvast');
  const fileNameDisplay = document.getElementById('file-name');
  
  if (fileInput && fileNameDisplay) {
    fileInput.addEventListener('change', function() {
      if (this.files.length > 0) {
        fileNameDisplay.textContent = this.files[0].name;
      } else {
        fileNameDisplay.textContent = '';
      }
    });
  }

  // No auto-scroll needed since all messages are visible
    
    // Auto-resize textarea
    const textarea = document.getElementById('matn_pasokh');
    if (textarea) {
      textarea.addEventListener('input', function() {
        this.style.height = 'auto';
      this.style.height = Math.min(this.scrollHeight, 200) + 'px';
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
