<?php
/**
 * Ticket tags (برچسب‌های تیکت) — per-user tag system for tickets.
 * Inspired by the letter_flags system from cartable-php.
 */

if (!function_exists('ticket_tags_table_exists')) {
    function ticket_tags_table_exists($Link) {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        if (!isset($Link) || !$Link) {
            $cache = false;
            return false;
        }
        $res = @mysqli_query($Link, "SHOW TABLES LIKE 'ticket_tags'");
        $cache = ($res && mysqli_fetch_assoc($res)) ? true : false;
        return $cache;
    }
}

if (!function_exists('ticket_tags_assignments_table_exists')) {
    function ticket_tags_assignments_table_exists($Link) {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        if (!isset($Link) || !$Link) {
            $cache = false;
            return false;
        }
        $res = @mysqli_query($Link, "SHOW TABLES LIKE 'ticket_tag_assignments'");
        $cache = ($res && mysqli_fetch_assoc($res)) ? true : false;
        return $cache;
    }
}

if (!function_exists('ticket_tag_normalize_color')) {
    function ticket_tag_normalize_color($color) {
        $color = trim((string)$color);
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            return strtolower($color);
        }
        return '#e11d48';
    }
}

if (!function_exists('ticket_tag_preset_colors')) {
    function ticket_tag_preset_colors() {
        return [
            // Reds & Oranges (گرم)
            '#e11d48', '#fb7185', '#f43f5e', '#ef4444', '#b91c1c',
            '#f97316', '#ea580c', '#fdba74',
            // Yellows & Ambers (زرد و کهربایی)
            '#f59e0b', '#d97706', '#facc15', '#fef08a',
            // Greens (سبز)
            '#22c55e', '#16a34a', '#10b981', '#059669', '#a3e635', '#65a30d',
            // Teals & Cyans (فیروزه‌ای)
            '#14b8a6', '#0d9488', '#2dd4bf',
            // Purples & Violets (بنفش)
            '#a855f7', '#9333ea', '#c084fc',
            // Pinks & Fuchsias (صورتی)
            '#ec4899', '#db2777', '#d946ef', '#c026d3',
            // Neutrals with character (خنثی با شخصیت)
            '#78716c', '#a8a29e',
        ];
    }
}

if (!function_exists('ticket_tag_normalize_title')) {
    function ticket_tag_normalize_title(string $title): string
    {
        $title = trim($title);
        if ($title === '') {
            return '';
        }
        $collapsed = preg_replace('/\s+/u', ' ', $title);
        return is_string($collapsed) ? $collapsed : $title;
    }
}

if (!function_exists('ticket_tag_title_taken')) {
    function ticket_tag_title_taken($Link, string $ownerCode, string $title, int $excludeId = 0): bool
    {
        $ownerCode = trim($ownerCode);
        $normalizedTitle = ticket_tag_normalize_title($title);
        if ($ownerCode === '' || $normalizedTitle === '' || !$Link || !ticket_tags_table_exists($Link)) {
            return false;
        }

        $stmt = mysqli_prepare($Link, 'SELECT id, title FROM ticket_tags WHERE owner_code_p = ?');
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 's', $ownerCode);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($res && ($row = mysqli_fetch_assoc($res))) {
            $rowId = (int)($row['id'] ?? 0);
            if ($excludeId > 0 && $rowId === $excludeId) {
                continue;
            }
            $existingTitle = ticket_tag_normalize_title((string)($row['title'] ?? ''));
            if ($existingTitle === $normalizedTitle) {
                mysqli_stmt_close($stmt);
                return true;
            }
        }
        mysqli_stmt_close($stmt);
        return false;
    }
}

if (!function_exists('ticket_tag_get_by_id')) {
    /**
     * @return array{id:int,title:string,color:string,owner_code_p:string}|null
     */
    function ticket_tag_get_by_id($Link, int $tag_id) {
        if ($tag_id <= 0 || !ticket_tags_table_exists($Link)) {
            return null;
        }
        $stmt = mysqli_prepare($Link, 'SELECT id, title, color, owner_code_p FROM ticket_tags WHERE id = ? LIMIT 1');
        if (!$stmt) {
            return null;
        }
        mysqli_stmt_bind_param($stmt, 'i', $tag_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        if (!$row) {
            return null;
        }
        return [
            'id' => (int)($row['id'] ?? 0),
            'title' => (string)($row['title'] ?? ''),
            'color' => ticket_tag_normalize_color($row['color'] ?? ''),
            'owner_code_p' => (string)($row['owner_code_p'] ?? ''),
        ];
    }
}

if (!function_exists('ticket_tag_user_can_use')) {
    function ticket_tag_user_can_use($Link, int $tag_id, string $owner_code_p): bool
    {
        $tag = ticket_tag_get_by_id($Link, $tag_id);
        if (!$tag) {
            return false;
        }
        $owner = trim($owner_code_p);
        if ($owner === '') {
            return false;
        }
        $stmt = mysqli_prepare($Link, 'SELECT id FROM ticket_tags WHERE id = ? AND owner_code_p = ? LIMIT 1');
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'is', $tag_id, $owner);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $ok = $res && mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }
}

