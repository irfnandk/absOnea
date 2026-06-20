<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Wajah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f6f8fa; color:#1e293b; display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; }
        .landing { max-width:900px; width:100%; background:#fff; border-radius:24px; padding:50px 40px; border:1px solid #e2e8f0; text-align:center; }
        .landing .brand { font-size:14px; font-weight:500; color:#64748b; letter-spacing:1px; text-transform:uppercase; margin-bottom:12px; }
        .landing h1 { font-size:44px; font-weight:700; line-height:1.2; color:#0b1e33; margin-bottom:12px; }
        .landing h1 span { color:#2563eb; }
        .landing p { font-size:18px; color:#475569; max-width:560px; margin:0 auto 32px; line-height:1.6; }
        .btn-landing { background:#0b1e33; border:none; padding:12px 40px; border-radius:40px; color:#fff; font-weight:600; font-size:16px; text-decoration:none; display:inline-flex; align-items:center; gap:10px; transition:0.2s; }
        .btn-landing:hover { background:#1e293b; color:#fff; }
        .features { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin:40px 0 20px; }
        .features .feat { padding:16px; border-radius:12px; background:#f8fafc; border:1px solid #e9edf2; }
        .features .feat h6 { font-weight:600; font-size:16px; margin-bottom:4px; }
        .features .feat p { font-size:14px; color:#64748b; margin:0; }
        .footer-landing { margin-top:30px; font-size:14px; color:#94a3b8; border-top:1px solid #e9edf2; padding-top:20px; }
        @media (max-width:600px) { .landing { padding:30px 20px; } .landing h1 { font-size:30px; } .features { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="landing">
    <div class="brand">IKIP PGRI BOJONEGORO</div>
    <h1>Absensi <span>Tanpa Ribet</span></h1>
    <p>ONEA - Sistem Absensi Wajah</p>
    <a href="?action=login" class="btn-landing">Masuk ke Sistem</a>
    <div class="features">
        <div class="feat"><h6>Real-time</h6><p>Proses dalam detik</p></div>
        <div class="feat"><h6>Akurat</h6><p>Pengenalan presisi</p></div>
        <div class="feat"><h6>Analitik</h6><p>Statistik lengkap</p></div>
    </div>
    <div class="footer-landing">&copy; <?= date('Y') ?> · Absensi Wajah</div>
</div>
</body>
</html>