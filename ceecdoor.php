<?php


date_default_timezone_set('Asia/Ho_Chi_Minh'); // Get GMT+7
$time_Data = date('d/m/Y - H:i:s');		// write Time before data 

$change = date('d-m-Y');			// get day-month-year
$changepath = "$change-History.txt";		// combine time and name 
$changepath = str_replace('"', "'", $changepath); // ep kieu

$fp = @fopen($changepath, "a"); // Open History file to write
fwrite($fp,$time_Data);		// write time
$ID = $_GET['id'];		// get ID from esp

if (!$fp)			
{
    file_put_contents($changepath," | ID: ".$ID,FILE_APPEND); // if error ,this line try to send data
}
else
{
   
    fwrite($fp, " | ID: ".$ID); // write ID got from ESP to file opened
    //echo "OK";
}

fwrite($fp,"\r\n");		// \r\n
fclose($fp);			// close file

?>

