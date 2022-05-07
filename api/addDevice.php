<?php
include_once "../inc/.env.php";
include_once "../inc/functions.php";

$device_type = $_REQUEST['device_type'];
$manufacturer = $_REQUEST['manufacturer'];
$serial_number = $_REQUEST['sn'];
// if not all fields are entered
if ($device_type == NULL || $manufacturer == NULL || $serial_number == NULL) {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: Error", "MSG: Device type, Manufactuer, and serial number cannot be blank"), JSON_PRETTY_PRINT);
    echo json_encode(array("API: AddDevice", "Usage: Adds a device", "Parameters:device_type=device_type&manufacturers=manufacturers&sn=serial_number", "How to run:/equipment/api/?AddDevice&Parameters", "Errors: All fields are required | Serial number already taken | Device type or manufacturer doesn't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    die();
}
check_device_and_manufacturer();
// if serial_number is taken, print error 
if (serial_number_exists($serial_number) > 0) {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: ERROR", "MSG: Serial number already exists"), JSON_PRETTY_PRINT);
    echo json_encode(array("API: AddDevice", "Usage: Adds a device", "Parameters:device_type=device_type&manufacturers=manufacturers&sn=serial_number", "How to run:/equipment/api/?AddDevice&Parameters", "Errors: All fields are required | Serial number already taken | Device type or manufacturer doesn't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
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
