<?php
$categoryFilter = str_g('category');
// Only show specific departments
$allowedDepartments = ['new_software', 'IT_Network', 'process', 'BPMS', 'BI', 'payesh'];
$departmentsPlaceholder = "'" . implode("','", array_map(function($dept) use ($Link) {
    return mysqli_real_escape_string($Link, $dept);
}, $allowedDepartments)) . "'";

$categoryQuery = "SELECT id, name FROM departman WHERE (vaziat = 'y' AND id IN ($departmentsPlaceholder)) ORDER BY name ASC LIMIT 200";
$categories = [];
$categoryCounts = [];

if ($resultCategories = mysqli_query($Link, $categoryQuery)) {
    while ($row = mysqli_fetch_assoc($resultCategories)) {
        $categories[] = $row;
        $categoryId = $row['id'];
        $categoryCounts[$categoryId] = 0;
    }
    mysqli_free_result($resultCategories);
}

if ($categoryFilter === '0' || $categoryFilter === '' || !in_array($categoryFilter, array_column($categories, 'id'))) {
    $categoryFilter = !empty($categories) ? $categories[0]['id'] : '';
}

// Count tickets with status 'a' or 'm' for each category
$countQuery = "SELECT daste, COUNT(*) as count FROM ticket WHERE (vaziat IN ('a', 'm')) GROUP BY daste";
if ($resultCounts = mysqli_query($Link, $countQuery)) {
    while ($row = mysqli_fetch_assoc($resultCounts)) {
        $catId = $row['daste'];
        if (isset($categoryCounts[$catId])) {
            $categoryCounts[$catId] = (int)$row['count'];
        }
    }
    mysqli_free_result($resultCounts);
}

// Show tickets with status 'a' or 'm', limit to last 20 per category
$ticketsQuery = "SELECT code, titr, name_karbar, name_sherkat, name_daste, daste, tarikh_sabt, saat_sabt, olaviat, vaziat, IFNULL(priority_status, 'n') AS priority_status, priority_order FROM ticket WHERE (vaziat IN ('a', 'm')) " . (!empty($categoryFilter) ? "AND daste = '" . mysqli_real_escape_string($Link, $categoryFilter) . "'" : "") . " ORDER BY priority_status DESC, priority_order ASC, i_ticket DESC LIMIT 20";

$prioritizedTickets = [];
$unprioritizedTickets = [];

if ($resultTickets = mysqli_query($Link, $ticketsQuery)) {
    while ($row = mysqli_fetch_assoc($resultTickets)) {
        $status = $row['priority_status'] === 'y' ? 'y' : 'n';
        if ($status === 'y') {
            $prioritizedTickets[] = $row;
        } else {
            $unprioritizedTickets[] = $row;
        }
    }
    mysqli_free_result($resultTickets);
}

usort($prioritizedTickets, function ($a, $b) {
    $orderA = isset($a['priority_order']) ? (int)$a['priority_order'] : PHP_INT_MAX;
    $orderB = isset($b['priority_order']) ? (int)$b['priority_order'] : PHP_INT_MAX;

    if ($orderA === $orderB) {
        $timeA = ($a['tarikh_sabt'] ?? '') . ($a['saat_sabt'] ?? '');
        $timeB = ($b['tarikh_sabt'] ?? '') . ($b['saat_sabt'] ?? '');
        return strcmp($timeA, $timeB);
    }

    return $orderA <=> $orderB;
});

usort($unprioritizedTickets, function ($a, $b) {
    $timeA = ($a['tarikh_sabt'] ?? '') . ($a['saat_sabt'] ?? '');
    $timeB = ($b['tarikh_sabt'] ?? '') . ($b['saat_sabt'] ?? '');
    return strcmp($timeB, $timeA);
});

$priorityLabels = [
    '1' => ['label' => 'ضروری', 'class' => 'danger'],
    '2' => ['label' => 'متوسط', 'class' => 'warning'],
    '3' => ['label' => 'معمولی', 'class' => 'info'],
    '4' => ['label' => 'پایین', 'class' => 'secondary']
];

