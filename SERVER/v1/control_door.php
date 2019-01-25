<?php
require_once('authenticate.php');
?>
<!DOCTYPE html>
<html>
<body>

<h1 style="font-family:courier; font-size:400%; text-align:center;color:deepskyblue;">CONTROL DOOR</h1>
<p style="font-family:verdana; font-size:200%; text-align:center;color:lime;">
<a href="control_door.php?status=open">OPEN</a>
</p>
<p style="font-family:verdana; font-size:200%; text-align:center;color:red;">
<a href="control_door.php?status=close">CLOSE</a>
</p>

<?php
$status;
if(isset($_GET['status'])) {
  $status = $_GET['status'];
  if($status == "open") {  
    $file = fopen("status_door", "w") or die("can't open file");
    fwrite($file, 'OPEN');
    fclose($file);
    echo "<html>";
    echo "<p style=".'"font-family:verdana; font-size:200%; text-align:center;color:lime;"'.">Opened Door</p>";
    echo "</html>";
  }
  if($status == "close") {  
    $file = fopen("status_door", "w") or die("can't open file");
    fwrite($file, 'CLOSE');
    fclose($file);
    echo "<html>";
    echo "<p style=".'"font-family:verdana; font-size:200%; text-align:center;color:red;"'.">Closed Door</p>";
    echo "</html>";
  }
}
?>

</body>
</html>
