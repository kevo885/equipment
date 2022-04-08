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
// deletes selected file
if (!empty($_POST['delete'])) {
    $delete =  "DELETE FROM files WHERE id = ?";
    mysqli_stmt_prepare($stmt, $delete);
    mysqli_stmt_bind_param($stmt, "i", $_POST['delete']);
    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));
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
    mysqli_stmt_bind_result($stmt, $id, $device_type, $manufacturer, $serial_number, $disable);
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

    $sql = "SELECT id,file_name , file_size from files where device_id = ?";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fileID, $filename, $byteSize);
    mysqli_stmt_store_result($stmt);
    $target_dir = "/Users/MacBook/Library/Mobile Documents/com~apple~CloudDocs/equipment/files/";


    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo ' <h5 class="mb-2">Files</h5>'; ?>
        <form action="upload.php?id=<?php echo $_GET['id'] ?>" method="post">

            <div class="row mx-n1 g-0">

                <?php while (mysqli_stmt_fetch($stmt)) {
                ?>
                    <div class="col-xxl-3 col-lg-6">
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
                                        <?php echo "<a class='text-muted fw-bold' href='../files/$filename' target='_blank'>$filename</a>"; ?>
                                        <p class="mb-0 font-13"><?php echo byteConverter($byteSize); ?></p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-secondary rounded">
                                                <button class="btn btn-sm d-inline-flex align-items-center btn-rounded" type="submit" name="delete" value="<?php echo $fileID ?>"><i class="dripicons-cross"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </div>
        </form>
<?php
    }
}
include_once "inc/head.php";
?>

<body class="vsc-initialized">
    <div class="container">
        <div class="py-5 text-center">
            <h2>File Manager</h2>
            <p class="lead">Selected device</p>

        </div>
        <?php include_once "inc/alerts.php"; ?>

        <?php get_selectedDevice();
        get_files(); ?>
        <div class="col-md-8 order-md-1">
            <form action="inc/uploadFile.php?id=<?php echo $_GET['id'] ?>" method='POST' enctype="multipart/form-data">
                <div class="p-2">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <input type="file" name='userfile' class="form-control">
                        </div>

                        <div class="col-auto">
                            <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                <i class="dripicons-cross"></i>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-primary btn-sm btn-block rounded" type="submit" name='upload'>Upload</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    include_once "inc/footer.php";
    ?>