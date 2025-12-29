<?php

/**
 * Authentication Middleware
 * Handles API key validation
 */

class AuthMiddleware
{

    private $apiKeys;

    public function __construct($apiKeys)
    {
        $this->apiKeys = $apiKeys;
    }

    /**
     * Validate API key from request headers
     */
    public function validateApiKey()
    {
        $apiKey = $this->getApiKeyFromHeaders();

        if (!$apiKey) {
            Response::unauthorized('API key is required. Please provide X-API-Key header.');
        }

        if (!isset($this->apiKeys[$apiKey])) {
            Response::unauthorized('Invalid API key.');
        }

        $keyInfo = $this->apiKeys[$apiKey];

        if ($keyInfo['status'] !== 'active') {
            Response::unauthorized('API key is inactive.');
        }

        // Log API access (in production, use proper logging)
        $this->logApiAccess($apiKey);

        return true;
    }

    /**
     * Get API key from request headers
     */
    private function getApiKeyFromHeaders()
    {
        $headers = getallheaders();

        // Check for X-API-Key header (case-insensitive)
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'x-api-key') {
                return $value;
            }
        }

        // Check for Authorization header (Bearer token format)
        if (isset($headers['Authorization'])) {
            $auth = $headers['Authorization'];
            if (strpos($auth, 'Bearer ') === 0) {
                return substr($auth, 7);
            }
        }

        return null;
    }

    /**
     * Log API access attempts
     */
    private function logApiAccess($apiKey)
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'api_key' => substr($apiKey, 0, 10) . '...', // Partial key for security
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'endpoint' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ];

        // In production, use proper logging system
        error_log('API Access: ' . json_encode($logData));
    }

    /**
     * Simple rate limiting (in production, use Redis or database)
     */
    public function checkRateLimit($apiKey)
    {
        // Simple file-based rate limiting
        $rateLimitFile = sys_get_temp_dir() . '/api_rate_limit_' . md5($apiKey) . '.json';

        $currentTime = time();
        $windowStart = $currentTime - RATE_WINDOW;

        if (file_exists($rateLimitFile)) {
            $data = json_decode(file_get_contents($rateLimitFile), true);

            // Remove old entries
            $data = array_filter($data, function ($timestamp) use ($windowStart) {
                return $timestamp > $windowStart;
            });

            if (count($data) >= RATE_LIMIT) {
                Response::error('Rate limit exceeded. Too many requests.', 429);
            }

            $data[] = $currentTime;
        } else {
            $data = [$currentTime];
        }

        file_put_contents($rateLimitFile, json_encode($data));
    }
}
