<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f4f6f9; color:#1e293b; display:flex; justify-content:center; align-items:center; min-height:100vh; padding:20px; }
        .card { background:#fff; border-radius:24px; padding:32px; max-width:500px; width:100%; border:1px solid #e9edf4; }
        .card h1 { font-size:22px; font-weight:600; margin-bottom:24px; }
        .form-group { margin-bottom:16px; }
        .form-group label { font-weight:500; font-size:14px; color:#1e293b; display:block; margin-bottom:4px; }
        .form-group select, .form-group input { width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; }
        .form-group select:focus, .form-group input:focus { border-color:#2563eb; outline:none; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
        .btn-submit { background:#2563eb; border:none; padding:10px; border-radius:40px; color:#fff; font-weight:600; width:100%; }
        .btn-submit:hover { background:#1d4ed8; }
        .btn-back { display:block; text-align:center; margin-top:12px; color:#64748b; text-decoration:none; }
    </style>
</head>
<body>
<div class="card">
    <h1>Edit Jadwal</h1>
    <form method="POST">
        <div class="form-group">
            <label>Mata Kuliah</label>
            <select name="mata_kuliah_id" required>
                <?php if (isset($mkList)): ?>
                    <?php foreach ($mkList as $mk): ?>
                    <option value="<?= $mk['id'] ?>" <?= (isset($data['mata_kuliah_id']) && $data['mata_kuliah_id'] == $mk['id']) ? 'selected' : '' ?>><?= $mk['kode_mk'] ?> - <?= $mk['nama_mk'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Dosen</label>
            <select name="dosen_id" required>
                <?php if (isset($dosenList)): ?>
                    <?php foreach ($dosenList as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= (isset($data['dosen_id']) && $data['dosen_id'] == $d['id']) ? 'selected' : '' ?>><?= $d['nama'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Hari</label>
            <select name="hari" required>
                <option value="Senin" <?= (isset($data['hari']) && $data['hari'] == 'Senin') ? 'selected' : '' ?>>Senin</option>
                <option value="Selasa" <?= (isset($data['hari']) && $data['hari'] == 'Selasa') ? 'selected' : '' ?>>Selasa</option>
                <option value="Rabu" <?= (isset($data['hari']) && $data['hari'] == 'Rabu') ? 'selected' : '' ?>>Rabu</option>
                <option value="Kamis" <?= (isset($data['hari']) && $data['hari'] == 'Kamis') ? 'selected' : '' ?>>Kamis</option>
                <option value="Jumat" <?= (isset($data['hari']) && $data['hari'] == 'Jumat') ? 'selected' : '' ?>>Jumat</option>
                <option value="Sabtu" <?= (isset($data['hari']) && $data['hari'] == 'Sabtu') ? 'selected' : '' ?>>Sabtu</option>
                <option value="Minggu" <?= (isset($data['hari']) && $data['hari'] == 'Minggu') ? 'selected' : '' ?>>Minggu</option>
            </select>
        </div>
        <div class="form-group">
            <label>Jam Mulai</label>
            <input type="time" name="jam_mulai" value="<?= htmlspecialchars($data['jam_mulai'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Jam Selesai</label>
            <input type="time" name="jam_selesai" value="<?= htmlspecialchars($data['jam_selesai'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Ruang</label>
            <input type="text" name="ruang" value="<?= htmlspecialchars($data['ruang'] ?? '') ?>">
        </div>
        <button type="submit" class="btn-submit">Update</button>
    </form>
    <a href="?action=admin&sub=jadwal" class="btn-back">Kembali</a>
</div>
</body>
</html>