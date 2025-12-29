<?php

/**
 * Ticket Model
 * Handles database operations for tickets
 */

class Ticket
{

    private $db;
    private $apiKey;

    public function __construct($database, $apiKey = null)
    {
        $this->db = $database;
        $this->apiKey = $apiKey;
    }

    /**
     * Get all tickets with basic information
     */
    public function getAllTickets()
    {
        try {
            // Check if BPMS API key is being used
            $isBpms = ($this->apiKey === BPMS_API_KEY);

            // Subquery placeholders for BPMS user codes
            $bpmsPlaceholders = implode(',', array_fill(0, count(BPMS_USER_CODES), '?'));

            // Subqueries to get first refer information (oldest response from BPMS users)
            $firstReferCodeSubquery = "(SELECT p_first.code_karbar_sabt 
                                        FROM pasokh p_first 
                                        WHERE p_first.code_ticket = t.code 
                                        AND p_first.code_karbar_sabt IN ($bpmsPlaceholders) 
                                        ORDER BY p_first.i_pasokh ASC 
                                        LIMIT 1)";

            $firstReferDateSubquery = "(SELECT p_first.tarikh_sabt 
                                        FROM pasokh p_first 
                                        WHERE p_first.code_ticket = t.code 
                                        AND p_first.code_karbar_sabt IN ($bpmsPlaceholders) 
                                        ORDER BY p_first.i_pasokh ASC 
                                        LIMIT 1)";

