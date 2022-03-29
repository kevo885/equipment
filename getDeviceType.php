<?php
include_once "inc/.env.php";

// if form not submited redirect to device selection page
if (!isset($device_type))
    header("index.php");

$device_type = $_POST['type'];
$sql = "SELECT * from devices where device_type = ? limit 10";

mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "s", $device_type);

if (!mysqli_stmt_execute($stmt))
    exit(mysqli_stmt_error($stmt));

mysqli_stmt_bind_result($stmt, $id, $name, $company, $sn);
echo "Device choosen is: $_POST[type]";

?>
<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>Manufacturer</td>
            <td>Serial Number</td>
        </tr>
    </thead>
    <tbody>
        <?php while (mysqli_stmt_fetch($stmt)) {
        ?>
            <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $company; ?></td>
                <td><?php echo $sn; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>