<?php
// get_divisions.php
// Returns the active divisions (معاونت) for a given company, for the
// company => division => department cascade on the new-ticket page.
session_start();
require_once '../../../inf/date.php'; // Database connection ($Link)

header('Content-Type: application/json');

if (isset($_GET['company_code']) && $_GET['company_code'] !== '') {
    $companyCode = mysqli_real_escape_string($Link, $_GET['company_code']);

    $Query_div = "SELECT id, name FROM moavenat
                  WHERE vaziat = 'y'
                  AND company_code = '$companyCode'
                  ORDER BY sort_order ASC, name ASC
                  LIMIT 200";

    $divisions = array();

    if ($Result_div = mysqli_query($Link, $Query_div)) {
        while ($row = mysqli_fetch_assoc($Result_div)) {
            $divisions[] = array(
                'id' => $row['id'],
                'name' => $row['name']
            );
        }
    }

    echo json_encode($divisions);
} else {
    echo json_encode([]);
}
