<?php

$db = new mysqli('localhost','root','','automaxr_rentaldb');

if($db->connect_error){
    die("Error in Connecting to database" . $db->connect_error);
 }
?>