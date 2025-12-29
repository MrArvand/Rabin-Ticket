<?php

/**
 * Ticket Controller
 * Handles HTTP requests for ticket endpoints
 */

class TicketController
{

    private $ticketModel;

    public function __construct($ticketModel)
    {
        $this->ticketModel = $ticketModel;
    }

    /**
     * Handle GET /api/v1/tickets
     * List all tickets
     */
    public function getAllTickets()
    {
        try {
            $tickets = $this->ticketModel->getAllTickets();
            Response::success($tickets, 'Tickets retrieved successfully');
        } catch (Exception $e) {
            Response::serverError('Failed to retrieve tickets: ' . $e->getMessage());
        }
    }

    /**
     * Handle GET /api/v1/tickets/today
     * List tickets from current day only
     */
    public function getTodayTickets()
    {
        try {
            $tickets = $this->ticketModel->getTodayTickets();
            Response::success($tickets, 'Today\'s tickets retrieved successfully');
        } catch (Exception $e) {
            Response::serverError('Failed to retrieve today\'s tickets: ' . $e->getMessage());
        }
    }

    /**
     * Handle GET /api/v1/tickets/{code}
     * Get single ticket details
     */
    public function getTicket($code)
    {
        try {
            // Validate ticket code format
            if (!$this->isValidTicketCode($code)) {
                Response::error('Invalid ticket code format', 400);
            }

            $ticket = $this->ticketModel->getTicketByCode($code);

            if (!$ticket) {
                Response::notFound('Ticket not found');
            }

            Response::success($ticket, 'Ticket details retrieved successfully');
        } catch (Exception $e) {
            Response::serverError('Failed to retrieve ticket: ' . $e->getMessage());
        }
    }

    /**
     * Handle GET /api/v1/tickets/{code}/responses
     * Get all responses for a ticket
     */
    public function getTicketResponses($code)
    {
        try {
            // Validate ticket code format
            if (!$this->isValidTicketCode($code)) {
                Response::error('Invalid ticket code format', 400);
            }

            // Check if ticket exists
            $ticket = $this->ticketModel->getTicketByCode($code);
            if (!$ticket) {
                Response::notFound('Ticket not found');
            }

            $responses = $this->ticketModel->getTicketResponses($code);
            Response::success($responses, 'Ticket responses retrieved successfully');
        } catch (Exception $e) {
            Response::serverError('Failed to retrieve ticket responses: ' . $e->getMessage());
        }
    }

    /**
     * Handle GET /api/v1/tickets/{code}/files
     * Get all file attachments for a ticket
     */
    public function getTicketFiles($code)
    {
        try {
            // Validate ticket code format
            if (!$this->isValidTicketCode($code)) {
                Response::error('Invalid ticket code format', 400);
            }

            // Check if ticket exists
            $ticket = $this->ticketModel->getTicketByCode($code);
            if (!$ticket) {
                Response::notFound('Ticket not found');
            }

            $files = $this->ticketModel->getTicketFiles($code);
            Response::success($files, 'Ticket files retrieved successfully');
        } catch (Exception $e) {
            Response::serverError('Failed to retrieve ticket files: ' . $e->getMessage());
        }
    }

    /**
     * Validate ticket code format
     */
    private function isValidTicketCode($code)
    {
        // Ticket codes are typically in format: T-1234567890-12 or similar
        // Allow alphanumeric with hyphens and underscores
        return preg_match('/^[A-Za-z0-9\-_]+$/', $code) && strlen($code) >= 3;
    }

    /**
     * Handle OPTIONS requests for CORS
     */
    public function handleOptions()
    {
        http_response_code(200);
        exit;
    }
}
