<?php
include_once "inc/.env.php";

// if form not submited redirect to query selection page
if (!isset($_POST['submit']))
    header("index.php");

// used to search serial number by certain letters and numbers
if (!empty($_POST['serial_number']))
    $serial_number_query = "%{$_POST['serial_number']}%";

// if search by all three types
if (isset($_POST['device_type']) && isset($_POST['manufacturer']) && !empty($_POST['serial_number'])) {
    $sql = "SELECT * from devices where manufacturer = ? and device_type = ? and serial_number like ? limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $_POST['manufacturer'], $_POST['device_type'], $serial_number_query);
}
// query by device type and manufacturer 
else if (isset($_POST['device_type']) && isset($_POST['manufacturer'])) {
    $sql = "SELECT * from devices where manufacturer = ? and device_type = ?  limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $_POST['manufacturer'], $_POST['device_type']);
}
// query by device type and serial number
else if (isset($_POST['device_type']) && !empty($_POST['serial_number'])) {
    $sql = "SELECT * from devices where serial_number like ? and device_type = ?  limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $serial_number_query, $_POST['device_type']);
}
// query by manufacturer and serial number
else if (isset($_POST['manufacturer']) && !empty($_POST['serial_number'])) {
    $sql = "SELECT * from devices where serial_number like ? and manufacturer = ?  limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $serial_number_query, $_POST['manufacturer']);
}
// querby by only device type
else if (isset($_POST['device_type'])) {
    $sql = "SELECT * from devices where device_type = ?  limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_POST['device_type']);
}
//query by only manufacturer 
else if (isset($_POST['manufacturer'])) {
    $sql = "SELECT * from devices where manufacturer = ?  limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_POST['manufacturer']);
}
// query by only serial number
else if (!empty($_POST['serial_number'])) {
    $sql = "SELECT * from devices where serial_number like  ?  limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $serial_number_query);
} else {
    echo 'Nothing selected';
    header("Location: index.php");
}
// execute query
if (!mysqli_stmt_execute($stmt))
    exit(mysqli_stmt_error($stmt));

mysqli_stmt_bind_result($stmt, $id, $name, $company, $sn);

?>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>ID</td>
                <td>Device Type</td>
                <td>Manufacturer</td>
                <td>Serial Number</td>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($stmt)) {
            ?>
                <tr>
                    <td><?php echo $id; ?></td>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $company; ?></td>
                    <td><?php echo $sn; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>