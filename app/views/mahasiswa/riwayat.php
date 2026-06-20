<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Absensi</title>
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
        .sidebar a.active { background:#ede9fe; color:#7c3aed; }
        .sidebar .logout { margin-top:40px; color:#b91c1c; }
        .sidebar .logout:hover { background:#fee2e2; }
        .main { flex:1; background:#fff; border-radius:20px; padding:28px 32px; border:1px solid #e9edf4; }
        .table-custom { width:100%; border-collapse:collapse; font-size:14px; }
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
        <a href="?action=mahasiswa">Beranda</a>
        <a href="?action=mahasiswa&sub=absensi">Absensi</a>
        <a href="?action=mahasiswa&sub=riwayat" class="active">Riwayat</a>
        <a href="?action=mahasiswa&sub=profil">Profil</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>
    <main class="main">
        <h1 style="font-size:22px; font-weight:600; margin-bottom:24px;">Riwayat Absensi</h1>
        <table class="table-custom">
            <thead><tr><th>Tanggal</th><th>Mata Kuliah</th><th>Jam</th><th>Status</th></tr></thead>
            <tbody>
            <?php if (isset($data) && count($data) > 0): ?>
                <?php foreach ($data as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['tanggal']) ?></td>
                    <td><?= htmlspecialchars($a['nama_mk']) ?></td>
                    <td><?= htmlspecialchars($a['jam_absen'] ?? '-') ?></td>
                    <td><span class="status <?= strtolower($a['status']) == 'hadir' ? 'hadir' : (strtolower($a['status']) == 'terlambat' ? 'terlambat' : 'alpha') ?>"><?= htmlspecialchars($a['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:20px 0;">Belum ada riwayat absensi.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>