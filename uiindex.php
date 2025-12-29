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

if($page=="gozareshat")include('page/ui/gozareshat.php');
if($page=="run_gozaresh")include('page/ui/run_gozaresh.php');

if($page=="list_pasokh_no")include('page/ui/list_pasokh_no.php');
if($page=="list_mobina")include('page/ui/list_mobina.php');
if($page=="list_payesh")include('page/ui/list_payesh.php');

if($page=="info_mohtava")include('page/ui/info_mohtava.php');
if($page=="new_mohtava")include('page/ui/new_mohtava.php');
if($page=="list_mohtava")include('page/ui/list_mohtava.php');
if($page=="list_qa")include('page/ui/list_qa.php');

// Priority pages - conditional routing based on user code
$special_view_codes = ["24277", "25662", "20612", "23056", "22695", "20072", "1100105", "1100056"];

if ($page == "priority" || $page == "set_priority" || $page == "view_priority") {

    $user_code = $_SESSION['code_p'] ?? null;

    // Case 1: Full access
    if ($user_code === "1002") {
        include('page/ui/set_priority.php');
        exit;
    }

    // Case 2: Semi-access (view only)
    if (in_array($user_code, $special_view_codes)) {
        include('page/ui/view_priority.php');
        exit;
    }

    // Case 3: No access → restricted page
    include('page/ui/restricted.php');
    exit;
}

// Admin-only page: list_working_on (show users and their current 'working on' ticket)
if ($page == "list_working_on") {
    $user_code = $_SESSION['code_p'] ?? null;
    if (in_array($user_code, $special_view_codes, true) || $user_code === "1002") {
        include('page/ui/list_working_on.php');
        exit;
    }
    include('page/ui/restricted.php');
    exit;
}

?>