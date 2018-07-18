
<html>
<table>
<form action="insert.php" method="GET">
	
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
<form action="" method="GET">
<button type="submit" name = "print" >Print Table </button>
</form>
</html>

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
    // output data of each row
    while($row = $result->fetch_assoc()) 
	{
        //echo "<br> Name: ". $row["Name"]. " - Birth: ". $row["Birth"]. " ID: " . $row["ID"] . "Falcuty :""<br>";
   
		echo "Name 		: ". $row["Name"]."<br>";
		echo "Birth 	: ". $row["Birth"]."<br>";
		echo "Sex 		: ". $row["Sex"]."<br>";
		echo "ID 		: ". $row["ID"]."<br>";
		echo "Falcuty 	: ". $row["Falcuty"]."<br>";
		echo "Time 		: ". $row["Time"]."<br>";
		echo "---------------------------------------"."<br>";
	}
} 
else     echo "0 results";
$conn->close();
}
?>


 