<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'example_username', 'example_password', 'example_table_name');


// if($conn->connect_error){
// 	die("<script>console.log('Couldn\'t connect to DB)</script> " . $conn->connect_error);
// } else {
// 	echo "<script>console.log('Connected to DB')</script>";
// }

?>
