<?php
include('inf/f1.php');

$ggggg1=str_p('mpost2');
$ggggg2=str_p('mpost3');
$ggggg3=str_p('mpost4');


?>
<span title="<?php echo $ggggg1; ?> - <?php echo $ggggg2; ?> - <?php echo $ggggg3; ?>" onclick="openpage('aslidiv','<?php echo $pfg; ?>','<?php echo $ggggg1; ?>','<?php echo $ggggg2; ?>','<?php echo $ggggg3; ?>','0')" >
<?php echo $pfg; ?>
</span>
<?php



				if($pfg=="list_makan")include('page/modal/list_makan.php');
				if($pfg=="select_janamay")include('page/modal/select_janamay.php');
				if($pfg=="set_meghdar_m")include('page/modal/set_meghdar_m.php');

				
				if($pfg=="list_khat")include('page/modal/list_khat.php');
				if($pfg=="search_dastgah")include('page/modal/search_dastgah.php');
				if($pfg=="list_dastgah")include('page/modal/list_dastgah.php');
				if($pfg=="list_zirdastgah")include('page/modal/list_zirdastgah.php');
				if($pfg=="list_def")include('page/modal/list_def.php');
				if($pfg=="info_kala")include('page/modal/info_kala.php');
				if($pfg=="info_karbar")include('page/modal/info_karbar.php');
				if($pfg=="list_karbar")include('page/modal/list_karbar.php');
				if($pfg=="show_tarikh")include('page/modal/show_tarikh.php');
				if($pfg=="show_saat")include('page/modal/show_saat.php');
				if($pfg=="search_kala")include('page/modal/search_kala.php');
				if($pfg=="search_peymankar")include('page/modal/search_peymankar.php');
				if($pfg=="show_saat_tarikh")include('page/modal/show_saat_tarikh.php');
				if($pfg=="info_taghvim_roz")include('page/modal/info_taghvim_roz.php');
				if($pfg=="info_darkhast")include('page/modal/info_darkhast.php');
				if($pfg=="select_makan")include('page/modal/select_makan.php');
				if($pfg=="dir_makan")include('page/modal/dir_makan.php');
				if($pfg=="list_tajhiz")include('page/modal/list_tajhiz.php');
				if($pfg=="info_garanti")include('page/modal/info_garanti.php');
				if($pfg=="info_tajhiz")include('page/modal/info_tajhiz.php');
				if($pfg=="tarikhche_tajhiz")include('page/modal/tarikhche_tajhiz.php');
				if($pfg=="search_tajhiz")include('page/modal/search_tajhiz.php');
				if($pfg=="show_def")include('page/modal/show_def.php');
				if($pfg=="bar_file")include('page/modal/bar_file.php');
				if($pfg=="info_makan")include('page/modal/info_makan.php');
				if($pfg=="meno_marhale")include('page/modal/meno_marhale.php');
				
				
				

				

?>