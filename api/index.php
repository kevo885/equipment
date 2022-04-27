<?php
include("functions.php");
$uri=parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY);
$uri=explode('&',$uri);
$endPoint=$uri[0];
//die("End Point: $endPoint");
switch ($endPoint){
	case "ViewDevice":
		include("ViewDevice.php");
		break;
	case "ListDevices":
		break;
	case "UploadFile":
		break;
	case "UpdateDevice":
		break;
	case "ViewFile":
		break;
	default:
		header('Content-Type: application/json');
		header("HTTP/1.1 404 Not Found");
		$message[]="Status: Error";
		$message[]="MSG: Endpoint not found";
		$message[]="";
		echo json_encode($message);
		die();
}
?>