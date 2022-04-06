<?php
include_once "inc/.env.php";
include_once "inc/sizeConversion.php";

session_start();

if (!isset($_GET['id']) && !empty($_GET['id'])) {
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
    mysqli_stmt_bind_result($stmt, $id, $device_type, $manufacturer, $serial_number);
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
function get_files()
{
    global $stmt;

    $sql = "SELECT file_name , file_size from files where device_id = ?";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $filename, $byteSize);
    mysqli_stmt_store_result($stmt);
    $target_dir = "/Users/MacBook/Library/Mobile Documents/com~apple~CloudDocs/equipment/files/";


    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo ' <h5 class="mb-2">Files</h5>'; ?>
        <?php while (mysqli_stmt_fetch($stmt)) {
        ?>
            <div class="card m-1 shadow-none border">
                <div class="p-2">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-secondary rounded">
                                    <i class="mdi mdi-folder-zip font-16"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col ps-0">
                            <?php echo "<a class='text-muted fw-bold' href='files/$filename' target='_blank'>$filename</a>"; ?>
                            <p class="mb-0 font-13"><?php echo byteConverter($byteSize); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

<?php
    }
}
include_once "inc/head.php";
include_once "inc/navbar.php";

?>
<div class="container">
    <div class="py-5 text-center">
        <h2>Update device</h2>
        <p class="lead">Selected device</p>
    </div>
    <?php get_selectedDevice();
    get_files(); ?>
    <div class="col-md-8 order-md-1">
        <?php include_once "inc/alerts.php"; ?>

        <h4 class="mb-3">Upload file
        </h4>

        <form action="inc/uploadFile.php?id=<?php echo $_GET['id'] ?>" method='POST' enctype="multipart/form-data">
            <input type="file" name="userfile">
            <input type="submit" name='upload'>
        </form>



    </div>
</div>
<?php
include_once "inc/footer.php";
?>