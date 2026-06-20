<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mata Kuliah · Admin</title>
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
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; }
        .header h1 { font-size:22px; font-weight:600; color:#0b1e33; margin:0; }
        .btn-primary { background:#2563eb; border:none; border-radius:40px; padding:8px 20px; color:#fff; text-decoration:none; font-weight:500; }
        .btn-primary:hover { background:#1d4ed8; color:#fff; }
        .table-custom { width:100%; border-collapse:collapse; font-size:14px; }
        .table-custom th { text-align:left; padding:12px 8px; color:#64748b; font-weight:500; border-bottom:1px solid #e9edf4; }
        .table-custom td { padding:12px 8px; border-bottom:1px solid #f1f5f9; }
        .btn-edit { background:#f1f5f9; color:#1e293b; padding:4px 14px; border-radius:30px; text-decoration:none; font-size:13px; }
        .btn-edit:hover { background:#e2e8f0; }
        .btn-delete { background:#fee2e2; color:#b91c1c; padding:4px 14px; border-radius:30px; text-decoration:none; font-size:13px; margin-left:6px; }
        .btn-delete:hover { background:#fecaca; }
        @media (max-width:768px) { .app { flex-direction:column; padding:12px; } .sidebar { width:100%; } }
    </style>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">Absensi Wajah</div>
        <a href="?action=admin">Beranda</a>
        <a href="?action=admin&sub=mahasiswa">Mahasiswa</a>
        <a href="?action=admin&sub=dosen">Dosen</a>
        <a href="?action=admin&sub=matakuliah" class="active">Mata Kuliah</a>
        <a href="?action=admin&sub=jadwal">Jadwal</a>
        <a href="?action=admin&sub=registrasi-wajah">Registrasi</a>
        <a href="?action=admin&sub=laporan">Laporan</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>
    <main class="main">
        <div class="header">
            <h1>Data Mata Kuliah</h1>
            <a href="?action=admin&sub=matakuliah-create" class="btn-primary">+ Tambah Mata Kuliah</a>
        </div>
        <table class="table-custom">
            <thead><tr><th>Kode</th><th>Nama</th><th>SKS</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if (isset($data) && count($data) > 0): ?>
                <?php foreach ($data as $mk): ?>
                <tr>
                    <td><?= htmlspecialchars($mk['kode_mk']) ?></td>
                    <td><?= htmlspecialchars($mk['nama_mk']) ?></td>
                    <td><?= htmlspecialchars($mk['sks']) ?></td>
                    <td>
                        <a href="?action=admin&sub=matakuliah-edit&id=<?= $mk['id'] ?>" class="btn-edit">Edit</a>
                        <a href="?action=admin&sub=matakuliah-delete&id=<?= $mk['id'] ?>" class="btn-delete" onclick="return confirm('Hapus mata kuliah ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:20px 0;">Belum ada data mata kuliah.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>