<?php

/**
 * Response Helper Class
 * Standardized JSON response formatting
 */

class Response
{

    /**
     * Send success response
     */
    public static function success($data = null, $message = 'Success', $httpCode = 200)
    {
        http_response_code($httpCode);

        $response = [
            'status' => 'success',
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Send error response
     */
    public static function error($message = 'Error', $httpCode = 400, $details = null)
    {
        http_response_code($httpCode);

        $response = [
            'status' => 'error',
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        if ($details !== null) {
            $response['details'] = $details;
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Send not found response
     */
    public static function notFound($message = 'Resource not found')
    {
        self::error($message, 404);
    }

    /**
     * Send unauthorized response
     */
    public static function unauthorized($message = 'Unauthorized access')
    {
        self::error($message, 401);
    }

    /**
     * Send method not allowed response
     */
    public static function methodNotAllowed($message = 'Method not allowed')
    {
        self::error($message, 405);
    }

    /**
     * Send server error response
     */
    public static function serverError($message = 'Internal server error')
    {
        self::error($message, 500);
    }

    /**
     * Sanitize data for output
     */
    public static function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }

        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        return $data;
    }

    /**
     * Format file URL
     */
    public static function formatFileUrl($codeFile, $kind)
    {
        return BASE_URL . FILES_BASE_PATH . $codeFile . '.' . $kind;
    }
}
