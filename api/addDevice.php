<?php
include_once "../inc/.env.php";
include_once "../inc/functions.php";

$device_type = $_REQUEST['device_type'];
$manufacturer = $_REQUEST['manufacturer'];
$serial_number = $_REQUEST['sn'];
$capitalizeManufacturer = array_map('strtoupper', get_manufacturer());
// if not all fields are entered
if ($device_type == NULL || $manufacturer == NULL || $serial_number == NULL) {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: Error", "MSG: Device type, Manufactuer, and serial number cannot be blank"), JSON_PRETTY_PRINT);
    die();
}
if (!in_array($_REQUEST['device_type'], get_device_type()) || !in_array(strtoupper($_REQUEST['manufacturer']), $capitalizeManufacturer)) {

    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');

    if (!in_array($_REQUEST['device_type'], get_device_type()))
        echo json_encode(array("Status: ERROR - $_REQUEST[device_type] is not a valid device type", "Available device types", get_device_type()), JSON_PRETTY_PRINT);

    if (!in_array(strtoupper($_REQUEST['manufacturer']), $capitalizeManufacturer))
        echo json_encode(array("Status: ERROR - $_REQUEST[manufacturer] is not a valid manufacturer", "Available manufacturers", get_manufacturer()), JSON_PRETTY_PRINT);

    die();
}
// if serial_number is taken, print error 
if (serial_number_exists($serial_number) > 0) {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: ERROR", "MSG: Serial number already exists"), JSON_PRETTY_PRINT);
    die();
}
// if  serial_number isn't taken, insert into database
else {
    $insert = "INSERT INTO devices (device_type,manufacturer,serial_number) values (?,?,?)";

    // prepare for insert
    mysqli_stmt_prepare($stmt, $insert);
    mysqli_stmt_bind_param($stmt, "sss", $device_type, $manufacturer, $serial_number);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_error($stmt);
        die();
    }
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output = array("Status: Successfully added device", "Device Info: ", "Device type: $device_type", 'Maufacturer:' . $manufacturer, 'Serial Number:' . $serial_number);
    echo json_encode($output, JSON_PRETTY_PRINT);
}
