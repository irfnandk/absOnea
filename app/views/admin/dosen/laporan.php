<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f4f6f9; color:#1e293b; }
        .app { display:flex; min-height:100vh; max-width:1400px; margin:0 auto; padding:20px; gap:24px; }
        .sidebar { width:200px; flex-shrink:0; background:#fff; border-radius:20px; padding:24px 16px; border:1px solid #e9edf4; }
        .sidebar .brand { font-weight:700; font-size:18px; color:#0b1e33; padding-bottom:20px; border-bottom:1px solid #e9edf4; margin-bottom:16px; }
        .sidebar a { display:block; padding:8px 12px; border-radius:8px; color:#475569; text-decoration:none; font-size:14px; font-weight:500; margin-bottom:2px; transition:background 0.15s; }
        .sidebar a:hover { background:#f1f5f9; }
        .sidebar a.active { background:#dcfce7; color:#16a34a; }
        .sidebar .logout { margin-top:40px; color:#b91c1c; }
        .sidebar .logout:hover { background:#fee2e2; }
        .main { flex:1; background:#fff; border-radius:20px; padding:28px 32px; border:1px solid #e9edf4; }
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; }
        .header h1 { font-size:22px; font-weight:600; color:#0b1e33; margin:0; }
        .filter-form { display:flex; flex-wrap:wrap; gap:12px; margin-bottom:20px; align-items:end; }
        .filter-form .form-group { margin-bottom:0; }
        .filter-form label { font-weight:500; font-size:14px; color:#1e293b; display:block; margin-bottom:4px; }
        .filter-form select, .filter-form input { padding:8px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; }
        .filter-form .btn-filter { background:#16a34a; border:none; border-radius:40px; padding:8px 24px; color:#fff; font-weight:500; cursor:pointer; }
        .filter-form .btn-filter:hover { background:#15803d; }
        .btn-export { background:#2563eb; border:none; border-radius:40px; padding:8px 24px; color:#fff; font-weight:500; text-decoration:none; display:inline-block; }
        .btn-export:hover { background:#1d4ed8; color:#fff; }
        .table-custom { width:100%; border-collapse:collapse; font-size:14px; margin-top:16px; }
        .table-custom th { text-align:left; padding:12px 8px; color:#64748b; font-weight:500; border-bottom:1px solid #e9edf4; }
        .table-custom td { padding:12px 8px; border-bottom:1px solid #f1f5f9; }
        .status { display:inline-block; padding:2px 12px; border-radius:40px; font-size:12px; font-weight:500; }
        .status.hadir { background:#dcfce7; color:#16a34a; }
        .status.terlambat { background:#fef9c3; color:#ca8a04; }
        .status.alpha { background:#fee2e2; color:#dc2626; }
        @media (max-width:768px) { .app { flex-direction:column; padding:12px; } .sidebar { width:100%; } }
    </style>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">Absensi Wajah</div>
        <a href="?action=dosen">Beranda</a>
        <a href="?action=dosen&sub=laporan" class="active">Laporan</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>
    <main class="main">
        <div class="header">
            <h1>Laporan Absensi</h1>
            <a href="?action=dosen&sub=export-excel&<?= http_build_query($_GET) ?>" class="btn-export">Export Excel</a>
        </div>

        <form method="GET" class="filter-form">
            <input type="hidden" name="action" value="dosen">
            <input type="hidden" name="sub" value="laporan">
            <div class="form-group">
                <label>Mata Kuliah</label>
                <select name="mata_kuliah_id">
                    <option value="">Semua</option>
                    <?php foreach ($mkList as $mk): ?>
                    <option value="<?= $mk['id'] ?>" <?= (isset($_GET['mata_kuliah_id']) && $_GET['mata_kuliah_id'] == $mk['id']) ? 'selected' : '' ?>><?= $mk['nama_mk'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="<?= $_GET['tanggal_awal'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="<?= $_GET['tanggal_akhir'] ?? '' ?>">
            </div>
            <button type="submit" class="btn-filter">Filter</button>
        </form>

        <table class="table-custom">
            <thead><tr><th>Tanggal</th><th>Mahasiswa</th><th>Mata Kuliah</th><th>Jam</th><th>Status</th></tr></thead>
            <tbody>
            <?php if (isset($data) && count($data) > 0): ?>
                <?php foreach ($data as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['tanggal']) ?></td>
                    <td><?= htmlspecialchars($a['mahasiswa']) ?></td>
                    <td><?= htmlspecialchars($a['nama_mk']) ?></td>
                    <td><?= htmlspecialchars($a['jam_absen'] ?? '-') ?></td>
                    <td><span class="status <?= strtolower($a['status']) == 'hadir' ? 'hadir' : (strtolower($a['status']) == 'terlambat' ? 'terlambat' : 'alpha') ?>"><?= htmlspecialchars($a['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:20px 0;">Tidak ada data absensi.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>