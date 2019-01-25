<?php
session_start();
if(empty($_SESSION["authenticated"]) || $_SESSION["authenticated"] != 'true') {
    header('Location:/project/smarthome/Process_Data/login.php');
}
?>