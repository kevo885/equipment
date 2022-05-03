<?php
include_once "inc/.env.php";
include_once "inc/functions.php";

session_start();

if (!isset($_GET['id'])) {
    $_SESSION['message'] = "ERROR: No device selected";
    $_SESSION['alert'] = "alert alert-danger alert-dismissible fade show";
    header("location: index.php");
    exit();
}

include_once "inc/head.php";
include_once "inc/navbar.php";
?>
<div class="container">
    <div class="py-5 text-center">
        <h2>Update device</h2>
        <p class="lead">Selected device</p>
    </div>
    <?php get_selectedDevice(); ?>
    <div class="col-md-8 order-md-1">
        <?php include_once "inc/alerts.php"; ?>

        <h4 class="mb-3">Update device</h4>
        <form action="inc/updateDevice.php?id=<?php echo $_GET['id'] ?>" method=POST>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>New device Type</label>
                    <?php device_type(); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label>New manufacturer</label>
                    <?php manufacturer(); ?>
                </div>
            </div>

            <div class="mb-3">
                <label>New serial Number</label>
                <div class="input-group">
                    <input type="text" class="form-control" name='newSerialNumber' placeholder="Enter a serial number">
                </div>
            </div>
            <button class="btn btn-primary btn-lg btn-block rounded-pill" type="submit" name='updateDevice'>update</button>
        </form>

    </div>
</div>
<?php
include_once "inc/footer.php";
?>