<?php 
if (!isset($data)) $data = [];
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container" style="margin-top: 30px;">
    <div class="d-flex justify-content-between">
        <h2>Laporan Absensi</h2>
        <a href="?action=dashboard_dosen" class="btn btn-secondary">Kembali</a>
    </div>
    <hr>
    <form method="GET" class="row g-3 mb-3">
        <input type="hidden" name="action" value="laporan">
        <input type="hidden" name="kode_mk" value="<?= $_GET['kode_mk'] ?? '' ?>">
        <div class="col-auto">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?= $tanggal ?>" class="form-control">
        </div>
        <div class="col-auto" style="margin-top: 18px;">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr><th>NIM</th><th>Nama</th><th>Jam Absen</th><th>Status</th><th>Skor Wajah</th></tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
            <tr><td colspan="5" class="text-center">Belum ada data absensi.</td></tr>
            <?php endif; ?>
            <?php foreach ($data as $row): ?>
            <tr>
                <td><?= $row['nim'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['jam_absen'] ?? '-' ?></td>
                <td>
                    <span class="badge bg-<?= $row['status_kehadiran']=='Hadir'?'success':($row['status_kehadiran']=='Terlambat'?'warning':'danger') ?>">
                        <?= $row['status_kehadiran'] ?>
                    </span>
                </td>
                <td><?= $row['skor_face'] ?? '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="?action=generate_alpha&kode_mk=<?= $_GET['kode_mk'] ?? '' ?>" class="btn btn-warning">Generate Alpha (Akhir Sesi)</a>
    </div>
</body>
</html>