<?php
include_once ".env.php";
session_start();

if (isset($_POST['addDevice'])) {
    // checks if serial_number exists already
    $check_serial_number = "SELECT * FROM devices WHERE serial_number=?";

    // preparing statement
    mysqli_stmt_prepare($stmt, $check_serial_number);
    mysqli_stmt_bind_param($stmt, 's', $_POST['serial_number']);

    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));

    mysqli_stmt_store_result($stmt);

    // if serial_number is taken, print error 
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['message'] = "ERROR: serial number already exists.";
        $_SESSION['alert'] = "alert alert-danger alert-dismissible fade show";
        header("location: ../add.php");
        exit();
    }
    // if  serial_number isn't taken, insert into database
    else {
        $insert = "INSERT INTO devices (device_type,manufacturer,serial_number) values (?,?,?)";

        // prepare for insert
        mysqli_stmt_prepare($stmt, $insert);
        mysqli_stmt_bind_param($stmt, "sss", $_POST['device_type'], $_POST['manufacturer'], $_POST['serial_number']);

        if (!mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = mysqli_stmt_error($stmt);
            $_SESSION['alert'] = "alert alert-danger alert-dismissible fade show";
            header("location: ../add.php");
        }
        // successfully created device, redirect to add page
        else {
            $_SESSION['alert'] = "alert alert-success alert-dismissible fade show";
            $_SESSION['message'] = "Successfully added $_POST[device_type] $_POST[manufacturer]";
            header("location: ../add.php");
            exit();
        }
    }
}
