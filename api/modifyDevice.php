<?php
include_once "../inc/.env.php";

if ($_REQUEST['id'] != NULL) {
    $id = $_REQUEST['id'];
    if (!is_numeric($_REQUEST['id']) && $_REQUEST['id'] != NULL) {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Invalid ID", "Device ID must be a positive integer"), JSON_PRETTY_PRINT);
        die();
    }
    // check if device id exists
    $sql = "Select id from `devices` where `id`= ?";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    // execute query and bind query
    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));

    mysqli_stmt_bind_result($stmt, $id);
    mysqli_stmt_store_result($stmt);

    // if device exists
    if (mysqli_stmt_num_rows($stmt) > 0) {
        if ($_REQUEST['device_type'] == NULL && $_REQUEST['manufacturer'] == NULL && $_REQUEST['sn'] == NULL) {
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            echo json_encode(array("Status: ERROR", "Device type, Manufactuer, and serial number cannot all be blank - choose at least one column to modify"), JSON_PRETTY_PRINT);
            die();
        }

        // update device type
        if ($_REQUEST['device_type'] != NULL) {
            $newDeviceType = $_REQUEST['device_type'];
            mysqli_stmt_prepare($stmt, "UPDATE devices set device_type = ? where id= ?");
            mysqli_stmt_bind_param($stmt, "ss", $newDeviceType, $id);

            if (!mysqli_stmt_execute($stmt))
                exit(mysqli_stmt_error($stmt));

            echo json_encode(array("Status: OK", "New device type: $newDeviceType"), JSON_PRETTY_PRINT);
        }
        // update manufacturer
        if (!empty($_REQUEST['manufacturer'])) {
            $newManufacturer = $_REQUEST['manufacturer'];
            mysqli_stmt_prepare($stmt, "UPDATE devices set manufacturer= ? where id=?");
            mysqli_stmt_bind_param($stmt, "ss", $newManufacturer, $id);

            if (!mysqli_stmt_execute($stmt))
                exit(mysqli_stmt_error($stmt));

            echo json_encode(array("Status: OK", "New manufacturer: $newManufacturer"), JSON_PRETTY_PRINT);
        }
        // update serial number
        if (!empty($_REQUEST['sn'])) {
            $newSerialNumber = $_REQUEST['sn'];

            $exists = false;
            mysqli_stmt_prepare($stmt, "SELECT serial_number FROM devices");

            // checks if serial number exists
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $current_serial_number);
                while (mysqli_stmt_fetch($stmt)) {
                    if ($current_serial_number == $newSerialNumber) {
                        $exists = true;
                        break;
                    }
                }
            } else
                exit(mysqli_stmt_error($stmt));

            // if serial dosent exsits, update serial number
            if (!$exists) {
                mysqli_stmt_prepare($stmt, "UPDATE devices set serial_number=? where id=?");
                mysqli_stmt_bind_param($stmt, "si", $newSerialNumber, $id);

                if (!mysqli_stmt_execute($stmt))
                    exit(mysqli_stmt_error($stmt));

                echo json_encode(array("Status: OK", "New serial number: $newSerialNumber"), JSON_PRETTY_PRINT);
            }
            // if serial number already exists 
            else
                echo json_encode(array("Status: ERROR", "Serial number already exists"), JSON_PRETTY_PRINT);
        }
    }
    // if device dosen't exists 
    else {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Not Found", "MSG: Device Id: $id not in database"), JSON_PRETTY_PRINT);
    }
}
// if no id is given 
else {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: No input", "Device ID is empty"), JSON_PRETTY_PRINT);
    die();
}
