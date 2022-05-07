<?php
include_once ".env.php";
include_once "alerts.php";
session_start();
// update device info
if (isset($_POST['updateDevice'])) {
    $id = $_GET['id'];
    // new device info
    $newDeviceType = $_POST['device_type'];
    $newManufacturer = $_POST['manufacturer'];
    $newSerialNumber = $_POST['newSerialNumber'];

    // if no input field was entered before submitting form
    if (empty($newDeviceType) && empty($newManufacturer) && empty($newSerialNumber)) {
        message("alert alert-warning alert-dismissible fade show", "Warning: did not enter any input");
        header("location: ../update.php?id=$id");

        exit();
    }
    // update device type
    if (!empty($newDeviceType)) {
        mysqli_stmt_prepare($stmt, "UPDATE devices set device_type=? where id=?");
        mysqli_stmt_bind_param($stmt, "ss", $newDeviceType, $id);

        if (!mysqli_stmt_execute($stmt))
            exit(mysqli_stmt_error($stmt));

        message("alert alert-success alert-dismissible fade show", "updated device");
        header("location: ../update.php?id=$id");
    }
    // update manufacturer
    if (!empty($newManufacturer)) {
        mysqli_stmt_prepare($stmt, "UPDATE devices set manufacturer=? where id=?");
        mysqli_stmt_bind_param($stmt, "ss", $newManufacturer, $id);

        if (!mysqli_stmt_execute($stmt))
            exit(mysqli_stmt_error($stmt));

        message("alert alert-success alert-dismissible fade show", "updated device");
        header("location: ../update.php?id=$id");
    }
    // update serial number
    if (!empty($newSerialNumber)) {
        $exists = false;
        mysqli_stmt_prepare($stmt, "SELECT serial_number FROM devices");

        // checks if serial number exists
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $serial_number);
            while (mysqli_stmt_fetch($stmt)) {
                if ($newSerialNumber == $serial_number) {
                    $exists = true;
                    break;
                }
            }
        } else
            exit(mysqli_stmt_error($stmt));

        // if serial dosent exsits, update serial number
        if (!$exists) {
            mysqli_stmt_prepare($stmt, "UPDATE devices set serial_number=? where id=?");
            mysqli_stmt_bind_param($stmt, "ss", $newSerialNumber, $id);

            if (!mysqli_stmt_execute($stmt))
                exit(mysqli_stmt_error($stmt));

            message("alert alert-success alert-dismissible fade show", "updated device");
            header("location: ../update.php?id=$id");
        }
        // if serial number already exists 
        else {
            message("alert alert-danger alert-dismissible fade show", "failed to update serial number - serial number already exists");
            header("location: ../update.php?id=$id");
        }
    }
}
