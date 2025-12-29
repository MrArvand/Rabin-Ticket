<?php

$faal = str_p('faal');
$daste = str_p('daste');
$sherkat = str_p('sherkat');
$tarikh_e = str_p('tarikh_e');
$saat_e = str_p('saat_e');
$saat_e_end = str_p('saat_e_end'); // End time

$modat = str_p('modat');

$vaziat = "y";

$titr = str_p('titr');

// Calculate duration automatically if start and end times are provided
$calculated_duration = $modat; // Default to manual input

if (!empty($tarikh_e) && !empty($saat_e) && !empty($saat_e_end)) {
    try {
        // Parse start time
        $exp_saat_start = explode(':', $saat_e);
        $start_hour = (int)$exp_saat_start[0];
        $start_min = (int)($exp_saat_start[1] ?? 0);
        
        // Parse end time
        $exp_saat_end = explode(':', $saat_e_end);
        $end_hour = (int)$exp_saat_end[0];
        $end_min = (int)($exp_saat_end[1] ?? 0);
        
        // Calculate duration in minutes
        $start_minutes = ($start_hour * 60) + $start_min;
        $end_minutes = ($end_hour * 60) + $end_min;
        
        // Handle case where end time is next day (end < start)
        if ($end_minutes < $start_minutes) {
            $end_minutes += (24 * 60); // Add 24 hours
        }
        
        $calculated_duration = $end_minutes - $start_minutes;
        
        // Use calculated duration if it's valid and greater than 0
        if ($calculated_duration > 0) {
            $modat = $calculated_duration;
        }
    } catch (Exception $e) {
        // If calculation fails, use manual input
        error_log("Duration calculation error: " . $e->getMessage());
    }
}

if ($faal != "" || $daste != "" || $titr != "" || $sherkat != "" || $tarikh_e != "" || $saat_e != "" || $modat != "") {

    //---------------------------------------------------------

    $code_kar = "K-" . time() . "-" . rand(11, 99);

    // Escape variables for SQL
    $tarikh_e_escaped = mysqli_real_escape_string($Link, $tarikh_e);
    $saat_e_escaped = mysqli_real_escape_string($Link, $saat_e);
    $saat_e_end_escaped = mysqli_real_escape_string($Link, $saat_e_end);
    $daste_escaped = mysqli_real_escape_string($Link, $daste);
    $titr_escaped = mysqli_real_escape_string($Link, $titr);
    $modat_escaped = mysqli_real_escape_string($Link, $modat);
    $faal_escaped = mysqli_real_escape_string($Link, $faal);
    $sherkat_escaped = mysqli_real_escape_string($Link, $sherkat);
    $name_karbar_run_escaped = mysqli_real_escape_string($Link, $name_karbar_run);

    $Qery = "INSERT INTO `karkerd` (`code_p`, `name_karbar`, `tarikh_s`, `saat_s`, `tarikh_e`, `saat_e`, `daste`, `matn`, `zaman`, `faal`, `mortabet`, `vaziat`, `code`, `i_karkerd`) 
VALUES ('$code_p_run', '$name_karbar_run_escaped', '$tarikh_e_escaped', '$saat_e_escaped', '$tarikh_e_escaped', '$saat_e_end_escaped', '$daste_escaped', '$titr_escaped', '$modat_escaped', '$faal_escaped', '$sherkat_escaped', '$vaziat', '$code_kar', NULL);";

    if ($Link->query($Qery) === TRUE) {
        header("location: ?page=my_work&p=y");
    } else {
        header("location: ?page=my_work&p=n");
    }
} else {
    header("location: ?page=my_work&p=n");
}
?>