            $firstReferTimeSubquery = "(SELECT p_first.saat_sabt 
                                        FROM pasokh p_first 
                                        WHERE p_first.code_ticket = t.code 
                                        AND p_first.code_karbar_sabt IN ($bpmsPlaceholders) 
                                        ORDER BY p_first.i_pasokh ASC 
                                        LIMIT 1)";

            if ($isBpms) {
                // For BPMS, only show tickets that have code_p_karbar_anjam matching any BPMS user codes
                $placeholders = implode(',', array_fill(0, count(BPMS_USER_CODES), '?'));

                $sql = "SELECT 
                            t.code,
                            t.titr,
                            t.olaviat,
                            t.matn,
                            t.tarikh_sabt,
                            t.saat_sabt,
                            t.vaziat,
                            t.name_karbar,
                            t.name_sherkat,
                            t.name_daste,
                            t.code_p_karbar_anjam,
                            t.name_karbar_anjam,
                            $firstReferCodeSubquery as first_refer_user_code,
                            $firstReferDateSubquery as first_refer_date,
                            $firstReferTimeSubquery as first_refer_time
                        FROM ticket t 
                        WHERE t.code_p_karbar_anjam IN ($placeholders)
                        ORDER BY t.i_ticket DESC";

                $stmt = $this->db->prepare($sql);
                // Parameters: 3 subqueries (3x BPMS codes) + 1 WHERE clause (1x BPMS codes) = 4 times
                $params = array_merge(BPMS_USER_CODES, BPMS_USER_CODES, BPMS_USER_CODES, BPMS_USER_CODES);
                $stmt->execute($params);
            } else {
                $sql = "SELECT 
                            t.code,
                            t.titr,
                            t.olaviat,
                            t.matn,
                            t.tarikh_sabt,
                            t.saat_sabt,
                            t.vaziat,
                            t.name_karbar,
                            t.name_sherkat,
                            t.name_daste,
                            t.code_p_karbar_anjam,
                            t.name_karbar_anjam,
                            $firstReferCodeSubquery as first_refer_user_code,
                            $firstReferDateSubquery as first_refer_date,
                            $firstReferTimeSubquery as first_refer_time
                        FROM ticket t 
                        ORDER BY t.i_ticket DESC";

                $stmt = $this->db->prepare($sql);
                // Parameters: BPMS codes (x4 for 4 subqueries)
                $params = array_merge(BPMS_USER_CODES, BPMS_USER_CODES, BPMS_USER_CODES, BPMS_USER_CODES);
                $stmt->execute($params);
            }

            $tickets = $stmt->fetchAll();

            // Format the response
            return array_map([$this, 'formatTicketBasic'], $tickets);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get tickets from current day only
     */
    public function getTodayTickets()
    {
        try {
            // Get current Persian date in Ymd format
            $today = jdate('Ymd');

            // Check if BPMS API key is being used
            $isBpms = ($this->apiKey === BPMS_API_KEY);

            // Subquery placeholders for BPMS user codes
            $bpmsPlaceholders = implode(',', array_fill(0, count(BPMS_USER_CODES), '?'));

            // Subqueries to get first refer information (oldest response from BPMS users)
            $firstReferCodeSubquery = "(SELECT p_first.code_karbar_sabt 
                                        FROM pasokh p_first 
                                        WHERE p_first.code_ticket = t.code 
                                        AND p_first.code_karbar_sabt IN ($bpmsPlaceholders) 
                                        ORDER BY p_first.i_pasokh ASC 
                                        LIMIT 1)";

            $firstReferDateSubquery = "(SELECT p_first.tarikh_sabt 
                                        FROM pasokh p_first 
                                        WHERE p_first.code_ticket = t.code 
                                        AND p_first.code_karbar_sabt IN ($bpmsPlaceholders) 
                                        ORDER BY p_first.i_pasokh ASC 
                                        LIMIT 1)";

            $firstReferTimeSubquery = "(SELECT p_first.saat_sabt 
                                        FROM pasokh p_first 
                                        WHERE p_first.code_ticket = t.code 
                                        AND p_first.code_karbar_sabt IN ($bpmsPlaceholders) 
                                        ORDER BY p_first.i_pasokh ASC 
                                        LIMIT 1)";

            if ($isBpms) {
                // For BPMS, only show tickets that have code_p_karbar_anjam matching any BPMS user codes AND responses from today
                $placeholders = implode(',', array_fill(0, count(BPMS_USER_CODES), '?'));

                $sql = "SELECT DISTINCT
                            t.code,
                            t.titr,
                            t.olaviat,
                            t.matn,
                            t.tarikh_sabt,
                            t.saat_sabt,
                            t.vaziat,
                            t.name_karbar,
                            t.name_sherkat,
                            t.name_daste,
                            t.code_p_karbar_anjam,
                            t.name_karbar_anjam,
                            $firstReferCodeSubquery as first_refer_user_code,
                            $firstReferDateSubquery as first_refer_date,
                            $firstReferTimeSubquery as first_refer_time
                        FROM ticket t 
                        INNER JOIN pasokh p ON t.code = p.code_ticket
                        WHERE t.code_p_karbar_anjam IN ($placeholders)
                        AND REPLACE(REPLACE(REPLACE(p.tarikh_sabt, '/', ''), '-', ''), '_', '') = ?
                        ORDER BY t.i_ticket DESC";

                // Parameters: 3 subqueries (3x BPMS codes) + 1 WHERE clause (1x BPMS codes) + 1 today = 4 times BPMS codes + today
                $params = array_merge(BPMS_USER_CODES, BPMS_USER_CODES, BPMS_USER_CODES, BPMS_USER_CODES, [$today]);
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
            } else {
                // For regular users, show tickets that have at least one response from today
                $sql = "SELECT DISTINCT
                            t.code,
                            t.titr,
                            t.olaviat,
                            t.matn,
                            t.tarikh_sabt,
                            t.saat_sabt,
                            t.vaziat,
                            t.name_karbar,
                            t.name_sherkat,
                            t.name_daste,
                            t.code_p_karbar_anjam,
                            t.name_karbar_anjam,
                            $firstReferCodeSubquery as first_refer_user_code,
                            $firstReferDateSubquery as first_refer_date,
                            $firstReferTimeSubquery as first_refer_time
                        FROM ticket t 
                        INNER JOIN pasokh p ON t.code = p.code_ticket
                        WHERE REPLACE(REPLACE(REPLACE(p.tarikh_sabt, '/', ''), '-', ''), '_', '') = ?
                        ORDER BY t.i_ticket DESC";

                $stmt = $this->db->prepare($sql);
                // Parameters: 3 subqueries (3x BPMS codes) + 1 today = 3 times BPMS codes + today
                $params = array_merge(BPMS_USER_CODES, BPMS_USER_CODES, BPMS_USER_CODES, [$today]);
                $stmt->execute($params);
            }

            $tickets = $stmt->fetchAll();

            // Format the response
            return array_map([$this, 'formatTicketBasic'], $tickets);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get single ticket with full details
     */
    public function getTicketByCode($code)
    {
        try {
            $sql = "SELECT 
                        t.*,
                        k.tel as requester_phone,
                        s.name as company_name,
                        d.name as department_name
                    FROM ticket t
                    LEFT JOIN karbar k ON t.code_p_karbar = k.code_p
                    LEFT JOIN sherkatha s ON t.code_sherkat = s.code
                    LEFT JOIN departman d ON t.daste = d.id
                    WHERE t.code = :code
                    LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':code', $code, PDO::PARAM_STR);
            $stmt->execute();

            $ticket = $stmt->fetch();

            if (!$ticket) {
                return null;
            }

            // Get responses and files
            $responses = $this->getTicketResponses($code);
            $files = $this->getTicketFiles($code);

            return $this->formatTicketFull($ticket, $responses, $files);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get all responses for a ticket
     */
    public function getTicketResponses($code)
    {
        try {
            $sql = "SELECT 
                        p.code,
                        p.code_karbar_sabt,
                        p.name_karbar_sabt,
                        p.code_karbar2,
                        p.name_karbar2,
                        p.matn,
                        p.tarikh_sabt,
                        p.saat_sabt,
                        p.oksee,
                        p.tarikh_see,
                        p.saat_see
                    FROM pasokh p
                    WHERE p.code_ticket = :code
                    ORDER BY p.i_pasokh ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':code', $code, PDO::PARAM_STR);
            $stmt->execute();

            $responses = $stmt->fetchAll();

            return array_map([$this, 'formatResponse'], $responses);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get all file attachments for a ticket
     */
    public function getTicketFiles($code)
    {
        try {
            $sql = "SELECT 
                        f.code_file,
                        f.titr,
                        f.kind,
                        f.hajm,
                        f.vaziat
                    FROM file_pasokh f
                    WHERE f.code_ticket = :code
                    ORDER BY f.i_file ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':code', $code, PDO::PARAM_STR);
            $stmt->execute();

            $files = $stmt->fetchAll();

            return array_map([$this, 'formatFile'], $files);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Format basic ticket information
     */
    private function formatTicketBasic($ticket)
    {
        global $PRIORITY_LABELS, $STATUS_LABELS;

        return [
            'ticket_code' => $ticket['code'],
            'title' => $ticket['titr'],
            'description' => $ticket['matn'],
            'priority' => $ticket['olaviat'],
            'priority_label' => $PRIORITY_LABELS[$ticket['olaviat']] ?? 'نامشخص',
            'status' => $ticket['vaziat'],
            'status_label' => $STATUS_LABELS[$ticket['vaziat']] ?? 'نامشخص',
            'requester_name' => $ticket['name_karbar'],
            'company_name' => $ticket['name_sherkat'],
            'department' => $ticket['name_daste'],
            'current_handler' => $ticket['name_karbar_anjam'],
            'current_handler_code' => $ticket['code_p_karbar_anjam'],
            'request_date' => $ticket['tarikh_sabt'],
            'request_time' => $ticket['saat_sabt'],
            'first_refer_user_code' => $ticket['first_refer_user_code'] ?? null,
            'first_refer_date' => $ticket['first_refer_date'] ?? null,
            'first_refer_time' => $ticket['first_refer_time'] ?? null
        ];
    }

    /**
     * Format full ticket information
     */
    private function formatTicketFull($ticket, $responses, $files)
    {
        global $PRIORITY_LABELS, $STATUS_LABELS;

        return [
            'ticket_code' => $ticket['code'],
            'title' => $ticket['titr'],
            'description' => $ticket['matn'],
            'priority' => $ticket['olaviat'],
            'priority_label' => $PRIORITY_LABELS[$ticket['olaviat']] ?? 'نامشخص',
            'requester' => [
                'code' => $ticket['code_p_karbar'],
                'name' => $ticket['name_karbar'],
                'phone' => $ticket['requester_phone']
            ],
            'company' => [
                'code' => $ticket['code_sherkat'],
                'name' => $ticket['company_name'] ?: $ticket['name_sherkat']
            ],
            'request_date' => $ticket['tarikh_sabt'],
            'request_time' => $ticket['saat_sabt'],
            'current_handler' => [
                'code' => $ticket['code_p_karbar_anjam'],
                'name' => $ticket['name_karbar_anjam']
            ],
            'status' => $ticket['vaziat'],
            'status_label' => $STATUS_LABELS[$ticket['vaziat']] ?? 'نامشخص',
            'department' => $ticket['department_name'] ?: $ticket['name_daste'],
            'attachments' => $files,
            'responses' => $responses
        ];
    }

    /**
     * Format response information
     */
    private function formatResponse($response)
    {
        return [
            'code' => $response['code'],
            'author' => $response['name_karbar_sabt'],
            'message' => $response['matn'],
            'date' => $response['tarikh_sabt'],
            'time' => $response['saat_sabt'],
            'is_read' => $response['oksee'],
            'read_date' => $response['tarikh_see'],
            'read_time' => $response['saat_see']
        ];
    }

    /**
     * Format file information
     */
    private function formatFile($file)
    {
        return [
            'code' => $file['code_file'],
            'title' => $file['titr'],
            'type' => $file['kind'],
            'size' => $file['hajm'],
            'url' => Response::formatFileUrl($file['code_file'], $file['kind']),
            'status' => $file['vaziat']
        ];
    }
}
