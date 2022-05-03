<?php
include_once "../inc/.env.php";
$device_type = $_REQUEST['device_type'];
$manufacturer = $_REQUEST['manufacturer'];
$serial_number = $_REQUEST['sn'];

// if blank
if ($device_type == NULL || $manufacturer == NULL || $serial_number == NULL) {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: No input", "Device type, Manufactuer, and serial number cannot be blank"), JSON_PRETTY_PRINT);
    die();
}
// add device
// checks if serial_number exists already
$check_serial_number = "SELECT * FROM devices WHERE serial_number=?";

// preparing statement
mysqli_stmt_prepare($stmt, $check_serial_number);
mysqli_stmt_bind_param($stmt, 's', $serial_number);

if (!mysqli_stmt_execute($stmt))
    exit(mysqli_stmt_error($stmt));

mysqli_stmt_store_result($stmt);

// if serial_number is taken, print error 
if (mysqli_stmt_num_rows($stmt) > 0) {
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
    $output = array("Status: OK", "Device Info: ", "Device type: $device_type", 'Maufacturer:' . $manufacturer, 'Serial Number:' . $serial_number);
    echo json_encode($output, JSON_PRETTY_PRINT);
}
