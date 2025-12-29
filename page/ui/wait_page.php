          
   <?php if(1){
   ?>   
   <div class="alert alert-warning alert-dismissible fade show" role="alert" style="color: black;">
       <i class="bi bi-info-circle"></i> <strong>توجه:</strong> اعلان صوتی از این صفحه حذف شده است :)
       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
   
   <h3 class="text-center text-success" id="tarikh"><?php echo $tarikh; ?></h3>  
   <h1 class="text-center text-success" id="clock"></h1>  

    <script>  
        function updateClock() {  
            const now = new Date();  
            const hours = String(now.getHours()).padStart(2, '0');  
            const minutes = String(now.getMinutes()).padStart(2, '0');  
            const seconds = String(now.getSeconds()).padStart(2, '0');  

            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;  
        }  

        setInterval(updateClock, 1000);  
        updateClock();  

    </script>  

<?php } ?>

        

                        <?php
								$shomare=0;
								$Query_list="SELECT*from ticket where (vaziat = 'a')ORDER BY i_ticket DESC LIMIT 10";
   if($Result_list=mysqli_query($Link,$Query_list)){
 while($q_list=mysqli_fetch_array($Result_list)){
	 $shomare++;
	 ?>
   <h3 class="text-info" ><?php echo $q_list['titr']; ?></h3>
   <h4> پشتیبانی : <?php echo $q_list['name_daste']; ?> : <?php echo $q_list['name_karbar']; ?> </h4>
   <h4> شرکت : <?php echo $q_list['name_sherkat']; ?> <span class="text-warning"><?php echo $q_list['tarikh_sabt']; ?> - <?php echo $q_list['saat_sabt']; ?> </span></h4>
   <hr>
   <?php }} ?>
