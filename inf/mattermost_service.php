<?php

if (!function_exists('ticket_bsp_send_allowed_codes')) {
    function ticket_bsp_send_allowed_codes()
    {
        return array('24277', '1064046037', '25662');
    }
}

if (!function_exists('ticket_can_send_to_bsp')) {
    function ticket_can_send_to_bsp($user_code = null)
    {
        if ($user_code === null) {
            $user_code = isset($_SESSION['code_p']) ? trim((string) $_SESSION['code_p']) : '';
        }
        return in_array($user_code, ticket_bsp_send_allowed_codes(), true);
    }
}

if (!function_exists('ticket_mattermost_api_base')) {
    function ticket_mattermost_api_base($base_url)
    {
        $base = rtrim(trim((string) $base_url), '/');
        if ($base === '') {
            return '';
        }
        if (preg_match('#/api/v4$#i', $base)) {
            $base = rtrim(preg_replace('#/api/v4$#i', '', $base), '/');
        }
        return $base . '/api/v4';
    }
}

if (!function_exists('ticket_mattermost_sanitize_access_token')) {
    function ticket_mattermost_sanitize_access_token($token)
    {
        $t = (string) $token;
        if ($t === '') {
            return '';
        }
        if (strncmp($t, "\xEF\xBB\xBF", 3) === 0) {
            $t = substr($t, 3);
        }
        $t = preg_replace('/[\x00-\x1F\x7F]/u', '', $t);
        $t = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $t);
        $t = trim($t);
        if (stripos($t, 'Bearer ') === 0) {
            $t = trim(substr($t, 7));
        }
        return $t;
    }
}

if (!function_exists('ticket_mattermost_env_token')) {
    function ticket_mattermost_env_token()
    {
        $keys = array('MATTERMOST_BOT_TOKEN', 'MM_BOT_TOKEN', 'MATTERMOST_ACCESS_TOKEN');
        foreach ($keys as $k) {
            $v = getenv($k);
            if ($v !== false && trim((string) $v) !== '') {
                return array('raw' => $v, 'source' => 'env:' . $k);
            }
            if (isset($_SERVER[$k]) && trim((string) $_SERVER[$k]) !== '') {
                return array('raw' => $_SERVER[$k], 'source' => 'server:' . $k);
            }
        }
        return null;
    }
}

if (!function_exists('ticket_mattermost_file_token')) {
    function ticket_mattermost_file_token()
    {
        $path = __DIR__ . '/mattermost_token.txt';
        if (!is_readable($path)) {
            return null;
        }
        $content = file_get_contents($path);
        if ($content === false) {
            return null;
        }
        foreach (preg_split('/\r\n|\r|\n/', $content) as $line) {
            $line = trim($line);
            if ($line === '' || (isset($line[0]) && $line[0] === '#')) {
                continue;
            }
            return array('raw' => $line, 'source' => 'file:mattermost_token.txt');
        }
        return null;
    }
}

if (!function_exists('ticket_mattermost_load_config')) {
    function ticket_mattermost_load_config()
    {
        $path = __DIR__ . '/mattermost_config.php';
        if (!is_readable($path)) {
            return null;
        }

        include $path;

        $token_raw = '';
        $token_source = 'none';

        $from_env = ticket_mattermost_env_token();
        if ($from_env !== null) {
            $token_raw = $from_env['raw'];
            $token_source = $from_env['source'];
        }

        if ($token_raw === '') {
            $from_file = ticket_mattermost_file_token();
            if ($from_file !== null) {
                $token_raw = $from_file['raw'];
                $token_source = $from_file['source'];
            }
        }

        if ($token_raw === '') {
            if (isset($MATTERMOST_BOT_TOKEN) && $MATTERMOST_BOT_TOKEN !== '') {
                $token_raw = $MATTERMOST_BOT_TOKEN;
                $token_source = 'config:MATTERMOST_BOT_TOKEN';
            } elseif (isset($MATTERMOST_ACCESS_TOKEN) && $MATTERMOST_ACCESS_TOKEN !== '') {
                $token_raw = $MATTERMOST_ACCESS_TOKEN;
                $token_source = 'config:MATTERMOST_ACCESS_TOKEN';
            }
        }

        $token = ticket_mattermost_sanitize_access_token($token_raw);
        $base_url = isset($MATTERMOST_BASE_URL) ? trim((string) $MATTERMOST_BASE_URL) : '';
        $bsp_channel_id = isset($MATTERMOST_BSP_CHANNEL_ID) ? trim((string) $MATTERMOST_BSP_CHANNEL_ID) : '';
        $enabled = true;
        if (isset($MATTERMOST_ENABLED)) {
            $enabled = (bool) $MATTERMOST_ENABLED;
        }

        return array(
            'enabled' => $enabled,
            'base_url' => $base_url,
            'bot_token' => $token,
            'bsp_channel_id' => $bsp_channel_id,
            'token_source' => $token_source,
            'token_len' => strlen($token),
        );
    }
}

