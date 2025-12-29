<?php

$titr=str_p('titr');
$tarikh_elam=str_p('tarikh_elam');
$code_karbar=str_p('code_karbar');
$name_karbar=str_p('name_karbar');
$matn=str_p('matn');

if($titr !="" || $tarikh_elam !="" || $name_karbar !=""  || $karbar_sabt !=""  ){

//---------------------------------------------------------


   $Query="INSERT INTO `yadavari`(`titr`, `matn`, `tarikh_elam`, `code_karbar`, `name_karbar`, `vaziat`, `karbar_sabt`, `code_g`,`i_elam`)
    VALUES ('$titr','$matn','$tarikh_elam','$code_karbar','$name_karbar','a','$name_karbar_run','$code',NULL );";

    if ($Link->query($Query ) === TRUE) {

        $sabt_dastor="y";


        header("location: ?page=panel_g_yadavar&code=$code&p=y");


    }else{
        header("location: ?page=panel_g_yadavar&code=$code&p=n");
    }
}else{

    header("location: ?page=panel_g_yadavar&code=$code&p=t");
} ?>