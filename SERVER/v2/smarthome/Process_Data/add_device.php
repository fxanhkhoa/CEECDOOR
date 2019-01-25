<?php
session_start();
if(empty($_SESSION["authenticated"]) || $_SESSION["authenticated"] != 'true') {
    header('Location:/project/smarthome/Process_Data/login.php');
} else {
	// define variables and set to empty values
	$name = $email = $gender = $comment = $website = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$DV_ID = $_POST['DV_ID'];
		$DV_NAME = $_POST['DV_NAME'];
		$TYPE = $_POST['TYPE'];
		$ID= $_SESSION['User'];
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

		/*check user and passw*/

		$sql = "INSERT into devices (DV_ID,ID,DV_NAME,STATUS,TYPE) values ('$DV_ID','$ID','$DV_NAME',0,'$TYPE')";
		$result = $connect->query($sql);

		if(!$result) {
			die("Device ID conflicted".$connect->connect_error);
			exit();
		}
		echo ('Add device successfully!');
		$connect->close();
	}
	?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Add Divices</title>
		</head>
		<body>
			<form method="post">
			  Device ID:<br>
			  <input type="text" name="DV_ID">
			  <br>
			  Device Name:<br>
			  <input type="text" name="DV_NAME">
			  <br>
			  Device Type:<br>
			  <select name="TYPE">
			    <option value="ONOFF">ON/OFF</option>
			    <option value="SERVO">OPEN/CLOSE</option>
			    <option value="LEVEL">LEVEL</option>
			    <option value="SLIDING">SLIDING</option>
			  </select>
			  <br>
			  <br>
			  <button type="submit">OK</button>
			</form>
		<p></p><a href="../Control_Devices/control_device.php">Control your devices now!</a></p>
		</body>
		</html>
<?php
}
?>