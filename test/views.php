<?php
include_once ".env.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>File Uploading With PHP and MySql</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
    <div id="header">
        <label>File Uploading With PHP and MySql</label>
    </div>
    <div id="body">
        <table width="80%" border="1">
            <tr>
                <th colspan="4">your uploads...<label><a href="index.php">upload new files...</a></label></th>
            </tr>
            <tr>
                <td>File Name</td>
                <td>File Type</td>
                <td>File Size(KB)</td>
                <td>View</td>
            </tr>
            <?php
            $sql = "SELECT * FROM tbl_uploads";
            mysqli_stmt_init($stmt, $sql);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $filename, $filetype, $size);
            while (mysqli_stmt_fetch($stmt)) {
            ?>
                <tr>
                    <td><?php echo $filename ?></td>
                    <td><?php echo $filetype ?></td>
                    <td><?php echo $size ?></td>
                    <td><a href="uploads/<?php echo $filename ?>" target="_blank">view file</a></td>
                </tr>
            <?php
            }
            ?>
        </table>

    </div>
</body>

</html>