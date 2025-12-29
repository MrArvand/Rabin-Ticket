<?php

if (!isset($Link)) {
    include_once __DIR__ . '/inf/f1.php';
}

if (!isset($page)) {
    $page = isset($_GET['page']) ? trim($_GET['page']) : '';
}

if ($page === "chek_login") {
    include('page/ux/chek_login.php');
    exit;
}

$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (!isset($_SESSION['code_p'])) {
    if ($isAjaxRequest) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'نشست کاربری منقضی شده است. لطفاً دوباره وارد شوید.'
        ]);
    } else {
        ?>
        <div class="alert alert-danger" role="alert">کاربر شناسایی نشد - سیشن شما منقضی شده است </div>
        <?php
    }
    exit;
}

if($page=="s_new_pasokh")include('page/ux/s_new_pasokh.php'); // ok

if($page=="end_ticket")include('page/ux/end_ticket.php'); 
if($page=="s_new_ticket")include('page/ux/s_new_ticket.php'); 
if($page=="erja_ticket")include('page/ux/erja_ticket.php');
if($page=="s_new_karkerd")include('page/ux/s_new_karkerd.php');
if($page=="s_new_sherkat")include('page/ux/s_new_sherkat.php');
if($page=="s_new_daste")include('page/ux/s_new_daste.php');
if($page=="s_new_poshtiban")include('page/ux/s_new_poshtiban.php');
if($page=="anjam_ticket")include('page/ux/anjam_ticket.php');
if($page=="set_working_on")include('page/ux/set_working_on.php');

if($page=="s_new_cat")include('page/ux/s_new_cat.php');
if($page=="hazf_karkerd")include('page/ux/hazf_karkerd.php');
if($page=="s_new_mohtava")include('page/ux/s_new_mohtava.php');
if($page=="hazf_ticket")include('page/ux/hazf_ticket.php');
if($page=="update_priority")include('page/ux/update_priority.php');
if($page=="change_category")include('page/ux/change_category.php');
if($page=="s_update_avatar")include('page/ux/s_update_avatar.php');
if($page=="s_change_password")include('page/ux/s_change_password.php');

if($page=="exit")include('page/ux/exit.php');

/*



?>
<span class="small text-danger" >
<?php 
echo $page;
 ?>
</span>
<?php
*/

?>