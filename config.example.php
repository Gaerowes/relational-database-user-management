<?php
$conn = mysqli_connect(
    "localhost",
    "YOUR_DB_USERNAME",
    "YOUR_DB_PASSWORD",
    "db_school"
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
