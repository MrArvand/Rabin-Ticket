<?php

/**
 * Fixed API Router with better error handling
 */

// Include required files
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/Response.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/models/Ticket.php';
require_once __DIR__ . '/controllers/TicketController.php';

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // Initialize database connection
    $database = new Database();
    $db = $database->connect();

    // Initialize authentication middleware
    $auth = new AuthMiddleware($API_KEYS);

    // Validate API key
    $auth->validateApiKey();

    // Get API key for rate limiting and filtering (case-insensitive)
    $apiKey = '';
    foreach ($_SERVER as $key => $value) {
        if (strtolower($key) === 'http_x_api_key') {
            $apiKey = $value;
            break;
        }
    }
    
    if (empty($apiKey)) {
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
    }
    
    $auth->checkRateLimit($apiKey);

    // Initialize models and controllers with API key
    $ticketModel = new Ticket($db, $apiKey);
    $ticketController = new TicketController($ticketModel);

    // Parse request
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    // Remove query string and base path
    $path = parse_url($requestUri, PHP_URL_PATH);

    // Handle different path formats
    if (strpos($path, '/api/v1/') === 0) {
        $path = substr($path, 8); // Remove '/api/v1/'
    } elseif (strpos($path, '/api/v1') === 0) {
        $path = substr($path, 7); // Remove '/api/v1'
    }

    $path = trim($path, '/');

    // Route requests
    routeRequest($path, $requestMethod, $ticketController);
} catch (Exception $e) {
    // Log the error for debugging
    error_log('API Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());

    Response::serverError('Internal server error: ' . $e->getMessage());
}

/**
 * Route requests to appropriate controller methods
 */
function routeRequest($path, $method, $ticketController)
{

    // Only allow GET requests
    if ($method !== 'GET') {
        Response::methodNotAllowed('Only GET requests are allowed');
    }

    // Parse path segments
    $segments = explode('/', $path);

    // Route based on path
    switch (true) {
        case $path === '' || $path === 'tickets':
            // GET /api/v1/tickets
            $ticketController->getAllTickets();
            break;

        case $path === 'tickets/today':
            // GET /api/v1/tickets/today
            $ticketController->getTodayTickets();
            break;

        case preg_match('/^tickets\/([^\/]+)$/', $path, $matches):
            // GET /api/v1/tickets/{code}
            $ticketController->getTicket($matches[1]);
            break;

        case preg_match('/^tickets\/([^\/]+)\/responses$/', $path, $matches):
            // GET /api/v1/tickets/{code}/responses
            $ticketController->getTicketResponses($matches[1]);
            break;

        case preg_match('/^tickets\/([^\/]+)\/files$/', $path, $matches):
            // GET /api/v1/tickets/{code}/files
            $ticketController->getTicketFiles($matches[1]);
            break;

        case $path === 'health' || $path === 'status':
            // Health check endpoint
            Response::success(['status' => 'healthy', 'timestamp' => date('Y-m-d H:i:s')]);
            break;

        default:
            Response::notFound('Endpoint not found');
    }
}
