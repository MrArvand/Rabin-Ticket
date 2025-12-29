<?php

/**
 * API Configuration
 */

// Include Jalali date functions
require_once 'jdf.php';

// API Keys (in production, store in database)
$API_KEYS = [
    'prVDNnL6uZv7Y9zxs7QdMEyF9ctysYatMxZBiGcVpcJGy7ViCObilgcgTKD0rXlp' => [
        'name' => 'BPMS',
        'created_at' => '2024-01-01',
        'status' => 'active'
    ]
];

// BPMS specific user codes that need filtering
define('BPMS_USER_CODES', [
    '1100113',
    '26519',
    '1100064',
    '1100119',
    '25662',
    '1100116',
    '1100100'
]);

// BPMS API Key
define('BPMS_API_KEY', 'prVDNnL6uZv7Y9zxs7QdMEyF9ctysYatMxZBiGcVpcJGy7ViCObilgcgTKD0rXlp');

// Base URL for file attachments
define('BASE_URL', 'https://request-r.ir'); // Update with your domain
define('FILES_BASE_PATH', '/files/peyvast/');

// Response headers
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// CORS headers (adjust as needed)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: X-API-Key, Content-Type');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in production

// Priority labels mapping
$PRIORITY_LABELS = [
    '1' => 'ضروری',
    '2' => 'متوسط',
    '3' => 'معمولی',
    '4' => 'پایین'
];

// Status labels mapping
$STATUS_LABELS = [
    'a' => 'ثبت اولیه',
    'm' => 'درحال بررسی',
    'b' => 'بسته شده',
    'k' => 'انجام شده',
    'c' => 'کنسل شده',
    't' => 'بررسی مجدد'
];

// Rate limiting (requests per minute)
define('RATE_LIMIT', 6000);
define('RATE_WINDOW', 6000); // seconds