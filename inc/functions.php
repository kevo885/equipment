<?php
include_once ".env.php";

function strip_param_from_url()
{
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    // Finally url is ready
}
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
function get_device_type()
{
    global $stmt;
    $sql = "SELECT type from device_type";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $device_type);

    while (mysqli_stmt_fetch($stmt)) {
        $deviceTypeList[] = $device_type;
    }
    return  $deviceTypeList;
}
function get_manufacturer()
{
    global $stmt;
    $sql = "SELECT manu_name from manufacturers";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $manufacturer);

    while (mysqli_stmt_fetch($stmt)) {
        $manufacturer_list[] = $manufacturer;
    }
    return $manufacturer_list;
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
function get_selectedDevice_API($id, $msg)
{
    global $stmt;
    // get selected device info
    $sql = "SELECT * from devices where id = ?";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $device_type, $manufacturer, $serial_number, $status);
    mysqli_stmt_fetch($stmt);

    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    echo json_encode(array("$msg", "Device type: " . $device_type . ", Manufacturer: $manufacturer, Serial number: $serial_number, status: $status"), JSON_PRETTY_PRINT);
}
function searchDevice()
{
    global $stmt;
    // used to search serial number by certain letters and numbers
    if (!empty($_POST['serial_number'])) {
        $_SESSION['serial_number'] = $_POST['serial_number'];
        $serial_number_query = "%$_SESSION[serial_number]%";
    }
    if (isset($_SESSION['serial_number']))
        $serial_number_query = "%$_SESSION[serial_number]%";

    if (isset($_POST['device_type']) && !empty($_POST['device_type']))
        $_SESSION['device_type'] = $_POST['device_type'];

    if (isset($_POST['manufacturer']) && !empty($_POST['manufacturer']))
        $_SESSION['manufacturer'] = $_POST['manufacturer'];

    //search by all three types
    if (isset($_SESSION['device_type']) && isset($_SESSION['manufacturer']) && !empty($_SESSION['serial_number'])) {
        $sql = "SELECT * from devices where manufacturer = ? and device_type = ? and serial_number like ? LIMIT 500";

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $_SESSION['manufacturer'], $_SESSION['device_type'], $serial_number_query);
    }
    // query by device type and manufacturer 
    else if (isset($_SESSION['device_type']) && isset($_SESSION['manufacturer'])) {
        $sql = "SELECT * from devices where manufacturer = ? and device_type = ?  LIMIT 500";

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $_SESSION['manufacturer'], $_SESSION['device_type']);
    }
    // query by device type and serial number
    else if (isset($_SESSION['device_type']) && !empty($_SESSION['serial_number'])) {
        $sql = "SELECT * from devices where serial_number like ? and device_type = ?  LIMIT 500";

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $serial_number_query, $_SESSION['device_type']);
    }
    // query by manufacturer and serial number
    else if (isset($_SESSION['manufacturer']) && !empty($_SESSION['serial_number'])) {
        $sql = "SELECT * from devices where serial_number like ? and manufacturer = ?  LIMIT 500";

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $serial_number_query, $_SESSION['manufacturer']);
    }
    // query by only device type
    else if (isset($_SESSION['device_type'])) {
        $sql = "SELECT * from devices where device_type = ?  LIMIT 500";
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['device_type']);
    }
    //query by only manufacturer 
    else if (isset($_SESSION['manufacturer'])) {
        $sql = "SELECT * from devices where manufacturer = ?  LIMIT 500";

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['manufacturer']);
    }
    // query by only serial number
    else if (!empty($_SESSION['serial_number'])) {
        $sql = "SELECT * from devices where serial_number like  ?  LIMIT 500";

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $serial_number_query);
    }
    // if no type is selected dislay the first 500 devices 
    else {
        $sql = "SELECT * from devices LIMIT 500";
        mysqli_stmt_prepare($stmt, $sql);
    }
    // execute query and bind query
    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));
    mysqli_stmt_bind_result($stmt, $id, $name, $company, $sn, $status);
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
function get_files_API()
{
    global $stmt;

    $sql = "SELECT id,file_name , file_size from files where device_id = ?";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $_REQUEST['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fileID, $filename, $byteSize);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo ' <h5 class="mb-2">Files</h5>'; ?>
        <form action="?ViewFile&id=<?php echo $_REQUEST['id'] ?>" method="POST">

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

                                        <?php echo "<a class='text-muted fw-bold' href='../../files/$filename' target='_blank'>$filename</a>"; ?>
                                        <p class="mb-0 font-13"><?php echo byteConverter($byteSize); ?></p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-light text-secondary rounded">
                                                <button class="btn btn-sm d-inline-flex align-items-center btn-rounded" type="submit" name="delete" value="<?php echo $fileID ?>"><i>x</i>
                                                </button>
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
    } else {
        // if device dosen't exist

        echo '<div class="py-5 text-center">';
        echo '<h2>File Manager</h2>';
        echo ' <p class="lead">Selected device</p>';

        echo '</div> ';
        die();
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
function serial_number_exists($serial_number)
{
    global $stmt;
    $check_serial_number = "SELECT * FROM devices WHERE serial_number=?";

    // preparing statement
    mysqli_stmt_prepare($stmt, $check_serial_number);
    mysqli_stmt_bind_param($stmt, 's', $serial_number);

    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));

    mysqli_stmt_store_result($stmt);

    // if serial_number is taken, print error 
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
function check_device_and_manufacturer()
{
    $capitalizeManufacturer = array_map('strtoupper', get_manufacturer());

    if (!in_array($_REQUEST['device_type'], get_device_type()) || !in_array(strtoupper($_REQUEST['manufacturer']), $capitalizeManufacturer)) {

        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');

        if ($_REQUEST['device_type'] != NULL && !in_array($_REQUEST['device_type'], get_device_type()))
            echo json_encode(array("Status: ERROR - $_REQUEST[device_type] is not a valid device type", "Available device types", get_device_type()), JSON_PRETTY_PRINT);

        if ($_REQUEST['manufacturer'] != NULL && !in_array(strtoupper($_REQUEST['manufacturer']), $capitalizeManufacturer))
            echo json_encode(array("Status: ERROR - $_REQUEST[manufacturer] is not a valid manufacturer", "Available manufacturers", get_manufacturer()), JSON_PRETTY_PRINT);

        die();
    }
}
