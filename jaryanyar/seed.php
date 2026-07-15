<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/api_functions.php';

$Link = mysqli_connect('localhost', 'rahbaria_software_team', 'C7L}n}U n<Y}^');
mysqli_select_db($Link, 'rahbaria_requestr_rahbarian');
mysqli_set_charset($Link, 'utf8mb4');

if (!$Link) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$ticketResult = mysqli_query($Link, "SELECT * FROM ticket WHERE vaziat != 'a'");
if (!$ticketResult) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to query tickets']);
    exit;
}

$bpmsUserCodes = BPMS_USER_CODES;
$tickets = [];

while ($ticket = mysqli_fetch_assoc($ticketResult)) {
    $code = mysqli_real_escape_string($Link, $ticket['code']);

    $handlerIsBpms = in_array($ticket['code_p_karbar_anjam'], $bpmsUserCodes);

    $pasokhHasBpms = false;
    $pasokhResult = mysqli_query($Link, "SELECT code_karbar_sabt FROM pasokh WHERE code_ticket = '$code'");
    if ($pasokhResult) {
        while ($row = mysqli_fetch_assoc($pasokhResult)) {
            if (in_array($row['code_karbar_sabt'], $bpmsUserCodes)) {
                $pasokhHasBpms = true;
                break;
            }
        }
        mysqli_free_result($pasokhResult);
    }

    if (!$handlerIsBpms && !$pasokhHasBpms) {
        continue;
    }

    $designerUsername = $handlerIsBpms ? $ticket['code_p_karbar_anjam'] : '';

    $bpmsCodes = array_map(function ($c) use ($Link) {
        return "'" . mysqli_real_escape_string($Link, $c) . "'";
    }, $bpmsUserCodes);
    $bpmsIn = implode(',', $bpmsCodes);

    $referQuery = "SELECT tarikh_sabt, saat_sabt FROM pasokh 
                   WHERE code_ticket = '$code' 
                   AND code_karbar_sabt IN ($bpmsIn) 
                   ORDER BY i_pasokh ASC LIMIT 1";
    $referResult = mysqli_query($Link, $referQuery);
    $referDate = '';
    if ($referResult && $referRow = mysqli_fetch_assoc($referResult)) {
        $referDate = jalaliToIso($referRow['tarikh_sabt'], $referRow['saat_sabt']);
        mysqli_free_result($referResult);
    }

    $tickets[] = [
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
}
mysqli_free_result($ticketResult);

if (empty($tickets)) {
    echo json_encode(['success' => true, 'message' => 'No matching tickets found', 'count' => 0]);
    exit;
}

$url = BASE_URL . SEED_ENDPOINT;
$jsonPayload = json_encode(['tickets' => $tickets], JSON_UNESCAPED_UNICODE);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => 'POST',
    CURLOPT_POSTFIELDS     => $jsonPayload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60,
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
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => $curlError, 'count' => count($tickets)]);
    exit;
}

$decoded = json_decode($response, true);
$success = $statusCode >= 200 && $statusCode < 300;

http_response_code($success ? 200 : 502);
echo json_encode([
    'success'     => $success,
    'count'       => count($tickets),
    'status_code' => $statusCode,
    'response'    => $decoded ?? $response,
]);
