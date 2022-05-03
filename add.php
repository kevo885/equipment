<?php
include_once "inc/.env.php";
include_once "inc/functions.php";

session_start();

include_once "inc/head.php";
include_once "inc/navbar.php";
?> <div class="container">
    <?php include_once "inc/alerts.php"; ?>
    <div class="py-5 text-center">
        <h2>Add device</h2>
        <p class="lead">Add a new device below</p>
    </div>
    <div class="col-md-8 order-md-1">
        <h4 class="mb-3">Add device</h4>
        <form action="inc/addDevice.php" method=POST>
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
                    <input type="text" class="form-control" name='serial_number' placeholder="Enter a serial number" required>
                </div>
            </div>
            <button class="btn btn-primary btn-lg btn-block rounded-pill" type="submit" name='addDevice'>Add</button>
        </form>

    </div>
</div>
<?php
include_once "inc/footer.php";
?>