if (!function_exists('ticket_mattermost_http_request')) {
    function ticket_mattermost_http_request($method, $url, $token, $post_json = null)
    {
        $method = strtoupper((string) $method);
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
            'User-Agent: RabinTicketBSP/1.0 (PHP)',
        );

        if ($post_json !== null) {
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Expect:';
        }

        $ch = curl_init();
        $opts = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => $headers,
        );

        if ($method === 'GET') {
            $opts[CURLOPT_HTTPGET] = true;
        } elseif ($method === 'POST' && $post_json !== null) {
            $opts[CURLOPT_POST] = true;
            $opts[CURLOPT_POSTFIELDS] = $post_json;
        } else {
            $opts[CURLOPT_CUSTOMREQUEST] = $method;
            if ($post_json !== null) {
                $opts[CURLOPT_POSTFIELDS] = $post_json;
            }
        }

        curl_setopt_array($ch, $opts);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        return array(
            'code' => $code,
            'body' => $body === false ? '' : (string) $body,
            'error' => (string) $err,
        );
    }
}

if (!function_exists('ticket_mattermost_post_message')) {
    function ticket_mattermost_post_message($channel_id, $message)
    {
        $cfg = ticket_mattermost_load_config();
        if ($cfg === null) {
            return array('success' => false, 'message' => 'فایل تنظیمات Mattermost یافت نشد.');
        }
        if (empty($cfg['enabled'])) {
            return array('success' => false, 'message' => 'ارسال به Mattermost غیرفعال است.');
        }
        if ($cfg['base_url'] === '') {
            return array('success' => false, 'message' => 'آدرس سرور Mattermost تنظیم نشده است.');
        }
        if ($cfg['bot_token'] === '') {
            return array('success' => false, 'message' => 'توکن ربات Mattermost تنظیم نشده است.');
        }
        if (trim((string) $channel_id) === '') {
            return array('success' => false, 'message' => 'شناسه کانال ICT در تنظیمات Mattermost خالی است.');
        }
        if (!function_exists('curl_init')) {
            return array('success' => false, 'message' => 'افزونه cURL در سرور فعال نیست.');
        }

        $api_root = ticket_mattermost_api_base($cfg['base_url']);
        if ($api_root === '') {
            return array('success' => false, 'message' => 'آدرس سرور Mattermost نامعتبر است.');
        }

        $me = ticket_mattermost_http_request('GET', $api_root . '/users/me', $cfg['bot_token'], null);
        if ($me['error'] !== '') {
            return array('success' => false, 'message' => 'خطا در اتصال به Mattermost: ' . $me['error']);
        }
        if ($me['code'] === 401) {
            return array('success' => false, 'message' => 'توکن ربات Mattermost نامعتبر است.');
        }
        if ($me['code'] < 200 || $me['code'] >= 300) {
            return array('success' => false, 'message' => 'خطا در احراز هویت Mattermost (کد ' . $me['code'] . ').');
        }

        $body = array(
            'channel_id' => trim((string) $channel_id),
            'message' => (string) $message,
        );
        $post = ticket_mattermost_http_request(
            'POST',
            $api_root . '/posts',
            $cfg['bot_token'],
            json_encode($body, JSON_UNESCAPED_UNICODE)
        );

        if ($post['error'] !== '') {
            return array('success' => false, 'message' => 'خطا در ارسال پیام: ' . $post['error']);
        }
        if ($post['code'] >= 200 && $post['code'] < 300) {
            return array('success' => true, 'message' => 'پیام با موفقیت به گروه ICT ارسال شد.');
        }

        $detail = 'کد ' . $post['code'];
        if ($post['code'] === 403) {
            $detail .= ' — ربات به کانال ICT دسترسی ندارد یا عضو کانال نیست.';
        }
        return array('success' => false, 'message' => 'ارسال به Mattermost ناموفق بود (' . $detail . ').');
    }
}

if (!function_exists('ticket_is_system_pasokh')) {
    function ticket_is_system_pasokh(array $row)
    {
        $kind = isset($row['kind']) ? (string) $row['kind'] : '';
        if (in_array($kind, array('referral', 'reopen', 'cancel', 'done'), true)) {
            return true;
        }
        if (!empty($row['code_karbar2']) && !empty($row['name_karbar2']) &&
            strpos((string) $row['matn'], 'مسئول پاسخگویی') !== false) {
            return true;
        }
        return false;
    }
}

