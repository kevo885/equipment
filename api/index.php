<?php
include_once "../inc/functions.php";
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$uri = explode('&', $uri);
$endpoint = $uri[0];
switch ($endpoint) {
	case "ViewDevice":
		include_once "viewDevice.php";
		break;
	case "ListDevice":
		device_type();
		manufacturer();
		break;
	case "AddDevice":
		include_once "addDevice.php";
		// TODO: check for valid device_type and valid manufacturer
		break;
	case "DeleteDevice":
		include_once "deleteDevice.php";
		break;
	case "ModifyDevice":
		include_once "modifyDevice.php";
		break;
	case "UploadFile":
		include_once "uploadFile.php";
		break;
	case "ViewFile":
		break;
	case "SetStatus":
		include_once "setStatus.php";
		break;
	default:
		header('Content-Type: application/json');
		header("HTTP/1.1 404 Not Found");
		echo json_encode(array("API: Index.php", "MSG: Select an endpoint", "Avaliable end points:ViewDevice,ListDevice, AddDevice, DeleteDevice, ModifyDevice,UploadFile,SetStatus",), JSON_PRETTY_PRINT);
		echo json_encode(array("API: ViewDevice", "Usage: View a device by device ID", "Parameters:id=validID", "How to run:/equipment/api/index.php/?ViewDevice&Parameters", "Errors: Device dosen't exists | device id is invalid - not a number or empty input"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("API: ListDevice", "Usage: Lists all device types and manufacturers", "Parameters:None", "How to run:/equipment/api/index.php/ListDevice", "Errors: None"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("API: AddDevice", "Usage: Adds a device", "Parameters:device_type=device_type&manufacturers=manufacturers&sn=serial_number", "How to run:/equipment/api/index.php/?AddDevice&Parameters", "Errors: All fields are required | Serial number already taken"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("API: DeleteDevice", "Usage: Deletes a device by device ID", "Parameters:id=validID", "How to run:/equipment/api/index.php/?DeleteDevice&Parameters", "Errors: Invalid ID | device does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("API: ModifyDevice", "Usage: Modify a device - Not all parameter fields are required - may choose 1 or multiple to update", "Parameters:id=ID_to_modify&device_type=new_device_type&manufacturer=new_manufacturer&sn=new_serial_num", "How to run:/equipment/api/index.php/?ModifyDevice&Parameteres", "Errors: At least one parameter must be given | ID is invalid | Device ID dosen't exists | Serial Number is already taken"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("API: UploadFile", "Usage: Upload a file to a device via file path", "Parameters:id=validID&file_path=file_path", "How to run:/equipment/api/index.php/?UploadFile&Parameteres", "Errors: ID is invalid | Device ID dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("API: ViewFile", "Usage: View upload file", "Parameters:id=validID&file_path_to_view=file_path", "How to run:/equipment/api/index.php/?ViewFile&Parameteres", "Errors: ID is invalid | Device ID dosen't exists | file dosen't exist"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo json_encode(array("API: SetStatus", "Usage: Enable or disables a device", "Parameters:id=validID&status=status", "How to run:/equipment/api/index.php/?SetStatus&Parameters", "Errors: Invalid ID | device does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		die();
}
