<?php
include_once ".env.php";
function device_type()
{
    global $stmt;
    $sql = "SELECT type from device_type";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $device_type);
?>
    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="device_type">
        <option disabled selected value value=''>select a device</option>

        <?php
        while (mysqli_stmt_fetch($stmt)) {
        ?>
            <option value="<?php echo $device_type ?>"><?php echo $device_type ?></option>
        <?php } ?>

    </select>
<?php
}
function manufacturer()
{
    global $stmt;
    $sql = "SELECT manu_name from manufacturers";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $manufacturer);
?>
    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="manufacturer">
        <option disabled selected value value=''>select a manufacturer</option>

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
function get_files()
{
    global $stmt;

    $sql = "SELECT id,file_name , file_size from files where device_id = ?";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fileID, $filename, $byteSize);
    mysqli_stmt_store_result($stmt);

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
                                        <?php echo "<a class='text-muted fw-bold' href='files/$filename' target='_blank'>$filename</a>"; ?>
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
function device_exists($id)
{
    global $stmt;
    $sql = "Select id from `devices` where `id`= ?";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    // execute query and bind query
    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));

    mysqli_stmt_bind_result($stmt, $id);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0)
        return true;
    else
        return false;
}
function byteConverter($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } else if ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } else if ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } else if ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } else if ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
