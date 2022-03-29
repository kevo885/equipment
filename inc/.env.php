<?php
$config = parse_ini_file('/var/www/database/aws-ec2.ini');
//print_r($config);
$host = $config['DB_HOST'];
$username = $config['DB_USERNAME'];
$password = $config['DB_PASSWORD'];
$database_name = $config['DB_DATABASE'];

$conn = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE']);
$stmt = mysqli_stmt_init($conn);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}


if (!$stmt)
    exit("Failed to connect to database");
