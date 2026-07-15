<?php
// get_departments.php
session_start();
require_once '../../../inf/date.php'; // Your database connection file
require_once __DIR__ . '/../../inf/restricted_department_sadgan_fx.php';

header('Content-Type: application/json');

if (isset($_GET['company_code'])) {
    $companyCode = mysqli_real_escape_string($Link, $_GET['company_code']);
    $current_user_code_for_department_visibility = isset($_SESSION['code_p']) ? trim((string) $_SESSION['code_p']) : '';
    $can_view_restricted_department = can_view_restricted_department($current_user_code_for_department_visibility);
    $restricted_department_sql = mysqli_real_escape_string($Link, $restricted_department_id);

    $divisionCode = isset($_GET['division_code']) ? trim((string) $_GET['division_code']) : '';

    $Query_dep = "SELECT * FROM departman 
                  WHERE vaziat = 'y' 
                  AND default_company_code = '$companyCode' ";

    // Division filter: specific division, "no division", or all (no filter)
    if ($divisionCode === '__none__') {
        $Query_dep .= "AND (division_code IS NULL OR division_code = '') ";
    } elseif ($divisionCode !== '') {
        $divisionCodeSql = mysqli_real_escape_string($Link, $divisionCode);
        $Query_dep .= "AND division_code = '$divisionCodeSql' ";
    }

    if (!$can_view_restricted_department) {
        $Query_dep .= "AND id <> '$restricted_department_sql' ";
    }
    $Query_dep .= "
                  ORDER BY name ASC 
                  LIMIT 200";

    $departments = array();

    if ($Result_dep = mysqli_query($Link, $Query_dep)) {
        while ($row = mysqli_fetch_assoc($Result_dep)) {
            $departments[] = array(
                'id' => $row['id'],
                'name' => $row['name']
            );
        }
    }

    echo json_encode($departments);
} else {
    echo json_encode([]);
}
