<?php
include_once "inc/.env.php";
session_start();

if (!isset($_GET['id'])) {
    $_SESSION['message'] = "ERROR: No device selected";
    $_SESSION['alert'] = "alert alert-danger alert-dismissible fade show";
    header("location: index.php");
    exit();
}
function get_device()
{
    global $stmt;
    $sql = "SELECT type from device_type";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $device_type);
?>
    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="newDeviceType">
        <option disabled selected value>Device Type</option>

        <?php
        while (mysqli_stmt_fetch($stmt)) {
        ?>
            <option value="<?php echo $device_type ?>"><?php echo $device_type ?></option>
        <?php } ?>

    </select>
<?php
}
function get_manufacturer()
{
    global $stmt;
    $sql = "SELECT manu_name from manufacturers";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $manufacturer);
?>
    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="newManufacturer">
        <option disabled selected value>Manufacturer</option>

        <?php
        while (mysqli_stmt_fetch($stmt)) {
        ?>
            <option value="<?php echo $manufacturer ?>"><?php echo $manufacturer ?></option>
        <?php } ?>

    </select>
<?php
}
function get_selectedDevice()
{
    global $stmt;
    $sql = "SELECT * from devices where id = ?";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $device_type, $manufacturer, $serial_number, $status);
    mysqli_stmt_fetch($stmt);
?>
    <div class="table-responsive">
        <table class="table table-centered table-nowrap mb-0 ">
            <thead>
                <tr>
                    <th>ID</th>

                    <th>Device Type</th>
                    <th>manufacturer</th>
                    <th>Serial Number</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $id ?></td>
                    <td><?php echo $device_type ?></td>
                    <td><?php echo $manufacturer ?></td>
                    <td><?php echo $serial_number ?></td>

                </tr>
            </tbody>
        </table>
    </div>
<?php
}
include_once "inc/head.php";
include_once "inc/navbar.php";
?>
<div class="container">
    <div class="py-5 text-center">
        <h2>Update device</h2>
        <p class="lead">Selected device</p>
    </div>
    <?php get_selectedDevice(); ?>
    <div class="col-md-8 order-md-1">
        <?php include_once "inc/alerts.php"; ?>

        <h4 class="mb-3">Update device</h4>
        <form action="inc/updateDevice.php?id=<?php echo $_GET['id'] ?>" method=POST>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>New device Type</label>
                    <?php get_device(); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label>New manufacturer</label>
                    <?php get_manufacturer(); ?>
                </div>
            </div>

            <div class="mb-3">
                <label>New serial Number</label>
                <div class="input-group">
                    <input type="text" class="form-control" name='newSerialNumber' placeholder="Enter a serial number">
                </div>
            </div>
            <button class="btn btn-primary btn-lg btn-block rounded-pill" type="submit" name='updateDevice'>update</button>
        </form>

    </div>
</div>
<?php
include_once "inc/footer.php";
?>