<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);

$host = "localhost";
$pass = "GreekCenter"; 
$db = "center";
$user = "center";	

$conn = mysqli_connect($host, $user, $pass);
if($conn){
	 
	$link = mysqli_select_db($conn, $db);
	if(!$link){
		
		echo "Error in selecting database";
	}
}else{
	echo "Error in connection";
} 
		
		
?>