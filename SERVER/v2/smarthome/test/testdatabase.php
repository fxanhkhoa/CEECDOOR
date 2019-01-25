<?php
	$username="smarthome";
	$password="123456";
	$server = "localhost";
	$dbname = "smarthome";
	$connect=new mysqli($server,$username,$password,$dbname);
	if ($connect->connect_error) {
		die("Error connect database".$connect->connect_error);
		exit();
	}
	echo ("connect success!<br>");
	$sql = "SELECT * 
	FROM devices";
	$result = $connect->query($sql);

	if(!$result) {
	    die("Error query Users database".$connect->connect_error);
	    exit();
	}
	$row;
	while ($row = $result -> fetch_array(MYSQLI_ASSOC)) {
		echo $row['DV_NAME'];
		echo "<br>";
	}
	$connect->close();
?>
