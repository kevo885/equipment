<?php
include_once "../inc/functions.php";
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$uri = explode('&', $uri);
$endpoint = $uri[0];
switch ($endpoint) {
	case "ViewDevice":
		include_once "viewDevice.php";
		break;
	case "AddDevice":
		include_once "addDevice.php";
		break;

	case "DeleteDevice":
		include_once "deleteDevice.php";
		break;

	case "ListDevices":
		device_type();
		manufacturer();
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
		echo json_encode(array("Status: Index.php", "MSG: Select an endpoint", "Avaliable end points:ViewDevice, AddDevice, DeleteDevice, ModifyDevice,UploadFile",), JSON_PRETTY_PRINT);
		echo json_encode(array("Status: How to run ViewDevice", "MSG:/equipment/api/index.php/?ViewDevice&id=validID"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("Status: How to run AddDevice", "MSG:/equipment/api/index.php/?AddDevice&id=validID"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("Status: How to run DeleteDevice", "MSG:/equipment/api/index.php/?DeleteDevice&id=ID_to_delete"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("Status: How to run ModifyDevice", "MSG:/equipment/api/index.php/?DeleteDevice&id=ID_to_modify"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);


		die();
}
