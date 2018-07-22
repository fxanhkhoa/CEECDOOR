<?php
require_once('authenticate.php');
?>
<?php
$servername = "localhost";
$username = "id6531159_manager";
$password = "123456";
$dbname = "id6531159_ceecdoor_list_id";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Kết nối SQL lỗi: " . $conn->connect_error);
}

$sql = "SELECT ID,NAME,MSSV FROM list_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo"
    <!DOCTYPE html>
	<html>
	<head>
	<style>
	table, th, td {
    	border: 2px solid black;
    	border-collapse: collapse;
	}
	th, td {
    	padding: 5px;
    	text-align: center;
	}
	</style>
	</head>
	<body>

	<h2 style=\"font-family:courier; font-size:400%; text-align:center;color:red;\">REMOVE ID
	</h2>
	<p style=\"font-family:verdana; text-align:left\">Select ID to remove</p>
	<form action=\"/Receive_Delete_Id.php\" method=\"post\">
	<table style=\"width:100%\">
  	<caption style=\"font-family:courier; font-size:200%; text-align:center;color:deepskyblue;\">LIST ID
  	</caption>
  	<tr>
    	<th style=\"text-align:left\">Check</th>
    	<th style=\"text-align:center\">Mã Thẻ</th>
    	<th style=\"text-align:center\">Họ Tên</th>
    	<th style=\"text-align:center\">MSSV</th>
  	</tr>
    ";
    while($row = $result->fetch_assoc()) {
    	$id=$row["ID"];
    	$name=$row["NAME"];
    	$mssv=$row["MSSV"];
        echo"
        	<tr>
        		<td><input type=\"checkbox\" name=$id value=$id></td>
        		<td>$id</td>
        		<td>$name</td>
        		<td>$mssv</td>
        	</tr>
        ";
    }
    echo"
    </table>
    <input type=\"submit\" name=\"submit\" value=\"Submit\">
	</form>
	</body>
	</html>
    ";
} else {
    echo "Không tìm thấy thẻ nào!";
}
$conn->close();
?>