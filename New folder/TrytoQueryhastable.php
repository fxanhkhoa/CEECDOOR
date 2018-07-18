<html>

<table class = "inputform">
<form action="inserthastable.php" method="GET">
	
	<td align="">Name:</td>
	<td align=""><input type = "text" name = "ten" value=""></td>
	<tr>
	
	<td align="">Birth:</td>
	<td align=""><input type = "date" name = "ntns" value='d-m-y'></td>
	<tr>
	
	<td align="">Student ID:</td>
	<td align=""><input type = "number_format" name = "mssv" value=""></td>
	<tr>
	
	<td align="">Falcuty: </td>
	<td align=""><input type = "text" name = "khoa" value=""></td>
	<tr>
	
</table>
	<input type="radio" name="sex" value="Male" checked> Male
	<input type="radio" name="sex" value="Female"> Female <br>
	<input type="submit">
	<input type="reset"><br>
	
</form>

<style>
table.showdata, td{
    border: 1px solid black;
}
</style>
<form action="" method="GET">
<button type="submit" name = "print" >Print Table </button>
</form>

<body>

		<table class = "showdata" 	style="width:100%">
 <?php
 
$server 	= "localhost:3306";
$username 	= "root";
$password 	= "";
$DB 		= "exampleinsertdata";

if(isset($_GET['print']))
{	
	
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
}
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