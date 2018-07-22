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
    die("Connection failed: " . $conn->connect_error);
}
$sql_select = "SELECT ID FROM list_id";
$result = $conn->query($sql_select);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        if(!empty($_POST[$row["ID"]])) {
        	$id=$_POST[$row["ID"]];
        	$sql_delete = "DELETE FROM list_id WHERE ID='" . $id . "'";
			if ($conn->query($sql_delete) === TRUE) {
			    $file = fopen("status_sql", "a") or die("can't open file");
                fwrite($file, "[R]$id");
                fclose($file);
    			echo "Thẻ $id đã được xóa<br>";
			} else {
    			echo "Lỗi không xóa được<br>" . $conn->error;
			}
        }
    }
} else {
    echo "Không tìm thấy thẻ nào";
}
$conn->close();
?>