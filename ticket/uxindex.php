<?php

if($page=="chek_login")include('page/ux/chek_login.php');
//----------------------------------------------------- trade tracking
if(!isset($_SESSION ['code_p'])){ ?>
   <div class="alert alert-danger" role="alert">کاربر شناسایی نشد - سیشن شما منقضی شده است </div><?php
}else{	


if($page=="s_new_pasokh")include('page/ux/s_new_pasokh.php'); // ok

if($page=="end_ticket")include('page/ux/end_ticket.php'); 
if($page=="anjam_ticket")include('page/ux/anjam_ticket.php'); 
if($page=="s_new_ticket")include('page/ux/s_new_ticket.php'); 






if($page=="exit")include('page/ux/exit.php');

/*

if($page=="s_hazf_gha")include('page/ux/s_hazf_gha.php');
if($page=="s_new_band")include('page/ux/s_new_band.php');
if($page=="s_edit_band")include('page/ux/s_edit_band.php');
if($page=="s_new_mot")include('page/ux/s_new_mot.php');
if($page=="s_edit_mot")include('page/ux/s_edit_mot.php');
if($page=="s_add_band")include('page/ux/s_add_band.php');
if($page=="hazf_band")include('page/ux/hazf_band.php');

if($page=="s_dade_g")include('page/ux/s_dade_g.php');
if($page=="s_vaziat_gha")include('page/ux/s_vaziat_gha.php');
if($page=="s_panel_g_yadavar")include('page/ux/s_panel_g_yadavar.php');

if($page=="s_new_daste_g")include('page/ux/s_new_daste_g.php');
if($page=="s_new_item_g")include('page/ux/s_new_item_g.php');

if($page=="s_new_karbar")include('page/ux/s_new_karbar.php');

if($page=="s_new_sherkat")include('page/ux/s_new_sherkat.php');

if($page=="s_ch_pass")include('page/ux/s_ch_pass.php');

if($page=="s_file_gha")include('page/ux/s_file_gha.php');
if($page=="hazf_file_gha")include('page/ux/hazf_file_gha.php');




*/

/*
?>
<span class="small text-danger" >
<?php 
echo $page;
 ?>
</span>
<?php
*/
}

?>