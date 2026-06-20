<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Absensi Wajah</title>
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
        .sidebar a.active { background:#eef2ff; color:#2563eb; }
        .sidebar .logout { margin-top:40px; color:#b91c1c; }
        .sidebar .logout:hover { background:#fee2e2; }
        .main { flex:1; background:#fff; border-radius:20px; padding:28px 32px; border:1px solid #e9edf4; }
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; }
        .header h1 { font-size:22px; font-weight:600; color:#0b1e33; margin:0; }
        .header .sub { font-size:14px; color:#64748b; }
        .badge-date { font-size:13px; color:#64748b; background:#f1f5f9; padding:4px 14px; border-radius:40px; }
        .stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
        .stat-card { background:#f8fafc; padding:18px 20px; border-radius:16px; border:1px solid #e9edf4; }
        .stat-card .number { font-size:28px; font-weight:700; color:#0b1e33; }
        .stat-card .label { font-size:14px; color:#64748b; margin-top:4px; }
        .stat-card .change { font-size:13px; color:#22c55e; margin-top:6px; }
        .row-grid { display:grid; grid-template-columns:2fr 1fr; gap:24px; margin-bottom:28px; }
        .chart-box { background:#f8fafc; border-radius:16px; padding:20px; border:1px solid #e9edf4; }
        .chart-box h6 { font-weight:600; font-size:16px; margin-bottom:16px; color:#0b1e33; }
        .chart-placeholder { height:160px; background:#e9edf4; border-radius:12px; display:flex; align-items:flex-end; padding:10px; gap:8px; }
        .chart-bar { flex:1; background:#2563eb; border-radius:6px 6px 0 0; height:40%; }
        .chart-bar:nth-child(2) { height:70%; }
        .chart-bar:nth-child(3) { height:55%; }
        .chart-bar:nth-child(4) { height:90%; }
        .chart-bar:nth-child(5) { height:65%; }
        .chart-bar:nth-child(6) { height:80%; }
        .chart-bar:nth-child(7) { height:45%; }
        .summary-box { background:#f8fafc; border-radius:16px; padding:20px; border:1px solid #e9edf4; }
        .summary-box h6 { font-weight:600; font-size:16px; margin-bottom:12px; color:#0b1e33; }
        .summary-item { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #e9edf4; font-size:14px; }
        .summary-item:last-child { border-bottom:none; }
        .summary-item .value { font-weight:600; }
        .table-wrap { margin-top:8px; }
        .table-wrap h6 { font-weight:600; font-size:16px; margin-bottom:14px; color:#0b1e33; }
        .table-custom { width:100%; border-collapse:collapse; font-size:14px; }
        .table-custom th { text-align:left; padding:10px 8px; color:#64748b; font-weight:500; border-bottom:1px solid #e9edf4; }
        .table-custom td { padding:10px 8px; border-bottom:1px solid #f1f5f9; }
        .table-custom .status { display:inline-block; padding:2px 12px; border-radius:40px; font-size:12px; font-weight:500; }
        .status.hadir { background:#dcfce7; color:#16a34a; }
        .status.terlambat { background:#fef9c3; color:#ca8a04; }
        .status.alpha { background:#fee2e2; color:#dc2626; }
        @media (max-width:768px) { .app { flex-direction:column; padding:12px; } .sidebar { width:100%; } .stats { grid-template-columns:1fr 1fr; } .row-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">Absensi Wajah</div>
        <a href="?action=admin" class="active">Beranda</a>
        <a href="?action=admin&sub=mahasiswa">Mahasiswa</a>
        <a href="?action=admin&sub=dosen">Dosen</a>
        <a href="?action=admin&sub=matakuliah">Mata Kuliah</a>
        <a href="?action=admin&sub=jadwal">Jadwal</a>
        <a href="?action=admin&sub=registrasi-wajah">Registrasi</a>
        <a href="?action=admin&sub=laporan">Laporan</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>
    <main class="main">
        <div class="header">
            <div>
                <h1>Selamat datang, Admin</h1>
                <div class="sub">Kelola kehadiran perkuliahan dengan mudah</div>
            </div>
            <div class="badge-date"><?= date('l, d M Y') ?></div>
        </div>
        <div class="stats">
            <div class="stat-card">
                <div class="number"><?= $totalMahasiswa ?? 0 ?></div>
                <div class="label">Mahasiswa</div>
                <div class="change">+2 minggu ini</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $totalDosen ?? 0 ?></div>
                <div class="label">Dosen</div>
                <div class="change">+1 baru</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $totalMatakuliah ?? 0 ?></div>
                <div class="label">Mata Kuliah</div>
                <div class="change">aktif semua</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $totalAbsensi ?? 0 ?></div>
                <div class="label">Total Absensi</div>
                <div class="change">+12.8%</div>
            </div>
        </div>
        <div class="row-grid">
            <div class="chart-box">
                <h6>Statistik Kehadiran (7 hari terakhir)</h6>
                <div class="chart-placeholder">
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                    <div class="chart-bar"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:12px;color:#94a3b8;">
                    <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                </div>
            </div>
            <div class="summary-box">
                <h6>Ringkasan Kehadiran</h6>
                <div class="summary-item"><span>Hadir</span><span class="value"><?= $hadir ?? 0 ?></span></div>
                <div class="summary-item"><span>Terlambat</span><span class="value"><?= $terlambat ?? 0 ?></span></div>
                <div class="summary-item"><span>Tidak Hadir</span><span class="value"><?= $alpha ?? 0 ?></span></div>
                <div class="summary-item" style="border-bottom:none;margin-top:8px;font-weight:600;">
                    <span>Total</span>
                    <span class="value"><?= ($hadir ?? 0) + ($terlambat ?? 0) + ($alpha ?? 0) ?></span>
                </div>
            </div>
        </div>
        <div class="table-wrap">
            <h6>Riwayat Absensi Terbaru</h6>
            <table class="table-custom">
                <thead><tr><th>Mahasiswa</th><th>Mata Kuliah</th><th>Tanggal</th><th>Jam</th><th>Status</th></tr></thead>
                <tbody>
                <?php if (isset($riwayat) && count($riwayat) > 0): ?>
                    <?php foreach ($riwayat as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nama'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['nama_mk'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['tanggal'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['jam_absen'] ?? '-') ?></td>
                        <td><span class="status <?= strtolower($r['status'] ?? '') == 'hadir' ? 'hadir' : (strtolower($r['status'] ?? '') == 'terlambat' ? 'terlambat' : 'alpha') ?>"><?= htmlspecialchars($r['status'] ?? '-') ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:20px 0;">Belum ada data absensi.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>