if (!function_exists('ticket_tag_load_user_tags')) {
    /**
     * Load all tags created by a specific user.
     *
     * @return array<int, array{id:int,title:string,color:string}>
     */
    function ticket_tag_load_user_tags($Link, $owner_code_p) {
        if (!ticket_tags_table_exists($Link) || trim((string)$owner_code_p) === '') {
            return [];
        }
        $owner = trim((string)$owner_code_p);
        $stmt = mysqli_prepare(
            $Link,
            'SELECT id, title, color FROM ticket_tags WHERE owner_code_p = ? ORDER BY title ASC'
        );
        if (!$stmt) {
            return [];
        }
        mysqli_stmt_bind_param($stmt, 's', $owner);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $tags = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $tags[] = [
                    'id' => (int)($row['id'] ?? 0),
                    'title' => (string)($row['title'] ?? ''),
                    'color' => ticket_tag_normalize_color($row['color'] ?? ''),
                ];
            }
        }
        mysqli_stmt_close($stmt);
        return $tags;
    }
}

if (!function_exists('ticket_tag_load_assignments')) {
    /**
     * Load tag assignments for given ticket codes.
     *
     * @param string[] $ticket_codes
     * @return array<string, array<int, array{id:int,title:string,color:string,assigned_at:string}>>
     */
    function ticket_tag_load_assignments($Link, $owner_code_p, array $ticket_codes) {
        $out = [];
        if (!ticket_tags_table_exists($Link) || !ticket_tags_assignments_table_exists($Link) || trim((string)$owner_code_p) === '') {
            return $out;
        }
        $ticket_codes = array_values(array_unique(array_filter(array_map('trim', $ticket_codes))));
        if (empty($ticket_codes)) {
            return $out;
        }
        $owner = trim((string)$owner_code_p);
        $placeholders = implode(',', array_fill(0, count($ticket_codes), '?'));
        $types = 's' . str_repeat('s', count($ticket_codes));

        $sql = "SELECT a.ticket_code, a.created_at AS assigned_at, t.id, t.title, t.color
                FROM ticket_tag_assignments a
                INNER JOIN ticket_tags t ON t.id = a.tag_id
                WHERE a.owner_code_p = ? AND a.ticket_code IN ($placeholders)
                ORDER BY t.title ASC";

        $stmt = mysqli_prepare($Link, $sql);
        if (!$stmt) {
            return $out;
        }
        $params = array_merge([$owner], $ticket_codes);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $code = (string)($row['ticket_code'] ?? '');
                if ($code === '') {
                    continue;
                }
                if (!isset($out[$code])) {
                    $out[$code] = [];
                }
                $out[$code][] = [
                    'id' => (int)($row['id'] ?? 0),
                    'title' => (string)($row['title'] ?? ''),
                    'color' => ticket_tag_normalize_color($row['color'] ?? ''),
                    'assigned_at' => (string)($row['assigned_at'] ?? ''),
                ];
            }
        }
        mysqli_stmt_close($stmt);
        return $out;
    }
}

if (!function_exists('ticket_tag_hex_to_rgba')) {
    function ticket_tag_hex_to_rgba(string $hex, float $alpha = 1.0): string
    {
        $hex = ltrim(trim($hex), '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (!preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            return 'rgba(99, 102, 241, ' . max(0, min(1, $alpha)) . ')';
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $alpha = max(0, min(1, $alpha));
        $alpha_str = rtrim(rtrim(sprintf('%.3f', $alpha), '0'), '.');
        return sprintf('rgba(%d, %d, %d, %s)', $r, $g, $b, $alpha_str);
    }
}

if (!function_exists('ticket_tag_oldest_assigned')) {
    /**
     * Oldest tag assignment (by assignment created_at).
     *
     * @param array<int, array<string, mixed>> $assigned
     * @return array<string, mixed>|null
     */
    function ticket_tag_oldest_assigned(array $assigned): ?array
    {
        if (empty($assigned)) {
            return null;
        }
        $best = null;
        $best_ts = null;
        foreach ($assigned as $tag) {
            $at = trim((string)($tag['assigned_at'] ?? ''));
            if ($at === '') {
                continue;
            }
            $ts = strtotime($at);
            if ($ts === false) {
                continue;
            }
            if ($best === null || $ts < $best_ts) {
                $best = $tag;
                $best_ts = $ts;
            }
        }
        return $best ?? $assigned[0];
    }
}

if (!function_exists('ticket_tag_row_shade_style')) {
    /**
     * CSS custom properties for tagged row background (oldest tag color).
     */
    function ticket_tag_row_shade_style(array $assigned): string
    {
        $oldest = ticket_tag_oldest_assigned($assigned);
        if (!$oldest) {
            return '';
        }
        $color = ticket_tag_normalize_color((string)($oldest['color'] ?? ''));
        $hex = ltrim($color, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return '--tt-row-shade-rgb:' . $r . ',' . $g . ',' . $b;
    }
}

if (!function_exists('ticket_tag_assigned_ids')) {
    /**
     * @return int[]
     */
    function ticket_tag_assigned_ids(array $assignments_for_ticket) {
        $ids = [];
        foreach ($assignments_for_ticket as $f) {
            $id = (int)($f['id'] ?? 0);
            if ($id > 0) {
                $ids[] = $id;
            }
        }
        return $ids;
    }
}
