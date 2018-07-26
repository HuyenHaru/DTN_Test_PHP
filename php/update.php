<?php 
	require 'connect.php';
	if(isset($_POST['update'])){
		if(isset($_POST['complete'])){
			// mảng complete lưu tất cả các checkbox đã được tích
			// complete là mảng con của mảng complete_all
			$complete = $_POST['complete'];

			//cập nhật các checkbox đã được tích vào csdl, đặt giá trị bằng 1
			//foreach: vòng lặp dùng để lặp mảng or đối tượng
			//key và value có thể đặt với tên khác bất kì
			foreach ($complete as $key => $value) {
				$update = mysqli_query($conn, "UPDATE tblbills SET is_paid = 1 WHERE id = '" . $key . "'");
			}

			//duyệt toàn bộ complete_all, các checkbox không được tích đặt giá trị bằng 0
			foreach ($complete_all as $key => $value) {
				// Kiểm tra xem checkbox nào không được tích thì đặt giá trị bằng 0
				if(!array_key_exists($key, $complete)){
					$update = mysqli_query($conn, "UPDATE tblbills SET is_paid = 0 WHERE id = '" . $key . "'");
				}
			}

			echo "<script language='javascript'> alert('You are updated successfull !') </script>";
			header("Location: " . $_SERVER['PHP_SELF']);

		} else {
			//nếu tất cả đều bỏ tích
			$update = mysqli_query($conn, "UPDATE tblbills SET is_paid = 0");
			echo "<script language='javascript'>alert('You have already unchecked all successfully !')</script>";
        	header("Location: " . $_SERVER['PHP_SELF']);
		}
	}
