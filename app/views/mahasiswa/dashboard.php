<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa · Absensi Wajah</title>
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
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; }
        .header h1 { font-size:22px; font-weight:600; color:#0b1e33; margin:0; }
        .header .sub { font-size:14px; color:#64748b; }
        .badge-date { font-size:13px; color:#64748b; background:#f1f5f9; padding:4px 14px; border-radius:40px; }
        .schedule-list { background:#f8fafc; border-radius:16px; padding:20px; border:1px solid #e9edf4; margin-bottom:24px; }
        .schedule-list h6 { font-weight:600; font-size:16px; margin-bottom:16px; color:#0b1e33; }
        .schedule-item { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #e9edf4; font-size:14px; }
        .schedule-item:last-child { border-bottom:none; }
        .schedule-item .day { font-weight:500; }
        .schedule-item .time { color:#64748b; }
        .attendance-summary { background:#f8fafc; border-radius:16px; padding:20px; border:1px solid #e9edf4; }
        .attendance-summary h6 { font-weight:600; font-size:16px; margin-bottom:16px; color:#0b1e33; }
        .badge-status { display:inline-block; padding:2px 12px; border-radius:40px; font-size:12px; font-weight:500; }
        .badge-hadir { background:#dcfce7; color:#16a34a; }
        .badge-terlambat { background:#fef9c3; color:#ca8a04; }
        .badge-alpha { background:#fee2e2; color:#dc2626; }
        @media (max-width:768px) { .app { flex-direction:column; padding:12px; } .sidebar { width:100%; } }
    </style>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">Absensi Wajah</div>
        <a href="?action=mahasiswa" class="active">Beranda</a>
        <a href="?action=mahasiswa&sub=absensi">Absensi</a>
        <a href="?action=mahasiswa&sub=riwayat">Riwayat</a>
        <a href="?action=mahasiswa&sub=profil">Profil</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>
    <main class="main">
        <div class="header">
            <div>
                <h1>Halo, Mahasiswa</h1>
                <div class="sub">Jangan lupa absen tepat waktu</div>
            </div>
            <div class="badge-date"><?= date('l, d M Y') ?></div>
        </div>
        <div class="schedule-list">
            <h6>Jadwal Hari Ini</h6>
            <?php if (isset($jadwal) && count($jadwal) > 0): ?>
                <?php foreach ($jadwal as $j): ?>
                <div class="schedule-item">
                    <span class="day"><?= $j['nama_mk'] ?></span>
                    <span class="time"><?= $j['hari'] ?> · <?= $j['jam_mulai'] ?> – <?= $j['jam_selesai'] ?> · Ruang <?= $j['ruang'] ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted" style="color:#94a3b8;">Tidak ada jadwal untuk hari ini. Istirahat dulu 😊</p>
            <?php endif; ?>
        </div>
        <div class="attendance-summary">
            <h6>Riwayat Absensi Terakhir</h6>
            <?php if (isset($absensi) && count($absensi) > 0): ?>
                <?php foreach (array_slice($absensi, 0, 3) as $a): ?>
                <div class="schedule-item">
                    <span><?= $a['nama_mk'] ?></span>
                    <span>
                        <span class="badge-status badge-<?= strtolower($a['status']) == 'hadir' ? 'hadir' : (strtolower($a['status']) == 'terlambat' ? 'terlambat' : 'alpha') ?>"><?= $a['status'] ?></span>
                        <span class="text-muted" style="color:#94a3b8;margin-left:8px;font-size:13px;"><?= $a['tanggal'] ?></span>
                    </span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted" style="color:#94a3b8;">Belum ada riwayat absensi.</p>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>