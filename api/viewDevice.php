<?php
include_once "../inc/.env.php";
include_once "../inc/functions.php";
error_reporting(0);

$id = $_REQUEST['id'];
$serial_number_query = "%$_REQUEST[sn]%";
$device_type = $_REQUEST['device_type'];
$manufacturer = $_REQUEST['manufacturer'];
$capitalizeManufacturer = array_map('strtoupper', get_manufacturer());

// search by id
if ($_REQUEST['id'] != NULL) {
	// has to be a positive int
	if (!is_numeric($id) && $id != NULL) {
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array("Status: Invalid ID", "Device ID must be a positive integer"), JSON_PRETTY_PRINT);
		die();
	}
	if (device_exists($id)) {

		// search by id

		$sql = "SELECT * from devices where id = ?";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id, $device_type, $manufacturer, $sn, $status);

		while (mysqli_stmt_fetch($stmt)) {
			$list[] =  str_replace("\r", "", "$id $device_type $manufacturer $sn $status");
		}
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array("Status: Success! search results below", "ID, Device type, Manufacturer, Serial number, Status", $list), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	} else {
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array("Status: ERROR", "MSG: Device Id: $id does not exist"), JSON_PRETTY_PRINT);
		die();
	}
} else if ($id == NULL) {
	if ($_REQUEST['device_type'] == NULL && $_REQUEST['manufacturer'] == NULL && $_REQUEST['sn'] == NULL) {
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array("ERROR", "Device type, Manufactuer, and serial number cannot all be blank - choose at least one column to modify"), JSON_PRETTY_PRINT);
		die();
	}
	//search by all three type
	if (!empty($_REQUEST['device_type']) && !empty($_REQUEST['manufacturer']) && !empty($_REQUEST['sn'])) {
		check_device_and_manufacturer();

		$sql = "SELECT * from devices where manufacturer = ? and device_type = ? and serial_number like ?";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "sss", $_REQUEST['manufacturer'], $_REQUEST['device_type'], $serial_number_query);
	}
	// query by device type and manufacturer 
	else if (!empty($_REQUEST['device_type']) && !empty($_REQUEST['manufacturer'])) {
		check_device_and_manufacturer();

		$sql = "SELECT * from devices where manufacturer = ? and device_type = ?  limit 10000";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "ss", $_REQUEST['manufacturer'], $_REQUEST['device_type']);
	}
	// query by device type and serial number
	else if (!empty($_REQUEST['device_type']) && !empty($_REQUEST['sn'])) {
		check_device_and_manufacturer();

		$sql = "SELECT * from devices where serial_number like ? and device_type = ?  Limit 10000";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "ss", $serial_number_query, $_REQUEST['device_type']);
	}
	// query by manufacturer and serial number
	else if (!empty($_REQUEST['manufacturer']) && !empty($_REQUEST['sn'])) {
		check_device_and_manufacturer();

		$sql = "SELECT * from devices where serial_number like ? and manufacturer = ?  Limit 10000";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "ss", $serial_number_query, $_REQUEST['manufacturer']);
	}
	// query by only device type
	else if (!empty($_REQUEST['device_type'])) {
		check_device_and_manufacturer();

		$sql = "SELECT * from devices where device_type = ?  Limit 10000";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "s", $_REQUEST['device_type']);
	}
	//query by only manufacturer 
	else if (!empty($_REQUEST['manufacturer'])) {
		check_device_and_manufacturer();

		$sql = "SELECT * from devices where manufacturer = ?  Limit 10000";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "s", $_REQUEST['manufacturer']);
	}
	// query by only serial number
	else if (!empty($_REQUEST['sn'])) {
		$sql = "SELECT * from devices where serial_number like  ? Limit 10000 ";

		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "s", $serial_number_query);
	}
	// execute query and bind query
	if (!mysqli_stmt_execute($stmt))
		exit(mysqli_stmt_error($stmt));
	mysqli_stmt_bind_result($stmt, $id, $device_type, $manufacturer, $sn, $status);

	while (mysqli_stmt_fetch($stmt)) {
		$list[] =  str_replace("\r", "", "$id $device_type $manufacturer $sn $status");
	}
	if (mysqli_stmt_num_rows($stmt) > 0) {
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array("Status: Success! search results below", "ID, Device type, Manufacturer, Serial number, Status", $list), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	} else {
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array("MSG: No devices found - try again"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
} else {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	echo json_encode(array("Status: Blank ID", "Device ID must not be blank"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	die();
}
