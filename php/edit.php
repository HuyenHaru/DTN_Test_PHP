<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<title>Update bill</title>
	<link type="text/css" href="style.css" rel="stylesheet" />
	<script language="javascript" src="jquery-3.2.1.js"></script> 
	<script type="text/javascript">
        function return_home() {
            window.location.href = 'MonthlyBudget.php';
        }
    </script>
	<link rel="stylesheet" href="">
</head>
<body>
	<div class="content">
   	
	   <h3 style="text-align: center;">Update bill</h3>
	   <div class="form">
     		<form method="post" >
     			<?php
			   	$cat = 0;
			   	$paid = 0;
			   	if(isset($_GET['id'])){
			   		$id = $_GET['id'];
			   		// $conn = mysqli_connect('localhost', 'root', '', 'budgetdb') or die('Can not connect to mysql');
			   		require 'connect.php';
				   	$qr = mysqli_query($conn, "SELECT * FROM tblbills WHERE id = '" .$id. "'") or die('Lỗi truy cập');
				   	$rs = mysqli_fetch_array($qr,MYSQLI_ASSOC);
				   	$cat= $rs['id_cat'];
				   	$paid = $rs['is_paid'];

			   	}
			   	
		   	?>
            <div class="row">                
              <span>Account</span>
              <input type="text" name="account" id="account" value="" placeholder="132000" readonly> ($) 
            </div>
            
            <div class="row">                
               <span>Bill ID</span>
               <input type="text" name="billID" id="bill_id" value="<? echo $rs['id']?>" style="width:50px;" readonly>
            </div>
             
            <div class="row">                
              <span>Bill name</span>
              <input type="text" name="billName" id="bill_name" value="<? echo $rs['name']?>" style="width: 200px">
            </div>
            
            <div class="row">               
              <span>Amount</span>
              <input type="number" name="amount" id="amount" value="<? echo $rs['amount']?>" style="width: 200px"> ($) 
            </div>
             
             <div class="row"> 
             		           
                 <span>Bill on</span>
                  <?php require 'connect.php' ?>
                 <select name="billon" id="category">
                    <option >--Select a Category--</option>
                    <?php 
                          while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) :
                    ?>
	                 	<option <?php
                        if ($cat == $row['id']) {
                            echo 'selected';
                        }
                        ?>> <?php echo $row['cat_name']; ?>  </option>;
                            <?php
                        endwhile;
                        ?>
                  </select>
                 
            </div>  

            <div class="row">
            		<input type="checkbox" name="isPaid" id="is_paid" 
						<?php
							if($paid == 1){
								echo 'checked';
							}
						?>
            		> Is Paid?
            </div>
            <p>
            	<input type="submit" name="save" value="Save" class="btn btn-success"/>
                <input type="button" name="cancel" value="Cancel" class="btn btn-danger" onclick=" return_home()"/>

                <?php
                	if(isset($_POST['save'])){
                		$billID = isset($_POST['billID']) ? $_POST['billID'] : FALSE;
							$billName = isset($_POST['billName']) ? $_POST['billName'] : FALSE;
							$amount = isset($_POST['amount']) ? $_POST['amount'] : FALSE;
							$catID = isset($_POST['billon']) ? $_POST['billon'] : FALSE;
							$isPaid = isset($_POST['isPaid']) ? $_POST['isPaid'] : FALSE;
							if ($isPaid == "on") {
								$isPaid = 1;
							} else {
								$isPaid = 0;
							}
							switch ($catID) {
							case "Personal":
								$catID = 1;
								break;
							case "Family":
								$catID = 2;
								break;
							case "Important":
								$catID = 3;
								break;
							}
                		$conn = mysqli_connect('localhost', 'root', '', 'budgetdb') or die('Can not connect to mysql');
                		if (mysqli_connect_errno()) {
						      echo "Failed to connect to MySQL: " . mysqli_connect_error();
						   } else {
						   	$update = mysqli_query($conn, "UPDATE tblbills SET name = '" . $billName . "' ,amount = " . $amount . " ,is_paid = " . $isPaid . " ,id_cat = " . $catID . "  WHERE id = '" . $id . "'");
						   	if(!$update || $update == FALSE){
						   		?>
						   		<script >
						   			alert('Fail to update data !');
                              window.location.href = 'MonthlyBudget.php';
						   		</script>;
						   	<?php
						   }else {
						   	?>
						   	<script>
						   		alert('Update data successful !');
                           window.location.href = 'MonthlyBudget.php';
						   	</script>
							<?php
							}
					   }
             	}
               ?>
	        	</p>
         </form>
      </div>
   </div>
</body>
</html>