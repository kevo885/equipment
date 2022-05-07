<?php
include_once "../inc/.env.php";
$id = $_REQUEST['id'];
// has to be a positive int
if (!is_numeric($id) && $id != NULL) {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: Invalid ID", "Device ID must be a positive integer"), JSON_PRETTY_PRINT);
    die();
}
// if blank
elseif ($id == NULL) {
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("Status: Blank ID", "Device ID must not be blank - /equipment/api/index.php/?DeleteDevice&id=device_id"), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    die();
}
// delete device
else {
    $getDevice = "SELECT id from devices where id = ?";
    mysqli_stmt_prepare($stmt, $getDevice);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));

    mysqli_stmt_bind_result($stmt, $id);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $delete = "DELETE from `devices` where `id`= ?";
        mysqli_stmt_prepare($stmt, $delete);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        // execute query and bind query
        if (!mysqli_stmt_execute($stmt))
            exit(mysqli_stmt_error($stmt));
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        $output = array("Status: Device deleted", "Device ID: $id was deleted");
        echo json_encode($output, JSON_PRETTY_PRINT);
    } else {
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode(array("Status: Could not delete - device not found", "MSG: Device Id: $id not in database"), JSON_PRETTY_PRINT);
    }
}
