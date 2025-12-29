<?php

$name_cat_x=str_p('name_cat_x');
$id_cat_y=str_p('id_cat_y');

$name_cat_y="";
$fader="y";

if($name_cat_x !="" || $id_cat_y !=""  ){
    
    
    
  if($id_cat_y !="new")  {
 
 $fader="n";
 $Query_dep="SELECT*from daste_mohtava where (id_daste = '$id_cat_y' ) ORDER BY name_f_daste ASC LIMIT 200";
if($Result_dep=mysqli_query($Link,$Query_dep)){
while($q_dep=mysqli_fetch_array($Result_dep)){


$name_cat_y=$q_dep['name_daste'];

    
    
    
  }}}

//---------------------------------------------------------

$id_cat_x="T-".time()."-".rand(11,99);


$Qery="INSERT INTO `daste_mohtava` (`name_daste`, `id_daste`, `name_f_daste`, `id_f_daste`, `fader`, `i_daste`)  VALUES ('$name_cat_x', '$id_cat_x', '$name_cat_y', '$id_cat_y', '$fader', NULL);";
if ($Link->query($Qery ) === TRUE) {	
 
    
header("location: ?page=setting&p=y");  
}else{
header("location: ?page=setting&p=n");  
 }
}else{
 header("location: ?page=setting&p=t");     
}



 ?>


