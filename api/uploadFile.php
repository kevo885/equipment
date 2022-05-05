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
        die();
    }
    if (device_exists($id)) {
        if ($_REQUEST['filepath'] == NULL) {
            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            echo json_encode(array("Status: Empty file path", "Enter the path of file for the file you like to upload"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            die();
        } else {
            $file = $_REQUEST['filepath'];
            $fileInfo = pathinfo($file);
            $filename = $fileInfo['basename'];
            $filetype = $fileInfo['extension'];
            $filesize = byteconverter(filesize($file));
            $target_dir = "/Users/MacBook/Library/Mobile Documents/com~apple~CloudDocs/equipment/files/";
            $target_file = $target_dir . basename($file);

            file_put_contents($target_file, file_get_contents($file));
            $insert = "INSERT into files (file_name,file_type,file_size,device_id) values (?,?,?,?)";
            mysqli_stmt_prepare($stmt, $insert);
            mysqli_stmt_bind_param($stmt, "ssii", $filename, $filetype, $filesize, $id);

            if (!mysqli_stmt_execute($stmt))
                die(mysqli_stmt_error($stmt));

            header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            echo json_encode(array("Status: Upload success", "File name: $filename", "File type: $filetype", "File size: $filesize"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            die();
        }
    }
    // if device dosen't exist
    else {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Invalid ID", "Device ID does not exists"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        die();
    }
}
// if id is blank
else {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: Blank ID", "Device ID must not be blank"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    die();
}
