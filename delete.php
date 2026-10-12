
<?php
// delete.php
require_once 'auth.php';
require_once 'config.php';

require_admin();

$id = $_GET['id'] ?? '';

if (filter_var($id, FILTER_VALIDATE_INT) === false || (int)$id < 1) {
    header("Location: index.php");
    exit;
}

$id = (int)$id;

$stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_close($conn);

header("Location: index.php");
exit;
?>
