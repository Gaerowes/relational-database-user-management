<?php
// export.php
include 'config.php';

// Memaksa browser mendownload berkas sebagai file Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Mahasiswa.xls");
header("Pragma: no-cache");
header("Expires: 0");

$result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");
?>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>NRP</th>
            <th>Name</th>
            <th>Major</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$no}</td>
                <td>'{$row['nrp']}</td>
                <td>{$row['name']}</td>
                <td>{$row['major']}</td>
            </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>