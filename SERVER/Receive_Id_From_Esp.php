<?php
$username="id6531159_manager";
$password="123456";
$server = "localhost";
$dbname = "id6531159_ceecdoor_list_id";
if(isset($_POST['ID'])) {
	$ID=$_POST['ID'];
	//ket noi db
	$connect=new mysqli($server,$username,$password,$dbname);

	if ($connect->connect_error) {
		die("ERROR".$connect->connect_error);
		exit();
	}

	//search dữ liệu tu table list_id của db
	$sql = "SELECT * FROM list_id";
	$result = $connect->query($sql);

	if(!$result) {
		die("ERROR".$connect->connect_error);
		exit();
	}

	//Tham chieu den tung phan tu trong table
	$findOut=FALSE;
	$row;
	while ($row = $result -> fetch_array(MYSQLI_ASSOC)) {
		if($ID==$row['ID']) {
			$findOut=TRUE;
			break;
		}
	}
	if($findOut) {
		date_default_timezone_set('Asia/Ho_Chi_Minh'); // Get GMT+7
		$time_Data = date('d/m/Y - H:i:s');		// write Time before data 

		$change = date('d-m-Y');			// get day-month-year
		$changepath = "$change-History.txt";		// combine time and name 
		$changepath = str_replace('"', "'", $changepath); // ep kieu

		$fp = @fopen($changepath, "a"); // Open History file to write
		fwrite($fp,$time_Data);		// write time
		$ID = $row['ID'];		// get ID from esp
		$NAME = $row['NAME'];
		$MSSV = $row['MSSV'];

		if (!$fp)			
		{
    		file_put_contents($changepath," | ID: ".$ID." | NAME: ".$NAME." | MSSV ".$MSSV,FILE_APPEND); // if error ,this line try to send data
		}
		else
		{
   
    		fwrite($fp," | ID: ".$ID." | NAME: ".$NAME." | MSSV ".$MSSV); // write ID got from ESP to file opened
    		//echo "OK";
		}

		fwrite($fp,"\r\n");		// \r\n
		fclose($fp);			// close file
		echo "OK";
	}
	else {
		date_default_timezone_set('Asia/Ho_Chi_Minh'); // Get GMT+7
		$time_Data = date('d/m/Y - H:i:s');		// write Time before data 
		//insert dữ liệu vào table mem_list của list_ceecdoor db
		$sql = "INSERT INTO new_id(ID,NOTE) VALUES ('".$ID."','".$time_Data."')";
		if($connect->query($sql)==TRUE) {
			echo "OK";
		} 
		else {
			echo "ERROR";
		}
	}
	$connect->close();
}
?>