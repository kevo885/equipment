<?php
include_once "inc/.env.php";

// if form not submited redirect to query selection page
if (!isset($_POST['submit']))
    header("index.php");

$device_type = $_POST['device_type'];
$manufacturer = $_POST['manufacturer'];
$serial_number = "%{$_POST['serial_number']}%";

if (isset($_POST['device_type'])) {
    $sql = "SELECT * from devices where device_type = ? limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $device_type);
}
if (isset($_POST['manufacturer'])) {
    $sql = "SELECT * from devices where manufacturer = ? limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $manufacturer);
}
if (isset($_POST['serial_number'])) {
    $sql = "SELECT * from devices where serial_number like ? limit 10";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $serial_number);
}
// if (isset($_POST['manufacturer']) and isset($_POST['device_type'])) {
//     $manufacturer = $_POST['manufacturer'];
//     $device_type = $_POST['device_type'];
//     $sql = "SELECT * from devices where manufacturer = ? and device_type = ? limit 10";

//     mysqli_stmt_prepare($stmt, $sql);
//     mysqli_stmt_bind_param($stmt, "ss", $manufacturer, $device_type);
// }
if (!mysqli_stmt_execute($stmt))
    exit(mysqli_stmt_error($stmt));

mysqli_stmt_bind_result($stmt, $id, $name, $company, $sn);

?>
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