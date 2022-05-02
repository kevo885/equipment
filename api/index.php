<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$uri = explode('&', $uri);
$endpoint = $uri[0];
switch ($endpoint) {
	case "ViewDevice":
		include_once "viewDevice.php";
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
		echo json_encode(array("Status: Error", "MSG: Endpoint not found"));
		die();
}
