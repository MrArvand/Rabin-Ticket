<?php

$name_daste=str_p('name_daste');
$id_daste=str_p('id_daste');

// Optional receiver company chosen at creation time (company => division => department)
$daste_company_code = mysqli_real_escape_string($Link, str_p('daste_company_code'));
if ($daste_company_code === '0') {
    $daste_company_code = '';
}
$daste_company_name = '';
if ($daste_company_code !== '') {
    $company_lookup = "SELECT name FROM sherkatha WHERE code = '$daste_company_code' LIMIT 1";
    if ($company_result = mysqli_query($Link, $company_lookup)) {
        if ($company_row = mysqli_fetch_assoc($company_result)) {
            $daste_company_name = mysqli_real_escape_string($Link, $company_row['name']);
        }
    }
}


if($name_daste !="" || $id_daste !=""  ){

//---------------------------------------------------------

//$code_ticket="T-".time()."-".rand(11,99);


if ($daste_company_code !== '') {
$Qery="INSERT INTO `departman` (`name`, `id`, `modir`, `default_company_code`, `default_company_name`, `vaziat`, `i_dep`) VALUES ('$name_daste', '$id_daste', '24277', '$daste_company_code', '$daste_company_name', 'y', NULL);";
} else {
$Qery="INSERT INTO `departman` (`name`, `id`, `modir`, `vaziat`, `i_dep`) VALUES ('$name_daste', '$id_daste', '24277', 'y', NULL);";
}


if ($Link->query($Qery ) === TRUE) {	
header("location: ?page=setting&p=y");  
}else{
header("location: ?page=setting&p=n");  
 }
}else{
header("location: ?page=setting&p=n");     
}
 ?>


