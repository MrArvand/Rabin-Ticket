<?php

$code_ticket = str_g('code_ticket');
$daste_new = str_g('daste');

if (!empty($code_ticket) && !empty($daste_new)) {
    
    // Get current ticket information
    $query = "SELECT t.*, d.name AS name_daste_new 
              FROM ticket t 
              LEFT JOIN departman d ON d.id = '$daste_new' 
              WHERE t.code = '$code_ticket' 
              ORDER BY t.i_ticket DESC LIMIT 1";
    
    $result = mysqli_query($Link, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        $old_daste = $row['daste'];
        $old_name_daste = $row['name_daste'];
        $new_name_daste = $row['name_daste_new'] ? $row['name_daste_new'] : $daste_new;
        $log_txt = $row['log_txt'];
        
        // Update ticket category
        $log_txt = $log_txt . " تغییر دسته بندی از " . $old_name_daste . " (" . $old_daste . ") به " . $new_name_daste . " (" . $daste_new . ") در تاریخ " . $tarikh . " - " . $saat . " توسط " . $name_karbar_run . " <br>";
        
        $Qery = "UPDATE `ticket` SET 
                 `daste` = '$daste_new', 
                 `name_daste` = '$new_name_daste',
                 `log_txt` = '$log_txt' 
                 WHERE `code` = '$code_ticket'; ";
        
        if (mysqli_query($Link, $Qery)) {
            header("location: ?page=info_ticket&code=$code_ticket&p=y&cat=changed");
            exit;
        } else {
            header("location: ?page=info_ticket&code=$code_ticket&p=n&cat=error");
            exit;
        }
    } else {
        header("location: ?page=info_ticket&code=$code_ticket&p=t");
        exit;
    }
} else {
    header("location: ?page=info_ticket&code=$code_ticket&p=t");
    exit;
}

?>

