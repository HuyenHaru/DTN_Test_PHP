<?php
ob_start();
// Khai báo biến mảng toàn cục, lưu tất cả các checkbox trong table, cả được tích và không được tích
$complete_all = array();
?>

<!DOCTYPE html>
<html >
<head>
<meta content="text/html; charset=utf-8">
<title>MonthlyBudget</title>
<link type="text/css" href="style.css" rel="stylesheet" />
<script language="javascript" src="jquery-3.2.1.js"></script> 
</head>
<body>
  <script language="javascript">
    function validated(){
      //lấy dữ liệu
      var data = {
        ID: $('input[name="ID"]').val(),
        Billname: $('input[name="Billname"]').val(),
        Amount: $('input[name="Amount"]').val(),
        billon: $('select[name="billon"]').val(),
      }

      // Xóa các thông báo lỗi
      $(".error").remove();

      //Kiểm tra định dạng input
      var flag=true;
      $.each(data, function(key,item) {
        if(key == 'billon'){
          if(item=='--Select a Category--'){
            var html='<span style="color: red; width: 200px; margin-right:10px" class="error" > Please select a category!  </span>';
            $('select[name="'+ key + '"]').parent().append(html);
            flag=false;
          }
        } 
        else {
          if(item == ''){
          var html='<span style="color: red; width: 200px; margin-right:10px" class="error" > Please enter ' + key + '! </span>';
          $('input[name="'+ key + '"]').parent().append(html);
          flag=false;
        }
      }

      });

      if(flag == false){
        return false;
      }


      //thực hiện reset addbill
      function resetForm() {
        document.getElementById("bill").reset();
      }

      //hàm kiểm tra checkbox
      function isChecked() {
        var bit = $('#is_paid')[0].checked;
        if (bit == true) {
            bit = 1;
        } else {
            bit = 0;
        }
        return bit;
      }



      $.ajax({
        url: 'addNewBill.php',
        type: 'post',
        dataType: 'text',
        data:{
          billID: $('#bill_id').val(),
          billName: $('#bill_name').val(),
          amount: $('#amount').val(),
          catID: $('#category').val(),
          isPaid: isChecked(),
          sum: $('#sum').val(),
        },
        success: function (result) {
          $('#total').remove();
          $('#remain').remove();
          $('#mybody').append(result);
        }
      });

      return true;
    }

    //thực hiện chọn/không chọn tất cả các checkbox
    function selectAll(source) {
      checkboxes = document.getElementsByClassName("checkbox");
      for(var i in checkboxes)
        checkboxes[i].checked = source.checked;
    }  
        
  </script>
	
  <div class="content">
   	
     <h3 >Add New Bill</h3>
     <div class="form">
     		<form id="bill" method="post" >
            <div class="row">                
              <span>Account</span>
              <input type="text" name="account" id="account" value="" placeholder="132000" readonly> ($) 
            </div>
            
            <div class="row">                
               <span>Bill ID</span>
               <input type="text" name="billID" id="bill_id" value="" style="width:50px;">
            </div>
             
            <div class="row">                
              <span>Bill name</span>
              <input type="text" name="billName" id="bill_name" value="" style="width: 200px">
            </div>
            
            <div class="row">               
              <span>Amount</span>
              <input type="number" name="amount" id="amount" value="" style="width: 200px"> ($) 
            </div>
             
             <div class="row"> 
             		           
                 <span>Bill on</span>
                  <?php require 'connect.php' ?>
                 <select name="billon" id="category">
                    <option >--Select a Category--</option>
                    <?php 
                          while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
                              echo "<option>" .$row['cat_name']."</option>";
                          }
                    ?>
                  </select>
                 
            </div>  

            <div class="row">
            		<input type="checkbox" name="isPaid" id="is_paid" value="" onsubmit="return isChecked()"> Is Paid?
            </div>

            <div class = "row">
                  <input style="margin: 0px auto 20px 105px" type="button" onclick="validated()" value="Add new bill" />
                  <button id="reset" onclick="resetForm()">Reset</button>                
            </div>
              
     		</form>
     </div>
     <h3>Bill list</h3>
     <div class="form1" >
     		<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method = "post">
            <table>
            <thead>
              <tr style="background-color: #FFFF66">
                <td width="5%"><input type="checkbox" id="select_all" onclick="selectAll(this)"></td>
                <td width="10%">ID</td>
                <td width="20%">Name</td>
                <td width="15%">Amount</td>
                <td width="15%">Category</td>
                <td width="15%">Complete</td>
                <td width="12%">Action</td>
              </tr>
            </thead>

            <tbody id="mybody">
              <?php
                $total = 0;
                require 'connect.php';
                if (mysqli_num_rows($query) > 0) {
                  while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                    $total += (int) $row['amount'];
                    $id = $row['id'];
                    $complete = $row['is_paid'];
                    $category = $row['id_cat'];
                    switch ($category) {
                      case 1:
                          $category = "Personal";
                          break;
                      case 2:
                          $category = "Family";
                          break;
                      case 3:
                          $category = "Important";
                          break;
                    }
                    echo '<tr id=' . $id . ' align="right">';
                    echo "<td><input class='checkbox' type='checkbox' id='" . $id . "' name='list[" . $id . "]'/></td>";
                    echo "<td>" . $row['id'] . "</td>";
                    if ($complete == 0) {
                      echo "<td>" . $row['name'] . "</td>";
                    } else {
                      echo "<td style='text-decoration: line-through;'>" . $row['name'] . "</td>";
                    }
                    echo "<td> $ " . (int) $row['amount'] . "</td>";
                    echo "<td>" . $category . "</td>";
                    if ($complete == 0) {
                      echo "<td><input type='checkbox' name='complete[" . $id . "]' value = 0></td>";
                      // Thêm lần lượt từng checkbox vào mảng toàn thể
                      $complete_all[$id] = 0;
                    } else {
                      echo "<td><input type='checkbox' name='complete[" . $id . "]' value = 1 checked></td>";
                      // Thêm lần lượt từng checkbox vào mảng toàn thể
                      $complete_all[$id] = 1;
                    }
                    echo "<td align='center' width='20%'>"
                    . "<a href='edit.php?id=" . $id . "' >Edit</a> | <a onclick='return confirm(\"Are you sure ?\")' href='delete.php?id=" . $id . "' >Delete</a>"
                    . "</td>";
                    echo '</tr>';
                  }
                }
              ?>

              <tr align="right" id="total">
                <td colspan="3">Total</td>
                <td><?php echo "$ " . $total ?></td>
                <td colspan="3"></td>
              </tr>
              <tr align="right" id="remain">
                <td colspan="3">Remain</td>
                <td> <?php echo "$ " . (132000 - $total); ?></td>
                <td colspan="3"></td>
              </tr>
            </tbody>
          </table>
          <input type="hidden" id="sum" value="<?php echo $total ?>"> 
          <p>
            <button type="submit" id="update" name="update" style="margin:10px auto 5px 5px;">Update</button>
            <button type="submit" id="delete" name="delete" style="margin:10px auto 5px 5px;">Delete</button>
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

                  //tải lại trang, hiện thông báo
                  ?>
                    <script>
                      //alert('Update successfully !');
                      window.location.href = 'MonthlyBudget.php';
                    </script>;
                  <?php
                } else {
                  //nếu tất cả đều bỏ tích
                  $update = mysqli_query($conn, "UPDATE tblbills SET is_paid = 0");
                  ?>
                    <script>
                      window.location.href = 'MonthlyBudget.php';
                    </script>;
                  <?php
                }
              }

              //xóa dữ liệu ở ô được tích
              if(isset($_POST['delete'])){
                if(isset($_POST['list'])){
                  // mảng tick  lưu tất cả các checkbox đã được tích
                  $tick = $_POST['list'];
                  $flag = true;
                  //xóa tất cả các hàng được tích ô checkbox đầu tiên
                  foreach ($tick as $key => $value) {
                    $delete_tick = mysqli_query($conn, "DELETE From tblbills where id = '" . $key . "'");
                  }

                  if($flag == false){
                    ?>
                    <script>
                      //alert('Delete unsuccessfully !');
                      window.location.href = 'MonthlyBudget.php';
                    </script>;
                    <?php
                  }else{
                    ?>
                    <script>
                      //alert('Delete successfully !');
                      window.location.href = 'MonthlyBudget.php';
                    </script>;
                    <?php
                  }
                  
                } else {
                  ?>
                    <script>
                      alert('You have not selected any data !');
                      window.location.href = 'MonthlyBudget.php';
                    </script>;
                  <?php
                }
              }

              ob_end_flush();
            ?>


          </p>  
   		  </form>
     </div>
    </div>
  
</body>
</html>
