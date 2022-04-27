<?php
include_once ".env.php";
include_once "alerts.php";
session_start();

// update device info
if (isset($_POST['upload'])) {
    $id = $_GET['id']; // id of selected device
    $filename = $_FILES['userfile']['name'];
    $filesize = $_FILES['userfile']['size'];
    $tmpName = $_FILES['userfile']['tmp_name'];
    $filetype = $_FILES['userfile']['type'];
    $target_dir = "../../files/";
    $target_file = $target_dir . basename($filename);
    $error = 0;

    // Check file size max size of 50 mb
    if ($filesize > 50000000) {
        $_SESSION['message'] .= " File size is too large";
        $error = 1;
    }
    if ($error == 1) {
        $_SESSION['message'] .= " File not uploaded";
        $_SESSION['alert'] = "alert alert-danger alert-dismissible fade show";
        header("location: ../upload.php?id=$id");
        exit();

        // upload file 
    } else {
        move_uploaded_file($tmpName, $target_file);
        $insert = "INSERT into files (file_name,file_type,file_size,device_id) values (?,?,?,?)";
        mysqli_stmt_prepare($stmt, $insert);
        mysqli_stmt_bind_param($stmt, "ssii", $filename, $filetype, $filesize, $id);

        if (!mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] .= mysqli_stmt_error($stmt);
            $_SESSION['alert'] = "alert alert-danger alert-dismissible fade show";
            header("location: ../upload.php?id=$id");
            exit();
        }
        $_SESSION['message'] = "Successfully uploaded $filename";
        $_SESSION['alert'] = "alert alert-success alert-dismissible fade show";
        header("location: ../upload.php?id=$id");
        exit();
    }
}
// if no device is selected, redirect to query
else {
    message("alert alert-danger alert-dismissible fade show", "Select device to upload file to");
    header("location: ../upload.php?id=$_GET[id]");
    exit();
}
