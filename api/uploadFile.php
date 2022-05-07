<?php
include_once "../inc/.env.php";
include_once "../inc/functions.php";
session_start();
if ($_REQUEST['id'] != NULL) {
    $id = $_REQUEST['id'];

    // has to be a positive int
    if (!is_numeric($id) && $id != NULL) {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Invalid ID", "Device ID must be a positive integer"), JSON_PRETTY_PRINT);
        echo json_encode(array("API: UploadFile", "Usage: Upload a file to a device via file path", "Parameters:id=validID&file_path=file_path", "How to run:/equipment/api/?UploadFile&Parameteres", "Errors: ID is invalid | Device ID dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        die();
    }
    if (device_exists($id)) {
        if ($_REQUEST['file_path'] == NULL) {
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            echo json_encode(array("Status: Empty file path", "Enter the path of file for the file you like to upload"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            echo json_encode(array("API: UploadFile", "Usage: Upload a file to a device via file path", "Parameters:id=validID&file_path=file_path", "How to run:/equipment/api/?UploadFile&Parameteres", "Errors: ID is invalid | Device ID dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            die();
        } else {
            $file = $_REQUEST['file_path'];
            $fileInfo = pathinfo($file);
            $filename = $fileInfo['basename'];
            $filetype = $fileInfo['extension'];
            $filesize = byteconverter(filesize($_REQUEST['file_path']));
            $target_dir = "/Users/MacBook/Library/Mobile Documents/com~apple~CloudDocs/equipment/files/";
            $target_file = $target_dir . basename($file);

            if (is_file($file) && file_exists($file) && $filesize > 0) {
                // file_put_contents($target_file, file_get_contents($file));
                // $insert = "INSERT into files (file_name,file_type,file_size,device_id) values (?,?,?,?)";
                // mysqli_stmt_prepare($stmt, $insert);
                // mysqli_stmt_bind_param($stmt, "ssii", $filename, $filetype, $filesize, $id);

                // if (!mysqli_stmt_execute($stmt))
                //     exit(mysqli_stmt_error($stmt));

                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                echo json_encode(array("Status: Upload success", "File name: $file", "File type: $filetype", "File size: $filesize"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                exit();
            } else if ($filesize <= 0) {
                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                echo json_encode(array("Status: ERROR", "$file file size is corrupted"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                die();
            } else if (!is_file($file)) {
                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                echo json_encode(array("Status: ERROR", "$file is a directory"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                die();
            }
        }
    }
    // if device dosen't exist
    else {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Invalid ID", "Device ID does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo json_encode(array("API: UploadFile", "Usage: Upload a file to a device via file path", "Parameters:id=validID&file_path=file_path", "How to run:/equipment/api/?UploadFile&Parameteres", "Errors: ID is invalid | Device ID dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        die();
    }
}
// if id is blank
else {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: Blank ID", "Device ID must not be blank"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo json_encode(array("API: UploadFile", "Usage: Upload a file to a device via file path", "Parameters:id=validID&file_path=file_path", "How to run:/equipment/api/?UploadFile&Parameteres", "Errors: ID is invalid | Device ID dosen't exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    die();
}
