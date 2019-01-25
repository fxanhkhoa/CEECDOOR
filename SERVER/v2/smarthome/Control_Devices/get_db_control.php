<?php
	if(isset($_POST['DV_ID'])) {
	  	$username="smarthome";
		$password="123456";
		$server = "localhost";
		$dbname = "smarthome";

		$connect=new mysqli($server,$username,$password,$dbname);

		if ($connect->connect_error) {
			die("ERROR".$connect->connect_error);
			exit();
		}
		
		$Device_Id=$_POST['DV_ID'];
		//$Device_Id='LED13';

		$sql = "SELECT STATUS FROM devices WHERE DV_ID='$Device_Id'";
		$result = $connect->query($sql);

		if(!$result) {
			die("ERROR".$connect->connect_error);
			exit();
		}

		//Tham chieu
		$row=$result -> fetch_array(MYSQLI_ASSOC);
		echo $row['STATUS'];
		$connect->close();
	}
?>