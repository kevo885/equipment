<?php
include_once "../inc/.env.php";
include_once "../inc/functions.php";
error_reporting(0);

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
?>

<head>
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<div class="container">
    <div class="py-5 text-center">
        <h2>File viewer</h2>
        <p class="lead">Device info</p>

    </div>
    <?php
    if (get_selectedDevice() > 0) {
        get_selectedDevice();

        get_files_API();
    } else {
        echo  '<div class="py-5 text-center">';
        echo  '<h2>File viewer</h2>';
        echo '<p class="lead">Device info</p>';

        echo '</div>';
    }
    ?>
</div>
<?php
include_once "inc/footer.php";
?>