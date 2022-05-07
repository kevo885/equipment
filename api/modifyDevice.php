<?php
include_once "../inc/.env.php";
include_once "../inc/functions.php";
error_reporting(0);

if ($_REQUEST['id'] != NULL) {
    $id = $_REQUEST['id'];
    if (!is_numeric($_REQUEST['id']) && $_REQUEST['id'] != NULL) {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Invalid ID", "Device ID must be a positive integer"), JSON_PRETTY_PRINT);
        echo json_encode(array("API: ModifyDevice", "Usage: Modify a device - Not all parameter fields are required - may choose 1 or multiple to update", "Parameters:id=ID_to_modify&device_type=new_device_type&manufacturer=new_manufacturer&sn=new_serial_num", "How to run:/equipment/api/?ModifyDevice&Parameteres", "Errors: At least one parameter must be given | ID is invalid | Device ID dosen't exists", "Warnings - won't update column with warning: Serial Number is already taken, Device type dosen't exists, Manufacturer dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        die();
    }
    // if device exists
    if (device_exists($id) > 0) {
        get_selectedDevice_API($id, "Current device info");

        if ($_REQUEST['device_type'] == NULL && $_REQUEST['manufacturer'] == NULL && $_REQUEST['sn'] == NULL) {
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            echo json_encode(array("ERROR", "Device type, Manufactuer, and serial number cannot all be blank - choose at least one column to modify"), JSON_PRETTY_PRINT);
            echo json_encode(array("API: ModifyDevice", "Usage: Modify a device - Not all parameter fields are required - may choose 1 or multiple to update", "Parameters:id=ID_to_modify&device_type=new_device_type&manufacturer=new_manufacturer&sn=new_serial_num", "How to run:/equipment/api/?ModifyDevice&Parameteres", "Errors: At least one parameter must be given | ID is invalid | Device ID dosen't exists", "Warnings - won't update column with warning: Serial Number is already taken, Device type dosen't exists, Manufacturer dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            die();
        }

        // update device type
        if ($_REQUEST['device_type'] != NULL) {
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            if (!in_array($_REQUEST['device_type'], get_device_type())) {
                echo json_encode(array("ERROR - $_REQUEST[device_type] is not a valid device type", "Device type was not updated", "Select an available device type", get_device_type()), JSON_PRETTY_PRINT);
            } else {
                $newDeviceType = $_REQUEST['device_type'];
                mysqli_stmt_prepare($stmt, "UPDATE devices set device_type = ? where id= ?");
                mysqli_stmt_bind_param($stmt, "ss", $newDeviceType, $id);

                if (!mysqli_stmt_execute($stmt))
                    exit(mysqli_stmt_error($stmt));

                echo json_encode(array("Successfully updated device type", "New device type: $newDeviceType"), JSON_PRETTY_PRINT);
            }
        }
        // update manufacturer
        if (!empty($_REQUEST['manufacturer'])) {
            $capitalizeManufacturer = array_map('strtoupper', get_manufacturer());

            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');

            if (!in_array(strtoupper($_REQUEST['manufacturer']), $capitalizeManufacturer)) {
                echo json_encode(array("ERROR - $_REQUEST[manufacturer] is not a valid manufacturer", "Manufacturer was not updated", "Select an available manufacturer", get_manufacturer()), JSON_PRETTY_PRINT);
            } else {
                $newManufacturer = $_REQUEST['manufacturer'];
                mysqli_stmt_prepare($stmt, "UPDATE devices set manufacturer= ? where id=?");
                mysqli_stmt_bind_param($stmt, "ss", $newManufacturer, $id);

                if (!mysqli_stmt_execute($stmt))
                    exit(mysqli_stmt_error($stmt));

                echo json_encode(array("Successfully updated manufacturer", "New manufacturer: $newManufacturer"), JSON_PRETTY_PRINT);
            }
        }
        // update serial number
        if (!empty($_REQUEST['sn'])) {
            $newSerialNumber = $_REQUEST['sn'];

            // checks if serial number exists
            if (serial_number_exists($newSerialNumber) > 0) {
                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                echo json_encode(array("ERROR", "MSG: Serial number already exists", "Serial number was not updated"), JSON_PRETTY_PRINT);
            }
            // if serial dosent exist, update serial number
            else {
                mysqli_stmt_prepare($stmt, "UPDATE devices set serial_number=? where id=?");
                mysqli_stmt_bind_param($stmt, "si", $newSerialNumber, $id);

                if (!mysqli_stmt_execute($stmt))
                    exit(mysqli_stmt_error($stmt));

                echo json_encode(array("OK", "New serial number: $newSerialNumber"), JSON_PRETTY_PRINT);
            }
        }
        get_selectedDevice_API($id, "New device info $count");
    }
    // if device dosen't exists 
    else {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Not Found", "MSG: Device Id $id does not exist"), JSON_PRETTY_PRINT);
        echo json_encode(array("API: ModifyDevice", "Usage: Modify a device - Not all parameter fields are required - may choose 1 or multiple to update", "Parameters:id=ID_to_modify&device_type=new_device_type&manufacturer=new_manufacturer&sn=new_serial_num", "How to run:/equipment/api/?ModifyDevice&Parameteres", "Errors: At least one parameter must be given | ID is invalid | Device ID dosen't exists", "Warnings - won't update column with warning: Serial Number is already taken, Device type dosen't exists, Manufacturer dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
// if no id is given 
else {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("status: Error", "No ID was given"), JSON_PRETTY_PRINT);
    echo json_encode(array("API: ModifyDevice", "Usage: Modify a device - Not all parameter fields are required - may choose 1 or multiple to update", "Parameters:id=ID_to_modify&device_type=new_device_type&manufacturer=new_manufacturer&sn=new_serial_num", "How to run:/equipment/api/?ModifyDevice&Parameteres", "Errors: At least one parameter must be given | ID is invalid | Device ID dosen't exists", "Warnings - won't update column with warning: Serial Number is already taken, Device type dosen't exists, Manufacturer dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    die();
}
