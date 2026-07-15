<?php

require_once __DIR__ . '/config.php';

function jalaliToIso(string $jalaliDate, string $time = ''): string
{
    $jalaliDate = trim($jalaliDate);
    if (strlen($jalaliDate) !== 8 || !ctype_digit($jalaliDate)) {
        return gmdate('Y-m-d\TH:i:s\Z');
    }

    $jy = (int) substr($jalaliDate, 0, 4) - 979;
    $jm = (int) substr($jalaliDate, 4, 2) - 1;
    $jd = (int) substr($jalaliDate, 6, 2) - 1;

    $jDayNo = 365 * $jy + intdiv($jy, 33) * 8 + intdiv($jy % 33 + 3, 4);
    for ($i = 0; $i < $jm; $i++) {
        $jDayNo += ($i < 6) ? 31 : 30;
    }
    $jDayNo += $jd;

    $gDayNo = $jDayNo + 79;
    $gy = 1600 + 400 * intdiv($gDayNo, 146097);
    $gDayNo %= 146097;

    if ($gDayNo >= 36525) {
        $gDayNo--;
        $gy += 100 * intdiv($gDayNo, 36524);
        $gDayNo %= 36524;
        if ($gDayNo >= 365) $gDayNo++;
    }

    $gy += 4 * intdiv($gDayNo, 1461);
    $gDayNo %= 1461;

    if ($gDayNo >= 366) {
        $gy += intdiv($gDayNo - 1, 365);
        $gDayNo = ($gDayNo - 1) % 365;
    }

    $leap = ($gy % 4 === 0 && $gy % 100 !== 0) || ($gy % 400 === 0);
    $gDaysInMonth = [31, 28 + ($leap ? 1 : 0), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    $gm = 0;
    for ($i = 0; $i < 12 && $gDayNo >= $gDaysInMonth[$i]; $i++) {
        $gDayNo -= $gDaysInMonth[$i];
        $gm++;
    }

    $gm += 1;
    $gd = $gDayNo + 1;

    $timePart = '00:00:00';
    $time = trim($time);
    if (preg_match('/^(\d{1,2}):(\d{2})(:\d{2})?$/', $time, $m)) {
        $timePart = sprintf('%02d:%s%s', $m[1], $m[2], !empty($m[3]) ? $m[3] : ':00');
    }

    return sprintf('%04d-%02d-%02dT%sZ', $gy, $gm, $gd, $timePart);
}

/**
 * @return array{success: bool, status_code: int, response: mixed, error: string|null}|null
 */
function updateTicket(string $ticketCode): ?array
{
    global $Link;

    while ($Link->more_results()) {
        $Link->next_result();
        if ($res = $Link->store_result()) {
            $res->free();
        }
    }

    $ticketCodeEscaped = mysqli_real_escape_string($Link, $ticketCode);

    $ticketQuery = "SELECT * FROM ticket WHERE code = '$ticketCodeEscaped' LIMIT 1";
    $ticketResult = mysqli_query($Link, $ticketQuery);
    if (!$ticketResult || mysqli_num_rows($ticketResult) === 0) {
        return null;
    }
    $ticket = mysqli_fetch_assoc($ticketResult);

    if ($ticket['vaziat'] === 'a') {
        return null;
    }

    $pasokhQuery = "SELECT * FROM pasokh WHERE code_ticket = '$ticketCodeEscaped'";
    $pasokhResult = mysqli_query($Link, $pasokhQuery);

    $handlerIsBpms = in_array($ticket['code_p_karbar_anjam'], BPMS_USER_CODES);

    $pasokhHasBpms = false;
    if ($pasokhResult) {
        while ($row = mysqli_fetch_assoc($pasokhResult)) {
            if (in_array($row['code_karbar_sabt'], BPMS_USER_CODES)) {
                $pasokhHasBpms = true;
                break;
            }
        }
    }

    if (!$handlerIsBpms && !$pasokhHasBpms) {
        return null;
    }

    $designerUsername = $handlerIsBpms ? $ticket['code_p_karbar_anjam'] : '';

    $bpmsCodes = array_map(function ($c) use ($Link) {
        return "'" . mysqli_real_escape_string($Link, $c) . "'";
    }, BPMS_USER_CODES);
    $bpmsIn = implode(',', $bpmsCodes);

    $referQuery = "SELECT tarikh_sabt, saat_sabt FROM pasokh 
                   WHERE code_ticket = '$ticketCodeEscaped' 
                   AND code_karbar_sabt IN ($bpmsIn) 
                   ORDER BY i_pasokh ASC LIMIT 1";
    $referResult = mysqli_query($Link, $referQuery);
    $referDate = '';
    if ($referResult && $referRow = mysqli_fetch_assoc($referResult)) {
        $referDate = jalaliToIso($referRow['tarikh_sabt'], $referRow['saat_sabt']);
    }

    $payload = [
        'title'             => $ticket['titr'],
        'description'       => $ticket['matn'],
        'company_name'      => $ticket['name_sherkat'],
        'ticket_number'     => $ticket['code'],
        'request_user'      => $ticket['name_karbar'],
        'designer_username' => $designerUsername,
        'status'            => $ticket['vaziat'],
        'priority'          => (int) $ticket['olaviat'],
        'created_at'        => jalaliToIso($ticket['tarikh_sabt'], $ticket['saat_sabt'] ?? ''),
        'reference_date'    => $referDate,
    ];

    $url = BASE_URL . CREATE_TICKET_ENDPOINT;
    return sendRequest('POST', $url, $payload);
}

/**
 * @return array{success: bool, status_code: int, response: mixed, error: string|null}
 */
function sendRequest(string $method, string $url, array $data): array
{
    $jsonPayload = json_encode($data, JSON_UNESCAPED_UNICODE);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_POSTFIELDS     => $jsonPayload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json; charset=utf-8',
            'x-api-key: ' . API_KEY,
        ],
    ]);

    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        return [
            'success' => false,
            'status_code' => 0,
            'response' => null,
            'error' => $curlError,
        ];
    }

    $decoded = json_decode($response, true);

    return [
        'success' => $statusCode >= 200 && $statusCode < 300,
        'status_code' => $statusCode,
        'response' => $decoded ?? $response,
        'error' => null,
    ];
}
