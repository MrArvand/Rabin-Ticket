<?php
 date_default_timezone_set('Asia/Tehran');
 
 /**
  * Send SMS using IranPayamak pattern API.
  *
  * @param string $recipient Phone number in local or international format
  * @param array $attributes Pattern attributes for the template
  * @param array $options Optional settings مثل pattern_code و line_number
  * @return array Response array with 'success' boolean and 'message' string
  */
 function send_sms_pattern($recipient, array $attributes, array $options = []) {
     $api_key = 'N7ne1csfYE0TQkXPNAfV2fLqO5b6qXcd8yDrtX92MWjeiWd7tH';
     $pattern_code = !empty($options['pattern_code']) ? (string)$options['pattern_code'] : 'UlTaEoqWQ0';
     $line_number = !empty($options['line_number']) ? (string)$options['line_number'] : '3000505';
     $number_format = !empty($options['number_format']) ? (string)$options['number_format'] : 'english';
     $url = 'https://api.iranpayamak.com/ws/v1/sms/pattern';
 
     $recipient = format_phone_number_local($recipient);
     if (empty($recipient)) {
         return ['success' => false, 'message' => 'Invalid phone number'];
     }
 
     $payload = [
         'code' => $pattern_code,
         'recipient' => $recipient,
         'attributes' => array_map('strval', $attributes),
         'line_number' => $line_number,
         'number_format' => $number_format
     ];
 
     $json_payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
     if ($json_payload === false) {
         return ['success' => false, 'message' => 'Failed to encode SMS payload'];
     }

     $ch = curl_init($url);
     curl_setopt_array($ch, [
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS => $json_payload,
         CURLOPT_HTTPHEADER => [
             'api-key: ' . $api_key,
             'content-type: application/json'
         ],
         CURLOPT_TIMEOUT => 10,
         CURLOPT_CONNECTTIMEOUT => 5
     ]);
 
     $response = curl_exec($ch);
     $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
     $curl_error = curl_error($ch);
     curl_close($ch);
 
     if ($curl_error) {
         error_log('IranPayamak SMS Error: ' . $curl_error);
         return ['success' => false, 'message' => 'Connection error: ' . $curl_error];
     }
 
     if ($http_code < 200 || $http_code >= 300) {
         error_log('IranPayamak SMS HTTP Error: ' . $http_code . ' - ' . $response);
         return ['success' => false, 'message' => 'API returned HTTP ' . $http_code];
     }
 
     $response_data = json_decode($response, true);
     if (!empty($response) && json_last_error() !== JSON_ERROR_NONE) {
         error_log('IranPayamak SMS JSON Error: ' . json_last_error_msg());
         return ['success' => false, 'message' => 'Invalid response from API'];
     }
 
     $success = true;
     if (is_array($response_data)) {
         if (isset($response_data['success'])) {
             $success = (bool)$response_data['success'];
         } elseif (isset($response_data['status'])) {
             $success = in_array((string)$response_data['status'], ['success', 'ok', '200'], true);
         }
     }
 
     if (!$success) {
         $error_msg = 'Unknown error';
         if (is_array($response_data)) {
             if (!empty($response_data['message'])) {
                 $error_msg = $response_data['message'];
             } elseif (!empty($response_data['error'])) {
                 $error_msg = is_string($response_data['error']) ? $response_data['error'] : json_encode($response_data['error'], JSON_UNESCAPED_UNICODE);
             } elseif (!empty($response_data['errors'])) {
                 $error_msg = is_string($response_data['errors']) ? $response_data['errors'] : json_encode($response_data['errors'], JSON_UNESCAPED_UNICODE);
             }
         }
         error_log('IranPayamak SMS Error: ' . $error_msg);
         return ['success' => false, 'message' => $error_msg];
     }
 
     return ['success' => true, 'message' => 'SMS sent successfully'];
 }
 
 /**
  * Convert Iranian phone numbers to a local mobile format such as 09XXXXXXXXX.
  *
  * @param string $phone Phone number to format
  * @return string Formatted phone number or empty string if invalid
  */
 function format_phone_number_local($phone) {
     $phone = preg_replace('/[^0-9]/', '', $phone);
 
     if (strpos($phone, '0098') === 0) {
         $phone = '0' . substr($phone, 4);
     } elseif (strpos($phone, '98') === 0) {
         $phone = '0' . substr($phone, 2);
     } elseif (strpos($phone, '9') === 0 && strlen($phone) === 10) {
         $phone = '0' . $phone;
     }
 
     if (!preg_match('/^09[0-9]{9}$/', $phone)) {
         return '';
     }
 
     return $phone;
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
