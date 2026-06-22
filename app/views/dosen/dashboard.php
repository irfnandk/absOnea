<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fc;
            color: #1e293b;
        }
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
        .sidebar a.logout {
            margin-top: 30px;
            color: #dc2626;
        }
        .sidebar a.logout:hover { background: #fee2e2; }
        .main {
            flex: 1;
            padding: 28px 32px;
            background: #f4f7fc;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0b1e33;
            margin: 0;
        }
        .header small { color: #6b7a8e; font-size: 14px; }
        .welcome-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #e9edf4;
            margin-bottom: 28px;
        }
        .welcome-box h2 {
            font-size: 22px;
            font-weight: 600;
            color: #0b1e33;
            margin: 0;
        }
        .welcome-box p {
            color: #64748b;
            margin: 4px 0 0;
            font-size: 14px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 22px;
            border: 1px solid #e9edf4;
        }
        .stat-card .number {
            font-size: 28px;
            font-weight: 600;
            color: #0b1e33;
        }
        .stat-card .label {
            font-size: 14px;
            color: #64748b;
        }
        .row-dua {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #e9edf4;
        }
        .card h5 {
            font-weight: 600;
            font-size: 16px;
            color: #0b1e33;
            margin-bottom: 16px;
        }
        .chart-container {
            position: relative;
            height: 200px;
        }
        .schedule-list {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #e9edf4;
        }
        .schedule-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .schedule-item:last-child { border-bottom: none; }
        .schedule-item .mk { font-weight: 500; }
        .schedule-item .info { color: #64748b; }
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
            .stats-grid { grid-template-columns: 1fr; }
            .row-dua { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <aside class="sidebar">
        <div class="brand">Dosen<span>Panel</span></div>
        <a href="?action=dosen" class="active">Beranda</a>
        <a href="?action=dosen&sub=laporan">Laporan</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>

    <main class="main">
        <div class="header">
            <h1>Dashboard</h1>
            <a href="?action=logout" class="btn-logout-header">Keluar</a>
        </div>

        <div class="welcome-box">
            <h2>Selamat datang, <?= htmlspecialchars($namaDosen ?? 'Dosen') ?></h2>
            <p>Berikut jadwal mengajar Anda hari ini.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="number"><?= count($jadwal ?? []) ?></div>
                <div class="label">Jadwal Mengajar</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $totalMahasiswa ?? 0 ?></div>
                <div class="label">Total Mahasiswa</div>
            </div>
        </div>

        <div class="row-dua">
            <div class="card">
                <h5>Grafik Kehadiran</h5>
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
            <div class="card">
                <h5>Ringkasan</h5>
                <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f1f5f9; font-size:14px;">
                    <span>Hadir</span>
                    <span style="font-weight:600; color:#16a34a;"><?= $hadir ?? 0 ?></span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f1f5f9; font-size:14px;">
                    <span>Terlambat</span>
                    <span style="font-weight:600; color:#ca8a04;"><?= $terlambat ?? 0 ?></span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:8px 0; font-size:14px;">
                    <span>Tidak Hadir</span>
                    <span style="font-weight:600; color:#dc2626;"><?= $alpha ?? 0 ?></span>
                </div>
            </div>
        </div>

        <div class="schedule-list">
            <h5>Jadwal Mengajar</h5>
            <?php if (isset($jadwal) && count($jadwal) > 0): ?>
                <?php foreach ($jadwal as $j): ?>
                <div class="schedule-item">
                    <span class="mk"><?= htmlspecialchars($j['nama_mk']) ?></span>
                    <span class="info"><?= htmlspecialchars($j['hari']) ?> · <?= htmlspecialchars($j['jam_mulai']) ?> – <?= htmlspecialchars($j['jam_selesai']) ?> · Ruang <?= htmlspecialchars($j['ruang']) ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Tidak ada jadwal.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const labels = ['Hadir', 'Terlambat', 'Alpha'];
        const values = [
            <?= $hadir ?? 0 ?>,
            <?= $terlambat ?? 0 ?>,
            <?= $alpha ?? 0 ?>
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: values,
                    backgroundColor: ['#22c55e', '#eab308', '#ef4444'],
                    borderColor: ['#16a34a', '#ca8a04', '#dc2626'],
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>

</body>
</html>