$selectedCategory = $categoryFilter;
?>

<style>
    :root {
        --view-surface: #1c1f21;
        --view-border: rgba(148, 163, 184, 0.22);
        --view-shadow: 0 26px 48px -20px rgba(8, 15, 30, 0.8);
        --view-accent: #2563eb;
        --view-accent-soft: rgba(37, 99, 235, 0.25);
        --view-text: #f9fafb;
        --view-muted: rgba(209, 213, 219, 0.68);
    }

    .view-wrapper {
        min-height: 420px;
    }

    .view-container {
        min-height: 240px;
        border: 1px solid var(--view-border);
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(17, 24, 39, 0.85), rgba(28, 31, 33, 0.78));
        padding: 16px;
    }

    .view-card {
        border-radius: 12px;
        border: 1px solid var(--view-border);
        background: linear-gradient(160deg, rgba(32, 39, 50, 0.95), rgba(17, 23, 34, 0.95));
        box-shadow: var(--view-shadow);
        margin-bottom: 12px;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        color: var(--view-text);
    }

    .view-card:last-child {
        margin-bottom: 0;
    }

    .view-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 26px 38px -18px rgba(37, 99, 235, 0.55);
        border-color: rgba(59, 130, 246, 0.45);
    }

    .view-meta {
        font-size: 0.78rem;
        color: var(--view-muted);
    }

    .view-meta span + span::before {
        content: "•";
        margin: 0 4px;
        color: rgba(129, 140, 248, 0.55);
    }

    .view-card .badge.border {
        background: rgba(17, 24, 39, 0.7);
        border-width: 1px;
    }

    .view-card .badge.border.text-secondary {
        color: var(--view-muted) !important;
        border-color: rgba(148, 163, 184, 0.45) !important;
    }

    .view-card .badge.border.text-primary {
        color: #93c5fd !important;
        border-color: rgba(59, 130, 246, 0.5) !important;
    }

    .view-card .badge.border.text-success {
        color: #6ee7b7 !important;
        border-color: rgba(16, 185, 129, 0.45) !important;
    }

    .view-card .badge.border.text-info {
        color: #67e8f9 !important;
        border-color: rgba(8, 145, 178, 0.45) !important;
    }

    .view-card .badge.bg-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 8px 18px -10px rgba(37, 99, 235, 0.66);
    }

    .view-card .badge.bg-danger {
        box-shadow: 0 8px 18px -10px rgba(239, 68, 68, 0.6);
    }

    .view-card .badge.bg-warning {
        box-shadow: 0 8px 18px -10px rgba(251, 191, 36, 0.5);
        color: #1c1917;
    }

    .view-badge {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .view-card .text-muted {
        color: rgba(229, 231, 235, 0.6) !important;
    }

    .view-empty {
        text-align: center;
        padding: 32px 16px;
        color: rgba(148, 163, 184, 0.8);
        font-size: 0.9rem;
    }

    .category-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .category-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.35);
        background: linear-gradient(135deg, rgba(17, 24, 39, 0.85), rgba(28, 31, 33, 0.78));
        color: var(--view-text);
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
    }

    .category-chip:hover {
        border-color: rgba(59, 130, 246, 0.5);
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.25), rgba(17, 24, 39, 0.85));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.4);
    }

    .category-chip.active {
        border-color: var(--view-accent);
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.35), rgba(17, 24, 39, 0.85));
        box-shadow: 0 4px 14px -4px rgba(37, 99, 235, 0.55);
    }

    .category-chip .count-badge {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        min-width: 20px;
        text-align: center;
    }

    .category-chip.active .count-badge {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        box-shadow: 0 2px 8px -2px rgba(37, 99, 235, 0.6);
    }

    @media (max-width: 767.98px) {
        .view-container {
            min-height: 120px;
        }
        
        .category-chips {
            gap: 6px;
        }
        
        .category-chip {
            padding: 6px 12px;
            font-size: 0.8125rem;
        }
    }
