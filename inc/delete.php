<?php
include_once ".env.php";
// deletes a single item
if (!empty($_POST['delete'])) {
    $delete =  "DELETE FROM devices WHERE id = ?";
    mysqli_stmt_prepare($stmt, $delete);
    mysqli_stmt_bind_param($stmt, "i", $_POST['delete']);
    if (!mysqli_stmt_execute($stmt))
        exit(mysqli_stmt_error($stmt));

    header("Location: ../table.php");
}
