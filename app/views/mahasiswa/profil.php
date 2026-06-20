<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; }
        .wrapper { display: flex; min-height: 100vh; }
        .sidebar {
            width: 220px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            padding: 24px 16px;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar .brand { font-weight: 700; font-size: 20px; color: #0b1e33; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0; margin-bottom: 20px; }
        .sidebar .brand span { color: #7c3aed; }
        .sidebar a { display: block; padding: 10px 14px; border-radius: 12px; color: #475569; text-decoration: none; font-weight: 500; font-size: 14px; transition: 0.15s; margin-bottom: 2px; }
        .sidebar a:hover { background: #f1f5f9; }
        .sidebar a.active { background: #7c3aed; color: #fff; }
        .sidebar a.logout { margin-top: 30px; color: #dc2626; }
        .sidebar a.logout:hover { background: #fee2e2; }
        .main { flex: 1; padding: 28px 32px; background: #f0f4f8; }
        .main .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .main .header h1 { font-size: 24px; font-weight: 700; color: #0b1e33; margin: 0; }
        .main .header small { color: #6b7a8e; font-size: 14px; }
        .card-profil {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            border: 1px solid #e2e8f0;
            max-width: 700px;
            margin: 0 auto;
        }
        .card-profil .foto-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #e2e8f0;
            margin: 0 auto 20px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-profil .foto-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-profil .foto-wrapper .placeholder {
            font-size: 48px;
            color: #94a3b8;
        }
        .form-group { margin-bottom: 16px; }
        .form-group label { font-size: 13px; font-weight: 500; color: #1e293b; display: block; margin-bottom: 4px; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #dce1e8;
            border-radius: 12px;
            font-size: 14px;
            background: #fafcfe;
            transition: 0.2s;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #7c3aed;
            outline: none;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.08);
        }
        .btn-update { background: #7c3aed; border: none; border-radius: 40px; padding: 12px 32px; font-weight: 600; color: #fff; transition: 0.2s; cursor: pointer; }
        .btn-update:hover { background: #6d28d9; }
        .btn-update i { margin-right: 8px; }
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .wrapper { flex-direction: column; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">Mahasiswa<span>Panel</span></div>
        <a href="?action=mahasiswa">Beranda</a>
        <a href="?action=mahasiswa&sub=absensi">Absensi</a>
        <a href="?action=mahasiswa&sub=riwayat">Riwayat</a>
        <a href="?action=mahasiswa&sub=profil" class="active">Profil</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <div class="header">
            <div>
                <h1>Profil Saya</h1>
                <small>Kelola data diri</small>
            </div>
            <span class="badge bg-light text-dark"><?= date('d M Y, H:i') ?></span>
        </div>

        <div class="card-profil">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> Profil berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" action="?action=mahasiswa&sub=profil">
                <!-- FOTO -->
                <div class="foto-wrapper">
                    <?php if (!empty($mhs['foto']) && file_exists(__DIR__ . '/../../public' . $mhs['foto'])): ?>
                        <img src="<?= $mhs['foto'] ?>" alt="Foto Profil">
                    <?php else: ?>
                        <div class="placeholder">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Ubah Foto</label>
                    <input type="file" name="foto" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, GIF. Maks 2MB.</small>
                </div>

                <!-- NAMA -->
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($mhs['nama'] ?? '') ?>" required>
                </div>

                <!-- NIM -->
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" value="<?= htmlspecialchars($mhs['nim'] ?? '') ?>" readonly style="background:#f1f5f9;">
                </div>

                <!-- PRODI -->
                <div class="form-group">
                    <label>Program Studi</label>
                    <input type="text" name="prodi" value="<?= htmlspecialchars($mhs['prodi'] ?? '') ?>">
                </div>

                <!-- ANGKATAN -->
                <div class="form-group">
                    <label>Angkatan</label>
                    <input type="number" name="angkatan" value="<?= htmlspecialchars($mhs['angkatan'] ?? '') ?>">
                </div>

                <!-- TTL -->
                <div class="form-group">
                    <label>Tempat, Tanggal Lahir</label>
                    <input type="text" name="ttl" value="<?= htmlspecialchars($mhs['ttl'] ?? '') ?>" placeholder="Contoh: Jakarta, 10 Mei 2000">
                </div>

                <!-- JABATAN -->
                <div class="form-group">
                    <label>Jabatan / Organisasi</label>
                    <input type="text" name="jabatan" value="<?= htmlspecialchars($mhs['jabatan'] ?? '') ?>" placeholder="Contoh: Ketua BEM">
                </div>

                <button type="submit" class="btn-update">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </main>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>