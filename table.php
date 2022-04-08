<?php
include_once "inc/.env.php";
session_start();

// deletes a single item
if (!empty($_POST['delete'])) {
    $delete =  "DELETE FROM devices WHERE id = ?";
    mysqli_stmt_prepare($stmt, $delete);
    mysqli_stmt_bind_param($stmt, "i", $_POST['delete']);
    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));
}
if (!isset($_POST['submit'])) {
    header("index.php");
    exit();
}
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

//elif search by all three types
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
// execute query and bind query
if (!mysqli_stmt_execute($stmt))
    exit(mysqli_stmt_error($stmt));
mysqli_stmt_bind_result($stmt, $id, $name, $company, $sn);

include_once "inc/head.php";
?>

<body class="vsc-initialized">

    <div class="container">
        <div class="py-5 text-center">
            <h2>Query results</h2>
            <p class="lead">display records by device type, manufacturer, or serial numbers </p>
        </div>
        <?php include_once "inc/alerts.php"; ?>
        <form action="table.php" method="post">
            <table id="alternative-page-datatable" class="table dt-responsive nowrap">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Device Type</td>
                        <td>Manufacturer</td>
                        <td>Serial Number</td>
                        <th>Action</th>
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
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0 arrow-none" data-bs-toggle="dropdown"><i class='dripicons-dots-3'></i></button>
                                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                                        <a href="update.php?id=<?php echo $id ?>" class="dropdown-item d-flex align-items-center btn btn-sm d-inline-flex align-items-center btn-rounded"><i class='mdi mdi-application-cog me-1'></i>Update</a>
                                        <a href="add.php" class="dropdown-item d-flex align-items-center"><i class='mdi mdi-plus me-1'></i>Add new device</a>
                                        <a href="upload.php?id=<?php echo $id ?>" class="dropdown-item d-flex align-items-center"><i class='mdi mdi-folder-open-outline me-1'></i>File manager</a>
                                        <!-- <a class="dropdown-item d-flex align-items-center" href="" data-bs-toggle="modal" data-bs-target="#addUser"><i class='mdi mdi-plus me-1'></i>Add</a> -->
                                    </div>
                                </div>
                                <?php
                                echo "<button class=\"btn btn-sm d-inline-flex align-items-center btn-rounded\" type=\"submit\" name=\"delete\" value=\"$id\"><i class='mdi mdi-delete'></i></button>"
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
        <a class="btn btn-primary btn-lg btn-block rounded-pill" href="index.php">Home</a>

    </div>

    <?php
    include_once "inc/footer.php";
