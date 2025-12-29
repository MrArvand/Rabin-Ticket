<?php
date_default_timezone_set('Asia/Tehran');

/**
 * Send SMS using IPPanel Edge API
 * 
 * @param string $recipient Phone number in E.164 format (e.g., +989121234567)
 * @param string $message SMS message content
 * @return array Response array with 'success' boolean and 'message' string
 */
function send_sms_ippanel($recipient, $message) {
    // IPPanel Edge API configuration
    // IMPORTANT: Configure your IPPanel API credentials here
    // Get your API key from: IPPanel User Panel > Developers > Access Keys
    // Documentation: https://ippanelcom.github.io/Edge-Document/docs/
    $api_key = 'YTBiMTU1NzUtMzdlMy00ZTM0LTk2OWItMzcwZWEzYjQ3MGMzYjI4M2RmNTllZTQ4N2VjYWU1ZGViZDU5NDBkODQzMDY='; // Replace with your actual IPPanel API key
    $from_number = '+9890009643'; // Replace with your registered sender number (E.164 format)
    
    // Ensure phone number is in E.164 format
    $recipient = format_phone_number($recipient);
    
    if (empty($recipient)) {
        return ['success' => false, 'message' => 'Invalid phone number'];
    }
    
    if (empty($message)) {
        return ['success' => false, 'message' => 'Message cannot be empty'];
    }
    
    // Add footer to all SMS messages
    $sms_footer = "\n\nمشاهده جزئیات تیکت در request-r.ir";
    $message = $message . $sms_footer;
    
    // IPPanel Edge API endpoint
    $url = 'https://edge.ippanel.com/v1/api/send';
    
    // Prepare request data
    $data = [
        'sending_type' => 'webservice',
        'from_number' => $from_number,
        'message' => $message,
        'params' => [
            'recipients' => [$recipient]
        ]
    ];
    
    // Initialize cURL
    $ch = curl_init($url);
    
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . $api_key,
            'Content-Type: application/json'
        ],
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    
    curl_close($ch);
    
    // Handle errors
    if ($curl_error) {
        error_log("IPPanel SMS Error: " . $curl_error);
        return ['success' => false, 'message' => 'Connection error: ' . $curl_error];
    }
    
    if ($http_code !== 200) {
        error_log("IPPanel SMS HTTP Error: " . $http_code . " - " . $response);
        return ['success' => false, 'message' => 'API returned HTTP ' . $http_code];
    }
    
    $response_data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("IPPanel SMS JSON Error: " . json_last_error_msg());
        return ['success' => false, 'message' => 'Invalid response from API'];
    }
    
    // Check if response indicates success
    if (isset($response_data['status']) && $response_data['status'] === 'success') {
        return ['success' => true, 'message' => 'SMS sent successfully'];
    } else {
        $error_msg = isset($response_data['message']) ? $response_data['message'] : 'Unknown error';
        error_log("IPPanel SMS Error: " . $error_msg);
        return ['success' => false, 'message' => $error_msg];
    }
}

/**
 * Format phone number to E.164 format
 * Converts Iranian phone numbers (09121234567) to E.164 (+989121234567)
 * 
 * @param string $phone Phone number to format
 * @return string Formatted phone number or empty string if invalid
 */
function format_phone_number($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // If starts with 0, replace with +98
    if (substr($phone, 0, 1) === '0') {
        $phone = '+98' . substr($phone, 1);
    } 
    // If starts with 98, add +
    elseif (substr($phone, 0, 2) === '98') {
        $phone = '+' . $phone;
    }
    // If starts with 9 (mobile number without country code), add +98
    elseif (substr($phone, 0, 1) === '9' && strlen($phone) === 10) {
        $phone = '+98' . $phone;
    }
    // If doesn't start with +, add it
    elseif (substr($phone, 0, 1) !== '+') {
        $phone = '+' . $phone;
    }
    
    // Validate length (Iranian mobile: +989XXXXXXXXX = 13 characters)
    if (strlen($phone) < 10 || strlen($phone) > 15) {
        return '';
    }
    
    return $phone;
}

/**
 * Trim text to specified length and add ellipsis if needed
 * 
 * @param string $text Text to trim
 * @param int $max_length Maximum length
 * @return string Trimmed text
 */
function trim_text($text, $max_length) {
    if (mb_strlen($text, 'UTF-8') <= $max_length) {
        return $text;
    }
    return mb_substr($text, 0, $max_length, 'UTF-8') . '...';
}

?>