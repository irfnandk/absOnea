<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f7fc; }
        .wrapper { display: flex; min-height: 100vh; }
        .sidebar {
            width: 220px;
            background: #ffffff;
            border-right: 1px solid #e9edf4;
            padding: 24px 16px;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar .brand {
            font-weight: 700;
            font-size: 20px;
            color: #0b1e33;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9edf4;
            margin-bottom: 20px;
        }
        .sidebar .brand span { color: #16a34a; }
        .sidebar a {
            display: block;
            padding: 10px 14px;
            border-radius: 10px;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: 0.15s;
            margin-bottom: 2px;
        }
        .sidebar a:hover { background: #f1f5f9; }
        .sidebar a.active {
            background: #16a34a;
            color: #fff;
        }
        .sidebar a.logout { margin-top: 30px; color: #dc2626; }
        .sidebar a.logout:hover { background: #fee2e2; }
        .main { flex: 1; padding: 28px 32px; background: #f4f7fc; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .header h1 { font-size: 24px; font-weight: 700; color: #0b1e33; margin: 0; }
        .card { background: #ffffff; border-radius: 16px; padding: 20px 24px; border: 1px solid #e9edf4; margin-bottom: 20px; }
        .btn-logout-header {
            background: #fee2e2;
            border: none;
            border-radius: 40px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 14px;
            color: #dc2626;
            transition: 0.2s;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-logout-header:hover { background: #fecaca; color: #b91c1c; }
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .wrapper { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <aside class="sidebar">
        <div class="brand">Dosen<span>Panel</span></div>
        <a href="?action=dosen">Beranda</a>
        <a href="?action=dosen&sub=laporan" class="active">Laporan</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>

    <main class="main">
        <div class="header">
            <h1>Laporan Absensi</h1>
            <a href="?action=logout" class="btn-logout-header">Keluar</a>
        </div>

        <div class="card">
            <form method="GET" class="row g-3">
                <input type="hidden" name="action" value="dosen">
                <input type="hidden" name="sub" value="laporan">
                <div class="col-md-4">
                    <label>Mata Kuliah</label>
                    <select name="mata_kuliah_id" class="form-select">
                        <option value="">Semua</option>
                        <?php if (isset($mkList)): foreach ($mkList as $mk): ?>
                        <option value="<?= $mk['id'] ?>" <?= isset($_GET['mata_kuliah_id']) && $_GET['mata_kuliah_id'] == $mk['id'] ? 'selected' : '' ?>><?= htmlspecialchars($mk['nama_mk']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="<?= $_GET['tanggal_awal'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="<?= $_GET['tanggal_akhir'] ?? '' ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>

        <div class="mb-3">
            <a href="?action=dosen&sub=export-excel&<?= http_build_query($_GET) ?>" class="btn btn-success">Export Excel</a>
        </div>

        <div class="card">
            <table class="table table-striped">
                <thead>
                    <tr><th>Tanggal</th><th>Mahasiswa</th><th>Mata Kuliah</th><th>Jam Absen</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php if (isset($data) && count($data) > 0): ?>
                    <?php foreach ($data as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['tanggal']) ?></td>
                        <td><?= htmlspecialchars($d['mahasiswa']) ?></td>
                        <td><?= htmlspecialchars($d['nama_mk']) ?></td>
                        <td><?= htmlspecialchars($d['jam_absen']) ?></td>
                        <td><span class="badge bg-<?= $d['status']=='Hadir'?'success':($d['status']=='Terlambat'?'warning':'danger') ?>"><?= htmlspecialchars($d['status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>