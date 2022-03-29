<html lang="en">
<?php
include_once "inc/.env.php";
$sql = "SELECT type from device_type";

mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $type);
?>


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <div>
        <p>Select a device type</p>
        <form action="getDeviceType.php" method=POST>
            <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="type">
                <?php while (mysqli_stmt_fetch($stmt)) {
                ?>
                    <option value="<?php echo $type ?>"><?php echo $type ?></option>
                <?php } ?>

            </select>
            <button class="rounded" type="submit" name="select_device_type">Submit</button>
        </form>
    </div>
</body>

</html>