<?php
function str_p($vs2)
{
if(isset($_POST[$vs2]))$$vs2=$_POST[$vs2]; else $$vs2='0';
//if(isset($_POST[$vs2]))$$vs2=strip_tags($_POST[$vs2]); else $$vs2='0';
$$vs2=str_replace('<','',trim($$vs2));

return $$vs2;
}
//--------------------------------------------------------
function str_pm($vs2)
{
if(isset($_POST[$vs2]))$$vs2=$_POST[$vs2]; else $$vs2='0';

return $$vs2;
}
//--------------------------------------------------------
function str_g($vs2)
{
if(isset($_GET[$vs2]))$$vs2=strip_tags($_GET[$vs2]); else $$vs2='0';
$$vs2=str_replace('<','',trim($$vs2));
return $$vs2;	
}
//-------------------------------------------------------
function limitword($string, $limit){
    $words = explode(" ",$string);
    $output = implode(" ",array_splice($words,0,$limit));
    return $output;
}

//---------------------------------------------------------

function en2faadd($string) {
$persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
$english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
 
$output= str_replace($persian, $english, $string);
return $output;
}
    //-------------------------------------------------------------------------
?>