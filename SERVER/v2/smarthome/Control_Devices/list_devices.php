<?php
if((isset($_POST["User"])) && (isset($_POST["Action"]))) {
	$user=$_POST["User"];
	$action=$_POST["Action"];

	//database
	$username="smarthome";
	$password="123456";
	$server = "localhost";
	$dbname = "smarthome";

	$connect=new mysqli($server,$username,$password,$dbname);

	if ($connect->connect_error) {
		die("Error connect database".$connect->connect_error);
		exit();
	}

	if ($action=="ListDevices") {
		$sql = "SELECT * FROM devices WHERE ID='$user'";
		$result = $connect->query($sql);

		if(!$result) {
			die("Error query Users database".$connect->connect_error);
			exit();
		}
		//Tham chieu den tung phan tu trong table
		$data=array();
		$row;
		while ($row = $result -> fetch_array(MYSQLI_ASSOC)) {
			$data[]=$row;
		}
		$connect->close();
		echo json_encode($data);
	}
}
?>