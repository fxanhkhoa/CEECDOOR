	
<a href = "TrytoQueryhastable.php"> <button> Click here to add more information</button> </a>


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
  $message = "Inserted Successfully";
	echo "<script type='text/javascript'>alert('$message');</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close()

?>


<html>
<style>
table.showdata, td{
    border: 1px solid black;
}
</style>

<body>

		<table class = "showdata" 	style="width:100%">
 <?php
 
$server 	= "localhost:3306";
$username 	= "root";
$password 	= "";
$DB 		= "exampleinsertdata";

$conn = new mysqli($server, $username, $password,$DB);
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} 
//$sql = "INSERT INTO exam (Name, Birth , ID , Falcuty, Time)VALUES('$NAME','$NTNS','$MSSV','$KHOA','$DATE')";
$sql = "SELECT Name, Birth ,Sex, ID , Falcuty, Time FROM exam";
$result = $conn->query($sql);
if ($result->num_rows > 0) 
{
	$i = 0;
    // output data of each row
	Title();
    while($row = $result->fetch_assoc()) 
	{
        //echo "<br> Name: ". $row["Name"]. " - Birth: ". $row["Birth"]. " ID: " . $row["ID"] . "Falcuty :""<br>";
		
		addRow($row["Name"],$row["Birth"],$row["Sex"],$row["ID"],$row["Falcuty"],$row["Time"],$i);
		$i++;
	}
} 
else     echo "0 results";
$conn->close();

?>
		</table>

<?php
	function Title()
	{
?>
		 <tr>
			<td>No.</td>
			<td>Name</td>
			<td>Birth</td>
			<td>Sex</td>
			<td>Id</td>
			<td>Falcuty</td>
			<td>Time</td>
		</tr>
<?php		
	}
?>
<?php
    function addRow( $Ten,$NS,$Gt,$MSSV,$Khoa,$Tg,$stt)
    {
?>
		<tr>
		 
		<td><?php echo $stt; ?></td>
		<td><?php echo $Ten; ?></td>
		<td><?php echo $NS; ?></td>
		<td><?php echo $Gt; ?></td>
		<td><?php echo $MSSV; ?></td>
		<td><?php echo $Khoa; ?></td>
		<td><?php echo $Tg; ?></td>
		
		</tr>
<?php
    }

?>

</body>
</html>