</style>

<div class="row gx-3">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="mb-2">مشاهده اولویت تیکت ها</h5>
                    <p class="text-muted mb-3 small">تیکت های در وضعیت ثبت اولیه و در حال بررسی نمایش داده می‌شوند (حداکثر 20 تیکت از هر دسته). این صفحه فقط برای مشاهده است و امکان تغییر اولویت وجود ندارد.</p>
                </div>
                <div class="category-chips">
                    <?php foreach ($categories as $category): ?>
                        <?php
                        $catId = $category['id'];
                        $catName = $category['name'];
                        $count = isset($categoryCounts[$catId]) ? $categoryCounts[$catId] : 0;
                        $isActive = $selectedCategory === $catId;
                        ?>
                        <a href="?page=priority&category=<?php echo htmlspecialchars($catId, ENT_QUOTES, 'UTF-8'); ?>" 
                           class="category-chip <?php echo $isActive ? 'active' : ''; ?>" 
                           data-category-id="<?php echo htmlspecialchars($catId, ENT_QUOTES, 'UTF-8'); ?>">
                            <span><?php echo htmlspecialchars($catName, ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="count-badge"><?php echo $count; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gx-3 view-wrapper">
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill mb-3">
            <div class="card-header border-0 bg-transparent d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">اولویت بندی نشده</h6>
                    <span class="badge rounded-pill border border-secondary text-secondary bg-transparent fw-semibold"><?php echo count($unprioritizedTickets); ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="view-container">
                    <?php if (empty($unprioritizedTickets)): ?>
                        <div class="view-empty">تیکتی برای نمایش وجود ندارد.</div>
                    <?php else: ?>
                        <?php foreach ($unprioritizedTickets as $ticket): ?>
                            <?php
                            $categoryId = $ticket['daste'] ?? '';
                            $categoryName = $ticket['name_daste'] ?? '---';
                            $priority = $ticket['olaviat'] ?? '';
                            $vaziat = $ticket['vaziat'] ?? '';
                            $priorityMeta = $priorityLabels[$priority] ?? null;
                            $vaziatLabel = ($vaziat == 'a') ? 'ثبت اولیه' : (($vaziat == 'm') ? 'در حال بررسی' : (($vaziat == 'w') ? 'روی میز' : ''));
                            $vaziatClass = ($vaziat == 'a') ? 'danger' : (($vaziat == 'm') ? 'info' : (($vaziat == 'w') ? 'primary' : 'secondary'));
                            ?>
                            <div class="view-card ticket-card-clickable" data-ticket-code="<?php echo htmlspecialchars($ticket['code'], ENT_QUOTES, 'UTF-8'); ?>" data-category-id="<?php echo htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold"><?php echo htmlspecialchars($ticket['titr'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <span class="view-meta mt-1">
                                                <span>کد: <?php echo htmlspecialchars($ticket['code'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                <span><?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></span>
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <?php if ($vaziatLabel): ?>
                                                <span class="badge bg-<?php echo $vaziatClass; ?> view-badge mb-1 d-block"><?php echo htmlspecialchars($vaziatLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php endif; ?>
                                            <?php if ($priorityMeta): ?>
                                                <span class="badge bg-<?php echo $priorityMeta['class']; ?> view-badge">اولویت: <?php echo htmlspecialchars($priorityMeta['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php else: ?>
                                                <span class="badge border border-secondary text-secondary view-badge">اولویت تعریف نشده</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge border border-primary text-primary view-badge">درخواست دهنده: <?php echo htmlspecialchars($ticket['name_karbar'] ?? '---', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="badge border border-info text-info view-badge">شرکت: <?php echo htmlspecialchars($ticket['name_sherkat'] ?? '---', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="text-muted small">ثبت: <?php echo htmlspecialchars(($ticket['tarikh_sabt'] ?? '') . ' - ' . ($ticket['saat_sabt'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill mb-3">
            <div class="card-header border-0 bg-transparent d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">اولویت بندی شده</h6>
                    <span class="badge rounded-pill bg-primary text-white fw-semibold"><?php echo count($prioritizedTickets); ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="view-container">
                    <?php if (empty($prioritizedTickets)): ?>
                        <div class="view-empty">تیکت اولویت‌دار وجود ندارد.</div>
                    <?php else: ?>
                        <?php foreach ($prioritizedTickets as $ticket): ?>
                            <?php
                            $categoryId = $ticket['daste'] ?? '';
                            $categoryName = $ticket['name_daste'] ?? '---';
                            $priority = $ticket['olaviat'] ?? '';
                            $vaziat = $ticket['vaziat'] ?? '';
                            $priorityMeta = $priorityLabels[$priority] ?? null;
                            $orderBadge = isset($ticket['priority_order']) ? (int)$ticket['priority_order'] : null;
                            $vaziatLabel = ($vaziat == 'a') ? 'ثبت اولیه' : (($vaziat == 'm') ? 'در حال بررسی' : (($vaziat == 'w') ? 'روی میز' : ''));
                            $vaziatClass = ($vaziat == 'a') ? 'danger' : (($vaziat == 'm') ? 'info' : (($vaziat == 'w') ? 'primary' : 'secondary'));
                            ?>
                            <div class="view-card ticket-card-clickable" data-ticket-code="<?php echo htmlspecialchars($ticket['code'], ENT_QUOTES, 'UTF-8'); ?>" data-category-id="<?php echo htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold"><?php echo htmlspecialchars($ticket['titr'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <span class="view-meta mt-1">
                                                <span>کد: <?php echo htmlspecialchars($ticket['code'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                <span><?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?></span>
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <?php if ($orderBadge): ?>
                                                <span class="badge bg-primary view-badge mb-1 d-block">#<?php echo $orderBadge; ?></span>
                                            <?php else: ?>
                                                <span class="badge border border-primary text-primary view-badge mb-1 d-block">#--</span>
                                            <?php endif; ?>
                                            <?php if ($vaziatLabel): ?>
                                                <span class="badge bg-<?php echo $vaziatClass; ?> view-badge mb-1 d-block"><?php echo htmlspecialchars($vaziatLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php endif; ?>
                                            <div class="mt-1">
                                                <?php if ($priorityMeta): ?>
                                                    <span class="badge bg-<?php echo $priorityMeta['class']; ?> view-badge">اولویت: <?php echo htmlspecialchars($priorityMeta['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                <?php else: ?>
                                                    <span class="badge border border-secondary text-secondary view-badge">اولویت تعریف نشده</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge border border-success text-success view-badge">درخواست دهنده: <?php echo htmlspecialchars($ticket['name_karbar'] ?? '---', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="badge border border-info text-info view-badge">شرکت: <?php echo htmlspecialchars($ticket['name_sherkat'] ?? '---', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="text-muted small">ثبت: <?php echo htmlspecialchars(($ticket['tarikh_sabt'] ?? '') . ' - ' . ($ticket['saat_sabt'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
    width: min(1100px, 100%);
    max-height: min(88vh, 860px);
    background: var(--view-surface);
    color: var(--view-text);
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 18px;
    box-shadow: 0 42px 68px -38px rgba(8, 15, 30, 0.9);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.ticket-modal__header,
.ticket-modal__footer {
    padding: 20px 24px;
    background: rgba(17, 24, 39, 0.88);
    border-bottom: 1px solid rgba(99, 102, 241, 0.22);
}

.ticket-modal__footer {
    border-bottom: none;
    border-top: 1px solid rgba(99, 102, 241, 0.22);
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
    background: linear-gradient(160deg, rgba(17, 24, 39, 0.92), rgba(15, 23, 42, 0.85));
}

.ticket-modal__close {
    border: none;
    background: rgba(55, 65, 81, 0.65);
    color: #f9fafb;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
}

.ticket-modal__close:hover {
    background: rgba(59, 130, 246, 0.4);
    transform: translateY(-1px);
}

.ticket-modal__button {
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.85), rgba(37, 99, 235, 0.85));
    color: #e2e8f0;
    font-weight: 500;
    cursor: pointer;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.ticket-modal__button:hover {
    box-shadow: 0 18px 32px -26px rgba(37, 99, 235, 0.75);
    transform: translateY(-1px);
}

.ticket-modal__loading {
    text-align: center;
    padding: 48px 16px;
    color: rgba(203, 213, 225, 0.8);
}

.ticket-modal__spinner {
    width: 46px;
    height: 46px;
    border: 4px solid rgba(148, 163, 184, 0.25);
    border-top-color: rgba(59, 130, 246, 0.85);
    border-radius: 50%;
    margin: 0 auto 18px;
    animation: ticket-modal-spin 0.8s linear infinite;
}

@keyframes ticket-modal-spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

<!-- Ticket Info Modal -->
<div class="ticket-modal" id="ticketInfoModal" aria-hidden="true" role="dialog">
    <div class="ticket-modal__overlay" data-modal-close></div>
    <div class="ticket-modal__dialog" role="document">
        <div class="ticket-modal__header">
            <h5 class="ticket-modal__title" id="ticketInfoModalLabel">جزئیات تیکت</h5>
            <button type="button" class="ticket-modal__close" data-modal-close aria-label="بستن">×</button>
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
    const modal = document.getElementById('ticketInfoModal');
    const modalContent = document.getElementById('ticketInfoContent');
    if (!modal || !modalContent) {
        return;
    }

    const closeElements = modal.querySelectorAll('[data-modal-close]');
    const ticketCards = document.querySelectorAll('.ticket-card-clickable');
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

        let ticketHTML = '';
        const firstRow = doc.querySelector('.row.gx-3');
        if (firstRow) {
            let parent = firstRow.parentElement;
            while (parent && parent !== doc.body && !ticketHTML) {
                const cards = parent.querySelectorAll('.card.mb-3');
                if (cards.length) {
                    cards.forEach(card => {
                        ticketHTML += card.outerHTML;
                    });
                }
                parent = parent.parentElement;
            }

            if (!ticketHTML) {
                firstRow.querySelectorAll('.card').forEach(card => {
                    ticketHTML += card.outerHTML;
                });
            }
        } else {
            const cards = doc.querySelectorAll('.card.mb-3');
            cards.forEach(card => {
                ticketHTML += card.outerHTML;
            });
        }

        return ticketHTML || (html.includes('<!DOCTYPE') ? '' : html);
    };

    const showLoading = () => {
        modalContent.innerHTML = `
            <div class="ticket-modal__loading" role="status">
                <div class="ticket-modal__spinner" aria-hidden="true"></div>
                <p class="mb-0">در حال بارگذاری اطلاعات تیکت...</p>
            </div>
        `;
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
        })
            .then(response => response.text())
            .then(html => {
                if (!isOpen()) return;

                if (!html) {
                    showError('خطا در بارگذاری اطلاعات تیکت');
                    return;
                }

                const rendered = renderTicketContent(html);
                modalContent.innerHTML = rendered
                    ? rendered
                    : '<div class="alert alert-danger mb-0">محتوای تیکت یافت نشد.</div>';
            })
            .catch(error => {
                if (error && error.name === 'AbortError') {
                    return;
                }
                console.error('Error loading ticket info:', error);
                showError('خطا در بارگذاری اطلاعات تیکت');
            })
            .finally(() => {
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

    ticketCards.forEach(card => {
        card.addEventListener('click', () => {
            const ticketCode = card.dataset.ticketCode;
            if (!ticketCode) {
                return;
            }
            loadTicketInfo(ticketCode);
        });
    });
})();
</script>
