<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk · Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f4f8;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 36px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 8px 30px rgba(0,0,0,0.04);
            border: 1px solid #e4e9f0;
        }
        .login-card .brand {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-card .brand img {
            max-width: 72px;
            height: auto;
            display: block;
            margin: 0 auto 10px;
            border-radius: 12px;
        }
        .login-card .brand h1 {
            font-size: 22px;
            font-weight: 600;
            color: #1a2634;
            margin: 0;
            letter-spacing: -0.3px;
        }
        .login-card .brand p {
            font-size: 14px;
            color: #6b7a8e;
            margin: 2px 0 0;
        }
        .login-card .divider {
            height: 1px;
            background: #e4e9f0;
            margin: 14px 0 20px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            display: block;
            margin-bottom: 4px;
        }
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #dce1e8;
            border-radius: 12px;
            font-size: 14px;
            background: #fafcfe;
            transition: 0.2s;
        }
        .form-group input:focus {
            border-color: #1a2634;
            outline: none;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26,38,52,0.05);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #1a2634;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            transition: 0.2s;
            cursor: pointer;
            margin-top: 4px;
        }
        .btn-login:hover {
            background: #0f172a;
        }
        .alert-error {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 10px 14px;
            font-size: 13px;
            color: #991b1b;
            border-radius: 8px;
            margin-bottom: 18px;
        }
        .demo-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 12px;
            color: #475569;
            border: 1px solid #e4e9f0;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 18px;
        }
        .demo-box .label {
            color: #6b7a8e;
        }
        .demo-box .val {
            font-weight: 500;
            color: #1a2634;
            font-family: 'Courier New', monospace;
            background: #fff;
            padding: 0 8px;
            border-radius: 4px;
            border: 1px solid #e4e9f0;
        }
        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 16px;
            font-size: 13px;
        }
        .footer-links a {
            color: #6b7a8e;
            text-decoration: none;
            transition: 0.2s;
        }
        .footer-links a:hover {
            color: #1a2634;
        }
        @media (max-width: 440px) {
            .login-card { padding: 28px 20px; }
            .login-card .brand h1 { font-size: 20px; }
            .demo-box { flex-direction: column; align-items: center; text-align: center; }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand">
            <!-- 
                PATH LOGO:
                - Karena file logo.png ada di public/ 
                - Maka gunakan "logo.png" (tanpa slash) 
                  karena halaman login diakses dari public/
            -->
            <img src="logo.png" alt="Logo" style="display:block; max-width:72px; margin:0 auto 10px;">
            <h1>Absensi</h1>
            <p>Sistem Kehadiran ONEA</p>
        </div>

        <div class="divider"></div>

        <?php if (isset($error)): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="?action=login">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="nama@email.com" required autofocus>
            </div>
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="demo-box">
            <span><span class="label">demo:</span> <span class="val">admin@example.com</span></span>
            <span><span class="label">pass:</span> <span class="val">admin123</span></span>
        </div>

        <div class="footer-links">
            <a href="?action=home">← beranda</a>
            <a href="#">lupa sandi</a>
        </div>
    </div>

</body>
</html>