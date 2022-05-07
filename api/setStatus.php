<?php
include_once "../inc/.env.php";
include_once "../inc/functions.php";

if ($_REQUEST['id'] != NULL) {
    $id = $_REQUEST['id'];

    // has to be a positive int
    if (!is_numeric($id) && $id != NULL) {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Invalid ID", "Device ID must be a positive integer"), JSON_PRETTY_PRINT);
        echo json_encode(array("API: SetStatus", "Usage: Enable or disables a device", "Parameters:id=validID&status=status", "How to run:/equipment/api/?SetStatus&Parameters", "Errors: Invalid ID | device does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        die();
    }
    // check if device exists
    if (device_exists($id)) {
        // if status is empty
        if ($_REQUEST['status'] == NULL) {
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            echo json_encode(array("Status: ERROR", "MSG: No status was given"), JSON_PRETTY_PRINT);
            echo json_encode(array("API: SetStatus", "Usage: Enable or disables a device", "Parameters:id=validID&status=status", "How to run:/equipment/api/?SetStatus&Parameters", "Errors: Invalid ID | device does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            die();
        }
        // set status
        else {
            $status = $_REQUEST['status'];

            if (strtoupper($status) == "ENABLE" || strtoupper($status) == "DISABLE") {
                $sql = "UPDATE devices set status = ? where id= ?";
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, 'si', $status, $id);

                // execute query and bind query
                if (!mysqli_stmt_execute($stmt))
                    exit(mysqli_stmt_error($stmt));

                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                echo json_encode(array("Status: OK", "MSG: Changed status of device id $id to $status"), JSON_PRETTY_PRINT);
            }
            // invalid status 
            else {
                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                echo json_encode(array("Status: Invalid status", "Invalid status given: Only choose between enable or disable"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                echo json_encode(array("API: SetStatus", "Usage: Enable or disables a device", "Parameters:id=validID&status=status", "How to run:/equipment/api/?SetStatus&Parameters", "Errors: Invalid ID | device does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

                die();
            }
        }
    }
    // if device dosen't exist
    else {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Invalid ID", "Device ID does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo json_encode(array("API: SetStatus", "Usage: Enable or disables a device", "Parameters:id=validID&status=status", "How to run:/equipment/api/?SetStatus&Parameters", "Errors: Invalid ID | device does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        die();
    }
}
// if id is blank
else {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: Blank ID", "Device ID must not be blank"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo json_encode(array("API: SetStatus", "Usage: Enable or disables a device", "Parameters:id=validID&status=status", "How to run:/equipment/api/?SetStatus&Parameters", "Errors: Invalid ID | device does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    die();
}
