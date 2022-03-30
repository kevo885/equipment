<?php
include_once "inc/.env.php";

// deletes a single item
if (!empty($_POST['delete'])) {
    $delete =  "DELETE FROM devices WHERE id = ?";
    mysqli_stmt_prepare($stmt, $delete);
    mysqli_stmt_bind_param($stmt, "i", $_POST['delete']);
    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));
}

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


include_once "inc/head.php";
include_once "inc/navbar.php";
?>
<div class="container">
    <div class="py-5 text-center">
        <h2>Query results</h2>
        <p class="lead">display records by device type, manufacturer, or serial numbers </p>
    </div>
    <table class="table">
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
                            <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0 arrow-none" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                                <a href="update.php" class="dropdown-item d-flex align-items-center btn btn-sm d-inline-flex align-items-center btn-rounded">Edit</a>
                                <a class="dropdown-item d-flex align-items-center" href="add.php">Add</a>
                                <button class="dropdown-item d-flex align-items-center" type="submit" name="delete" value="<?php echo $id ?>"><i class="bi bi-trash"></i>
                                </button>
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
</div>

<?php
include_once "inc/footer.php";
