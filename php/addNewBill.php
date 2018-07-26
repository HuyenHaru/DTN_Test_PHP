<?php
	$billID = isset($_POST['billID']) ? $_POST['billID'] : FALSE;
	$billName = isset($_POST['billName']) ? $_POST['billName'] : FALSE;
	$amount = isset($_POST['amount']) ? $_POST['amount'] : FALSE;
	$catID = isset($_POST['catID']) ? $_POST['catID'] : FALSE;
	$isPaid = isset($_POST['isPaid']) ? $_POST['isPaid'] : FALSE;


	//sum là tổng amount nhập từ monthlyBudget
	$sum = (int) $_POST['sum'];	
	//xac dinh xem billID moi nhap co trung voi billID co trong bang khong	
	if (($billID != FALSE) && ($billName != FALSE) && ($amount != FALSE) && ($catID != FALSE)) {
		// Tổng mới sau mỗi lần thêm hóa đơn mới
		$new_total = $sum + $amount;

		// Cập nhật lại tổng hiện tại
		$sum = $new_total;
		require 'connect.php';
	    if (mysqli_connect_errno()) {
	        echo "Failed to connect to MySQL: " . mysqli_connect_error();
	    } else {
	    	//Chuyển tên của category sang id 
	    	$search = mysqli_query($conn,"SELECT * FROM tblbills WHERE id='". $billID ."'");
			if(!$search || mysqli_num_rows($search) == 0){
				switch ($catID) {
					case 'Personal':
						$catIDD = 1;
						break;

					case 'Family':
						$catIDD = 2;
						break;

					case 'Important':
						$catIDD = 3;
						break;
				}
            
            //kiểm tra xem đã trả hay chưa trả
				switch ($isPaid) {
					case 0:
						$str = "insert into tblbills values ('$billID','$billName','$amount',0,'$catIDD')";
						break;

					case 1:
						$str = "insert into tblbills values ('$billID','$billName','$amount',1,'$catIDD')";
						break;
				}

			 // Thực hiện thêm bản ghi vào danh sách 
		        $insert = mysqli_query($conn, $str);

		        // Hiển thị hàng mới nhất vừa được thêm
		        echo '<tr align="right">';
                echo "<td><input class='checkbox' type='checkbox' id='" . $billID . "' name='list[" . $billID . "]'/></td>";
		        echo "<td>" . $billID . "</td>";
		        if ($isPaid == 0) {
		            echo "<td>" . $billName . "</td>";
		        } else {
		            echo "<td style='text-decoration: line-through;'>" . $billName . "</td>";
		        }
		        echo "<td> $ " . $amount . "</td>";
		        echo "<td>" . $catID . "</td>";

		        if ($isPaid == 0) {
		            echo "<td><input type='checkbox'></td>";
		        } else {
		            echo "<td><input type='checkbox' checked></td>";
		        }
		        echo "<td align='center' width='20%'>"
                    . "<a href='edit.php?id=" . $billID . "' >Edit</a> | <a onclick='return confirm(\"Are you sure ?\")' href='delete.php?id=" . $billID . "' >Delete</a>"
                    . "</td>";
		        echo '</tr>';

		        echo '<tr id="tr1">';
		        echo '<td colspan="3">Total</td>';
		        echo '<td>$ ' . $new_total . '</td>';
		        echo '<td colspan="3"></td>';
		        echo '</tr>';
		        echo '<tr  id="tr2">';
		        echo '<td colspan="3">Remain</td>';
		        echo '<td> $ ' . (132000 - $new_total) . '</td>';
		        echo '<td colspan="3"></td>';
		        echo '</tr>';
	        } else {
	            // Nếu đã tồi tại Bill ID, ta thông báo cho người dùng
	            echo "<script language='javascript'> alert('Note: Bill ID has already existed !!!');</script>";
	        }
	}
}
	
