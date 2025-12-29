<?php
include('page/ui/ok_sabt.php');


/*
$list_da_karbar = explode( '*', $_SESSION ['mojavez'] );
if($page=="exit")include('page/ui/exit.php');
if($page=="profile")include('page/ui/profile.php');
if($page=="ch_pass")include('page/ui/ch_pass.php');

if(!in_array($page,$list_da_karbar)){ 
?>
<div class="alert alert-danger" role="alert">دسترسی به این بخش برای شما فعال نمی باشد</div>
<?php
}else{

	//-----------------------------------------for_dastor---------------------------

	if($page=="matn_dastor" AND in_array($page,$list_da_karbar))include('page/ui/dastor/matn_dastor.php');
	if($page=="karbar_dastor" AND in_array($page,$list_da_karbar))include('page/ui/dastor/karbar_dastor.php');
	if($page=="file_dastor" AND in_array($page,$list_da_karbar))include('page/ui/dastor/file_dastor.php');
}
*/	
if($page=="" || $page=="0" ||  $page=="null"  )include('page/ui/asli.php');
if($page=="list_ticket")include('page/ui/list_ticket.php');
if($page=="info_ticket")include('page/ui/info_ticket.php');
if($page=="start_ticket")include('page/ui/start_ticket.php');
if($page=="history_ticket")include('page/ui/history_ticket.php');
if($page=="my_work")include('page/ui/my_work.php');
if($page=="wait_page")include('page/ui/wait_page.php');
if($page=="setting")include('page/ui/setting.php');
if($page=="profile")include('page/ui/profile.php');


if($page=="search_academi")include('page/ui/search_academi.php');



if($page=="academi")include('page/ui/academi.php');
if($page=="info_mohtava")include('page/ui/info_mohtava.php');
if($page=="search_mohtava")include('page/ui/search_mohtava.php');



if($page=="panel_g_end")include('page/ui/panel_g_end.php');
if($page=="panel_g_matn")include('page/ui/panel_g_matn.php');
if($page=="panel_g_edit")include('page/ui/panel_g_edit.php');
if($page=="panel_g_hazf")include('page/ui/panel_g_hazf.php');
if($page=="panel_g_data")include('page/ui/panel_g_data.php');
//---------------------------------------------------------------تنظیمات
if($page=="list_band")include('page/ui/list_band.php');
if($page=="new_band")include('page/ui/new_band.php');
if($page=="add_band")include('page/ui/add_band.php');
if($page=="set_pishfarz")include('page/ui/set_pishfarz.php');
if($page=="panel_g_yadavar")include('page/ui/panel_g_yadavar.php');
if($page=="panel_g_file")include('page/ui/panel_g_file.php');

if($page=="info_band")include('page/ui/info_band.php');

if($page=="sabt_sherkat")include('page/ui/sabt_sherkat.php');
if($page=="list_sherkat")include('page/ui/list_sherkat.php');
//-------------------------------------------------------------
if($page=="ch_pass")include('page/ui/ch_pass.php');
if($page=="setting_karbar")include('page/ui/setting_karbar.php');
if($page=="help")include('page/ui/help.php');
if($page=="sabt_karbar")include('page/ui/sabt_karbar.php');

//-------------------------------------------------------------


	
if($page=="list_gozaresh")include('page/ui/list_gozaresh.php');
if($page=="run_gozaresh")include('page/ui/run_gozaresh.php');

	
?>