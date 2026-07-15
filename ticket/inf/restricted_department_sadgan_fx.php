<?php
/**
 * Sadgan FX department visibility for new-ticket flow.
 * Used by page UI (start_ticket) and AJAX (get_departments) — keep a single source of truth.
 */
$restricted_department_id = 'sadgan-fx';
$restricted_department_allowed_users = ['1008', '1000009', '1200050', '1003'];

function can_view_restricted_department($user_code)
{
    global $restricted_department_allowed_users;

    return in_array(trim((string) $user_code), $restricted_department_allowed_users, true);
}

function is_restricted_department($department_id)
{
    global $restricted_department_id;

    return (string) $department_id === $restricted_department_id;
}
