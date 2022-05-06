<?php
include_once "../inc/.env.php";
include_once "../inc/functions.php";
$id = $_GET['id'];
// has to be a positive int
if (!is_numeric($id) && $id != NULL) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	echo json_encode(array("Status: Invalid ID", "Device ID must be a positive integer"), JSON_PRETTY_PRINT);
	die();
}
// if blank
elseif ($id == NULL) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	echo json_encode(array("Status: Blank ID", "Device ID must not be blank"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	die();
}
// get data
else {
	// if (!valid_device_type($_REQUEST['device_type'])) {
	// 	header('Content-Type: application/json');
	// 	header('HTTP/1.1 200 OK');
	// 	echo json_encode(array("Status: Invalid ID", "Device ID must be a positive integer"), JSON_PRETTY_PRINT);
	// }
	if (!in_array($_REQUEST['test'], get_device_type())) {
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array("Status: $_REQUEST[test] Not in array"), JSON_PRETTY_PRINT);
	}
	if (!in_array(ucfirst($_REQUEST['manufacturer']), get_manufacturer())) {
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array(ucfirst($_REQUEST['manufacturer']) . " Not in array"), JSON_PRETTY_PRINT);
	}
	$sql = "Select * from `devices` where `id`= ?";
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, 'i', $id);


	// execute query and bind query
	if (!mysqli_stmt_execute($stmt))
		exit(mysqli_stmt_error($stmt));
	mysqli_stmt_bind_result($stmt, $id, $device_type, $manufacturer, $sn, $status);
	mysqli_stmt_store_result($stmt);
	// get device
	if (mysqli_stmt_num_rows($stmt) > 0) {
		while (mysqli_stmt_fetch($stmt)) {
			header('Content-Type: application/json');
			header('HTTP/1.1 200 OK');
			$output = array("Status: OK", "Device Info: ", "Device ID: $id", 'Maufacturer:' . $manufacturer, 'Device Type:' . $device_type, 'Serial Number:' . $sn, 'Status:' . $status);
			echo json_encode($output, JSON_PRETTY_PRINT);
		}
	}
	// no device
	else {
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		echo json_encode(array("Status: Not Found", "MSG: Device Id: $id not in database"), JSON_PRETTY_PRINT);
	}
}
