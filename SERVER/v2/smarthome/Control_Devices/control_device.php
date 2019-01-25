<?php
require_once('../Process_Data/authenticate.php');
if (isset($_POST['DV_ID']) && isset($_POST['Action'])) {
	$DV_ID = $_POST['DV_ID'];
	$Action=$_POST['Action'];
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

	$sql = "UPDATE devices SET STATUS='$Action' WHERE DV_ID='$DV_ID'";
	$result = $connect->query($sql);

	if(!$result) {
		die("Error query Users database".$connect->connect_error);
		exit();
	}
	$connect->close();
}
?>

</!DOCTYPE html>
<html>
<head>
	<title>Control Devices</title>
</head>
<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
}
/* Set a style for all buttons */
button {
	text-align: center;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: white solid 5px;
    cursor: pointer;
    width: 20%;
}

button:hover {
    opacity: 0.8;
}
</style>
<body>
<div id=devices></div>
<div id=selected></div>
<script type="text/javascript">
	var intervalId;
	var ajax_call;
	console.log("runing first");
	ajax_call = function() {
		var Post_List_Device = "User=" + <?php echo json_encode($_SESSION['User']); ?> + "&Action=ListDevices";
		var xhttp_list = new XMLHttpRequest();
		xhttp_list.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var result_list = JSON.parse(this.responseText);
				var devices = new String("");
				var i;
				for (i = 0; i < result_list.length; i++) {

					status='OFF';
					
					if (result_list[i].STATUS!=0) {
						if(result_list[i].TYPE=='LEVEL') {
							status='LEVEL: ' + result_list[i].STATUS;
						}
						else {
							status='ON';
						}
					}
					
					
					if (result_list[i].TYPE=='ONOFF' || result_list[i].TYPE=='SLIDING' || result_list[i].TYPE=='SERVO') {
						devices += "<p><h1>"+result_list[i].DV_NAME+"</h1><button onclick=\"turn('"+result_list[i].DV_ID+"',1)\">ON/OPEN</button><button onclick=\"turn('"+result_list[i].DV_ID+"',0)\">OFF/CLOSE</button><strong>STATUS: "+status+"</strong></p>";
					}
					if (result_list[i].TYPE=='LEVEL') {
						devices += "<p><h1>"+result_list[i].DV_NAME+"</h1><button onclick=\"turn('"+result_list[i].DV_ID+"',0)\">OFF</button>";
						for (i_level=1; i_level<=3; i_level++) {
							devices+="<button onclick=\"turn('"+result_list[i].DV_ID+"',"+i_level+")\">Level"+i_level+"</button>";
						}
						devices+="<strong>STATUS: "+status+"</strong></p>";
					}
					
				}
				document.getElementById("devices").innerHTML=devices;
			}
		};
		xhttp_list.open("POST", "list_devices.php", true);
		xhttp_list.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp_list.send(Post_List_Device);
	}
	start_interval(); //set timer


	function start_interval() {
		if (intervalId) {
		    clearInterval(intervalId);
		}
		intervalId = setInterval(ajax_call, 3000);
	}

	function turn(id,state) {
		console.log(state);
		var Post_Turn = "DV_ID=" + id + "&Action="+state;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				//console.log(this.responseText);
			}
		};
		xhttp.open("POST", "control_device.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(Post_Turn);
	};

</script>
<p><h2><a href="../Process_Data/logout.php">Log out</a></h2></p>
<p><h2><a href="../Process_Data/add_device.php">Add device</a></h2></p>
</body>
</html>