<?php
    $conn = mysqli_connect('localhost', 'root', '', 'budgetdb') or die('Can not connect to mysql');
    $sql = mysqli_query($conn, "SELECT * FROM tblbillcategories");
    $query = mysqli_query($conn, 'SELECT * FROM tblbills');
    
    
	