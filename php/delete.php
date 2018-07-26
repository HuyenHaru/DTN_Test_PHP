<?php
	require 'connect.php';
	$id = isset($_GET['id']) ? $_GET['id'] : False;
	$delete = mysqli_query($conn, "DELETE From tblbills where id ='$id'") or die("Lỗi truy vấn");
	if($delete){
		?>
         <script>
            window.location.href = 'MonthlyBudget.php';
         </script>;
      <?php
	}
?>
