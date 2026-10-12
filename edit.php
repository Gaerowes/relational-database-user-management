
<?php
// edit.php
include 'config.php';
require_once 'auth.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php");
    exit;
}

// Load the student being edited
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM students WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data) {
    die("Student not found.");
}

// Load the majors for the dropdown
$majors = mysqli_query(
    $conn,
    "SELECT id, name FROM majors ORDER BY name"
);

if (isset($_POST['update'])) {
    $nrp = trim($_POST['nrp'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $major_id = filter_input(
        INPUT_POST,
        'major_id',
        FILTER_VALIDATE_INT
    );

    if (
        $nrp === '' ||
        $name === '' ||
        strlen($nrp) > 20 ||
        strlen($name) > 100 ||
        !$major_id ||
        $major_id < 1
    ) {
        die("Please enter valid student data.");
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE students
        SET nrp = ?, name = ?, major_id = ?
        WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssii",
        $nrp,
        $name,
        $major_id,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: index.php");
        exit;
    }

    echo "Failed to update student.";
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Student Data</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="nrp" class="form-label">NRP</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nrp"
                                name="nrp"
                                maxlength="20"
                                value="<?= htmlspecialchars($data['nrp'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="100"
                                value="<?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="major_id" class="form-label">Major</label>
                            <select
                                class="form-select"
                                id="major_id"
                                name="major_id"
                                required
                            >
                                <?php while ($m = mysqli_fetch_assoc($majors)): ?>
                                    <option
                                        value="<?= $m['id'] ?>"
                                        <?= (int)$m['id'] === (int)$data['major_id'] ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($m['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="update" class="btn btn-warning">
                                Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
