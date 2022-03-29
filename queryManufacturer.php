<?php
include_once "inc/.env.php";

function device_type()
{
  global $stmt;
  $sql = "SELECT type from device_type";

  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $type);
?>
  <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="type">
    <?php
    while (mysqli_stmt_fetch($stmt)) {
    ?>
      <option value="<?php echo $type ?>"><?php echo $type ?></option>
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
  <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="type">
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
            <label for="lastName">Last name</label>
            <input type="text" class="form-control" id="lastName" placeholder="" value="" required="">
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="username">Username</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">@</span>
            </div>
            <input type="text" class="form-control" id="username" placeholder="Username" required="">
            <div class="invalid-feedback" style="width: 100%;">
              Your username is required.
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="email">Email <span class="text-muted">(Optional)</span></label>
          <input type="email" class="form-control" id="email" placeholder="you@example.com">
          <div class="invalid-feedback">
            Please enter a valid email address for shipping updates.
          </div>
        </div>

        <div class="mb-3">
          <label for="address">Address</label>
          <input type="text" class="form-control" id="address" placeholder="1234 Main St" required="">
          <div class="invalid-feedback">
            Please enter your shipping address.
          </div>
        </div>

        <div class="mb-3">
          <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
          <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
        </div>

        <div class="row">
          <div class="col-md-5 mb-3">
            <label for="country">Country</label>
            <select class="custom-select d-block w-100" id="country" required="">
              <option value="">Choose...</option>
              <option>United States</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid country.
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="state">State</label>
            <select class="custom-select d-block w-100" id="state" required="">
              <option value="">Choose...</option>
              <option>California</option>
            </select>
            <div class="invalid-feedback">
              Please provide a valid state.
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="zip">Zip</label>
            <input type="text" class="form-control" id="zip" placeholder="" required="">
            <div class="invalid-feedback">
              Zip code required.
            </div>
          </div>
        </div>
        <button class="btn btn-primary btn-lg btn-block rounded-pill" type="submit">Submit</button>
      </form>
    </div>
  </div>
  </div>
</body>

</html>