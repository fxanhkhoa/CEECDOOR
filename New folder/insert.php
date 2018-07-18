	
<a href = "TrytoQuery.php"> <button> Click here to add more information</button> </a>


<?php
	$NAME = $_GET["ten"];
	$NTNS = $_GET["ntns"];
	$Sex  = $_GET["sex"];
	$MSSV = $_GET["mssv"];
	$KHOA = $_GET["khoa"];
	$DATE = date('Y-m-d');
	
	
	
$server 	= "localhost:3306";
$username 	= "root";
$password 	= "";
$DB 		= "exampleinsertdata";


$conn = new mysqli($server, $username, $password,$DB);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "INSERT INTO exam (Name, Birth , Sex,ID , Falcuty, Time)VALUES('$NAME','$NTNS','$Sex','$MSSV','$KHOA','$DATE')";

if ($conn->query($sql) === TRUE) {
   
	$sql = "SELECT Name, Birth , Sex ,ID , Falcuty, Time FROM exam";
	$result = $conn->query($sql);
if ($result->num_rows > 0) 
{
    // output data of each row
    while($row = $result->fetch_assoc()) 
	{
        //echo "<br> Name: ". $row["Name"]. " - Birth: ". $row["Birth"]. " ID: " . $row["ID"] . "Falcuty :""<br>";
		
		echo "<br>";
		echo "<br>";
		echo "Name : ". $row["Name"]."<br>";
		echo "Birth		: ". $row["Birth"]."<br>";
		echo "Sex 		: ". $row["Sex"]."<br>";
		echo "ID 		: ". $row["ID"]."<br>";
		echo "Falcuty 	: ". $row["Falcuty"]."<br>"; 	
		echo "Time 		: ". $row["Time"]."<br>";
		echo "---------------------------------------"."<br>";
	}
} 
else     echo "0 results";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close()

?>