if (!function_exists('ticket_build_bsp_mattermost_message')) {
    function ticket_build_bsp_mattermost_message(array $ticket, array $responses, $actor_name)
    {
        $sender_name = trim((string) ($ticket['name_karbar'] ?? ''));
        $sender_company = trim((string) ($ticket['name_sherkat'] ?? ''));

        $receiver_name = trim((string) ($ticket['name_karbar_anjam'] ?? ''));
        if ($receiver_name === '') {
            $receiver_name = 'نامشخص';
        }
        $receiver_company = trim((string) ($ticket['target_sherkat_name'] ?? ''));
        if ($receiver_company === '') {
            $receiver_company = 'نامشخص';
        }

        $department = trim((string) ($ticket['name_daste'] ?? ''));
        if ($department === '') {
            $department = 'نامشخص';
        }

        $registered_at = trim((string) ($ticket['tarikh_sabt'] ?? '') . ' - ' . (string) ($ticket['saat_sabt'] ?? ''));
        $actor_name = trim((string) $actor_name);
        if ($actor_name === '') {
            $actor_name = 'کاربر';
        }

        $lines = array();
        $lines[] = '📨 **تیکت جدیدی توسط کاربر ' . $actor_name . ' به کمیته ICT ارسال شد**';
        $lines[] = '';
        $lines[] = '👤 **فرستنده تیکت:** ' . ($sender_name !== '' ? $sender_name : 'نامشخص') . ' - 🏢 ' . ($sender_company !== '' ? $sender_company : 'نامشخص');
        $lines[] = '🎯 **گیرنده تیکت:** ' . $receiver_name . ' - 🏢 ' . $receiver_company;
        $lines[] = '🏛️ **دپارتمان:** ' . $department;
        $lines[] = '🕒 **زمان ثبت تیکت:** ' . ($registered_at !== ' - ' ? $registered_at : 'نامشخص');
        $lines[] = '';

        $first_message = '';
        foreach ($responses as $row) {
            if (ticket_is_system_pasokh($row)) {
                continue;
            }
            $matn = trim((string) ($row['matn'] ?? ''));
            if ($matn === '') {
                continue;
            }
            $first_message = $matn;
            break;
        }

        if ($first_message === '') {
            $first_message = trim((string) ($ticket['matn'] ?? ''));
        }

        if ($first_message !== '') {
            $lines[] = '💬 **متن تیکت:**';
            $lines[] = $first_message;
        } else {
            $lines[] = '💬 **پیام:** (بدون متن)';
        }

        $ticket_code = trim((string) ($ticket['code'] ?? ''));
        $lines[] = '';
        $lines[] = '🔗لینک دسترسی به تیکت:';
        $lines[] = 'http://ticket.rahbarianrabin.ir/support.php?page=info_ticket&code=' . $ticket_code;

        return rtrim(implode("\n", $lines));
    }
}

if (!function_exists('ticket_send_to_bsp_mattermost')) {
    function ticket_send_to_bsp_mattermost($link, $code_ticket, $actor_name)
    {
        $cfg = ticket_mattermost_load_config();
        if ($cfg === null) {
            return array('success' => false, 'message' => 'فایل تنظیمات Mattermost یافت نشد.');
        }

        $code_ticket = trim((string) $code_ticket);
        if ($code_ticket === '') {
            return array('success' => false, 'message' => 'شماره تیکت مشخص نیست.');
        }

        $code_escaped = mysqli_real_escape_string($link, $code_ticket);
        $ticket = null;
        $query_ticket = "SELECT * FROM ticket WHERE code = '$code_escaped' ORDER BY i_ticket DESC LIMIT 1";
        if ($result_ticket = mysqli_query($link, $query_ticket)) {
            $ticket = mysqli_fetch_array($result_ticket);
        }
        if (!$ticket) {
            return array('success' => false, 'message' => 'تیکت یافت نشد.');
        }

        $responses = array();
        $query_pasokh = "SELECT * FROM pasokh WHERE code_ticket = '$code_escaped' ORDER BY i_pasokh ASC LIMIT 20";
        if ($result_pasokh = mysqli_query($link, $query_pasokh)) {
            while ($row = mysqli_fetch_array($result_pasokh)) {
                $responses[] = $row;
            }
        }

        $message = ticket_build_bsp_mattermost_message($ticket, $responses, $actor_name);
        return ticket_mattermost_post_message($cfg['bsp_channel_id'], $message);
    }
}
