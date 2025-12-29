<?php

$code_ticket = str_g('code');

if ($code_ticket != "") {
    
    $name_vaziat_gha = "";
    $name_vaziat = "";
    $code_p_karbar_anjam = "";
    
    // Escape the ticket code for SQL
    $code_ticket_escaped = mysqli_real_escape_string($Link, $code_ticket);
    
    // Get current ticket information
    $Query_list = "SELECT * FROM ticket WHERE code = '$code_ticket_escaped' ORDER BY i_ticket DESC LIMIT 1";
    if ($Result_list = mysqli_query($Link, $Query_list)) {
        while ($q_gar = mysqli_fetch_array($Result_list)) {
            $vaziat_ticket = $q_gar['vaziat'];
            $log_txt = $q_gar['log_txt'];
            $code_p_karbar_anjam = $q_gar['code_p_karbar_anjam'];
            
            // Get status name
            if ($vaziat_ticket == "a") { $name_vaziat = "ثبت اولیه"; }
            if ($vaziat_ticket == "m") { $name_vaziat = "در حال بررسی"; }
            if ($vaziat_ticket == "w") { $name_vaziat = "روی میز"; }
            if ($vaziat_ticket == "b") { $name_vaziat = "بسته شده"; }
            if ($vaziat_ticket == "t") { $name_vaziat = "بررسی مجدد"; }
            if ($vaziat_ticket == "c") { $name_vaziat = "کنسل شده"; }
            if ($vaziat_ticket == "k") { $name_vaziat = "انجام شد"; }
        }
    }
    
    // Only proceed if ticket has an assigned user
    if ($code_p_karbar_anjam != "") {
        
        $name_vaziat_gha = "روی میز";
        $revaziat = "w";
        $revaziat2 = "m"; // For pasokh and file_pasokh tables
        
        // Update log
        $log_txt = $log_txt . " تغییر وضعیت از حالت $name_vaziat به $name_vaziat_gha در تاریخ $tarikh - $saat <br>";
        
        // Build query to:
        // 1. Set all other "working on" tickets for this user to "در حال بررسی" (m)
        // 2. Set the current ticket to "working on" (w)
        
        // Escape variables for SQL
        $code_p_karbar_anjam_escaped = mysqli_real_escape_string($Link, $code_p_karbar_anjam);
        
        $log_message_other = " تغییر وضعیت از حالت روی میز به در حال بررسی در تاریخ $tarikh - $saat <br>";
        $Qery = "UPDATE `ticket` SET 
                 `vaziat` = 'm',
                 `log_txt` = CONCAT(`log_txt`, '" . mysqli_real_escape_string($Link, $log_message_other) . "')
                 WHERE `code_p_karbar_anjam` = '$code_p_karbar_anjam_escaped' 
                 AND `vaziat` = 'w' 
                 AND `code` != '$code_ticket_escaped'; ";
        
        $log_txt_escaped = mysqli_real_escape_string($Link, $log_txt);
        $Qery .= "UPDATE `ticket` SET
                 `vaziat` = '$revaziat',
                 `log_txt` = '$log_txt_escaped'
                 WHERE `code` = '$code_ticket_escaped'; ";
        
        $Qery .= "UPDATE `pasokh` SET
                 `vaziat` = '$revaziat2'
                 WHERE `code_ticket` = '$code_ticket_escaped'; ";
        
        $Qery .= "UPDATE `file_pasokh` SET
                 `vaziat` = '$revaziat2'
                 WHERE `code_ticket` = '$code_ticket_escaped'; ";
        
        if ($Link->multi_query($Qery) === TRUE) {
            // Clear multi_query results
            while ($Link->next_result()) {
                if ($result = $Link->store_result()) {
                    $result->free();
                }
            }
            header("location: ?page=info_ticket&code=$code_ticket&p=y");
            exit;
        } else {
            // Log error for debugging
            error_log("set_working_on error: " . $Link->error);
            header("location: ?page=info_ticket&code=$code_ticket&p=n");
            exit;
        }
    } else {
        // Ticket has no assigned user
        header("location: ?page=info_ticket&code=$code_ticket&p=t");
        exit;
    }
} else {
    // No ticket code provided
    header("location: ?page=list_ticket&p=t");
    exit;
}
?>

