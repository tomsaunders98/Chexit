<?php

function NumericalVote($var){
	if ($var === "Y"){
		$var = 1;
	}
	if ($var === "A"){
		$var = 0;
	}
	if ($var === "N"){
		$var = 1;
	}
	return $var;
}
function InsertPicture($var){
	$ext1 = ".jpeg";
	$url = "img/" . $var . ".jpeg";
	$url1 = "img/" . $var . ".jpg";
	$url2 = "img/profile.png";
	if (file_exists($url)){
		return $url;
	}
	elseif (file_exists($url1)){
		return $url1;
	}else{
		return $url2;
	}
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "";
$username = "";
$password = "";
$dbname = "";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
  $mysqli = new mysqli($servername, $username, $password, $dbname);
  $mysqli->set_charset("utf8mb4");
} catch(Exception $e) {
  error_log($e->getMessage());
  exit('Error connecting to database'); //Should be a message a typical user could understand
}
?>