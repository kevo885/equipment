<?php
include_once "inc/.env.php";

function device_type()
{
  global $stmt;
  $sql = "SELECT type from device_type";

  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $device_type);
?>
  <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="device_type">
    <option disabled selected value>select a device</option>

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
    <option disabled selected value>select a manufacturer</option>

    <?php
    while (mysqli_stmt_fetch($stmt)) {
    ?>
      <option value="<?php echo $manufacturer ?>"><?php echo $manufacturer ?></option>
    <?php } ?>

  </select>
<?php
}
?>
<html lang="en">

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body class="bg-light vsc-initialized">
  <div class="container">
    <div class="py-5 text-center">
      <h2>Query database</h2>
      <p class="lead">display records by device type, manufacturer, or serial numbers </p>
    </div>
    <div class="col-md-8 order-md-1">
      <h4 class="mb-3">Select type to query by</h4>
      <form action="handleForm.php" method=POST>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label>Device Type</label>
            <?php device_type(); ?>
          </div>
          <div class="col-md-6 mb-3">
            <label>Manufacturer</label>
            <?php manufacturer(); ?>
          </div>
        </div>

        <div class="mb-3">
          <label>Serial Number</label>
          <div class="input-group">
            <input type="text" class="form-control" name='serial_number' placeholder="Enter a serial number to lookup">
          </div>
        </div>
        <button class="btn btn-primary btn-lg btn-block rounded-pill" type="submit" name='submit'>Submit</button>
      </form>
    </div>
  </div>
  </div>
</body>

</html>