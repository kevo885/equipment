<?php
include_once "inc/.env.php";
include_once "inc/functions.php";
session_start();

if (!isset($_GET['id']) && !empty($_GET['id'])) {
    $_SESSION['message'] = "ERROR: No device selected";
    $_SESSION['alert'] = "alert alert-danger alert-dismissible fade show";
    header("location: index.php");
    exit();
}
// deletes selected file from database and from file
if (!empty($_POST['delete'])) {
    $target_dir = "/Users/MacBook/Library/Mobile Documents/com~apple~CloudDocs/equipment/files/";

    // gets file name to delete
    mysqli_stmt_prepare($stmt, "SELECT file_name from files where id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $_POST['delete']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $filename);

    while (mysqli_stmt_fetch($stmt)) {
        $delete =  "DELETE FROM files WHERE id = ?";
        mysqli_stmt_prepare($stmt, $delete);
        mysqli_stmt_bind_param($stmt, "i", $_POST['delete']);
        if (!mysqli_stmt_execute($stmt))
            exit(mysqli_stmt_error($stmt));

        // upon removing from database, remove file from folder as well
        unlink($target_dir . $filename);
    }
}
include_once "inc/head.php";
include_once "inc/navbar.php";
?>
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