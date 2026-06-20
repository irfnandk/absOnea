<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Wajah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 820px;
            width: 100%;
            background: #ffffff;
            border-radius: 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.06);
            padding: 35px 40px;
            transition: 0.3s;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .header h2 {
            font-weight: 600;
            font-size: 26px;
            color: #0b1e33;
            margin: 0;
            letter-spacing: -0.3px;
        }
        .header small {
            color: #6b7a8e;
            font-size: 14px;
        }
        .video-wrapper {
            position: relative;
            background: #0b1e33;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        #video {
            width: 100%;
            display: block;
            background: #000;
            transform: none !important;
            -webkit-transform: none !important;
        }
        #canvas {
            display: none;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 20px;
        }
        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 6px;
            display: block;
        }
        .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 12px 16px;
            background: #f8fafc;
            font-size: 15px;
            width: 100%;
            transition: 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%236b7a8e'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
        }
        .form-select:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
            background-color: #fff;
        }
        .info-box {
            padding: 16px 20px;
            border-radius: 18px;
            background: #f1f5f9;
            color: #1e293b;
            font-size: 15px;
            margin-bottom: 18px;
            min-height: 54px;
            display: flex;
            align-items: center;
            transition: 0.25s;
            border-left: 4px solid #94a3b8;
        }
        .info-box.success { background: #ecfdf5; border-left-color: #16a34a; color: #065f46; }
        .info-box.error { background: #fef2f2; border-left-color: #dc2626; color: #991b1b; }
        .info-box.warning { background: #fffbeb; border-left-color: #f59e0b; color: #92400e; }
        .info-box.info { background: #eff6ff; border-left-color: #2563eb; color: #1e3a8a; }
        .btn-absen {
            background: #2563eb;
            border: none;
            border-radius: 40px;
            padding: 16px;
            font-weight: 600;
            font-size: 18px;
            color: #fff;
            width: 100%;
            transition: 0.2s;
            cursor: pointer;
            letter-spacing: 0.3px;
        }
        .btn-absen:hover:not(:disabled) {
            background: #1d4ed8;
            transform: scale(1.01);
        }
        .btn-absen:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        @media (max-width: 640px) {
            .container { padding: 20px; }
            .form-row { grid-template-columns: 1fr; }
            .header h2 { font-size: 22px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Absensi Wajah</h2>
        <small>deteksi &amp; catat</small>
    </div>

    <div class="video-wrapper">
        <video id="video" autoplay muted></video>
        <canvas id="canvas"></canvas>
    </div>

    <div class="form-row">
        <div>
            <label class="form-label">Mahasiswa</label>
            <select id="mahasiswaSelect" class="form-select">
                <option value="">-- Pilih --</option>
                <?php if (isset($mahasiswaList) && count($mahasiswaList) > 0): ?>
                    <?php foreach ($mahasiswaList as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nim']) ?> – <?= htmlspecialchars($m['nama']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div>
            <label class="form-label">Mata Kuliah (Jadwal)</label>
            <select id="jadwalSelect" class="form-select">
                <option value="">-- Pilih --</option>
                <?php if (isset($semuaJadwal) && count($semuaJadwal) > 0): ?>
                    <?php foreach ($semuaJadwal as $j): ?>
                        <option value="<?= $j['id'] ?>" <?= (isset($jadwalAktif) && $jadwalAktif && $jadwalAktif['id'] == $j['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($j['nama_mk']) ?> (<?= htmlspecialchars($j['hari']) ?> <?= htmlspecialchars($j['jam_mulai']) ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div id="info" class="info-box info">Pilih mahasiswa &amp; jadwal, lalu klik tombol di bawah.</div>
    <button id="absenBtn" class="btn-absen" disabled>Absen Sekarang</button>
</div>

<!-- MediaPipe -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/face_detection.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>

<script>
    (function() {
        // ===== ELEMENTS =====
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const absenBtn = document.getElementById('absenBtn');
        const info = document.getElementById('info');
        const mhsSelect = document.getElementById('mahasiswaSelect');
        const jdSelect = document.getElementById('jadwalSelect');

        let faceDetected = false;
        let faceDetection = null;
        let isProcessing = false;

        // PASTIKAN VIDEO TIDAK MIROR
        video.style.transform = 'none';
        video.style.webkitTransform = 'none';

        // ===== FUNGSI =====
        function setInfo(msg, type) {
            info.textContent = msg;
            info.className = 'info-box ' + type;
        }

        function showSweetAlert(status, message) {
            let icon = 'success';
            let title = 'Absensi Berhasil';
            let color = '#16a34a';
            let timer = 4000;

            if (status === 'Terlambat') {
                icon = 'warning';
                title = 'Terlambat';
                color = '#f59e0b';
            } else if (status === 'Tidak Hadir') {
                icon = 'error';
                title = 'Tidak Hadir (Alpha)';
                color = '#dc2626';
                timer = 5000;
            } else if (status === 'Hadir') {
                icon = 'success';
                title = 'Tepat Waktu';
                color = '#16a34a';
            }

            Swal.fire({
                icon: icon,
                title: title,
                text: message || 'Absensi berhasil dicatat.',
                timer: timer,
                timerProgressBar: true,
                confirmButtonColor: color,
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        }

        function checkSelection() {
            const mhs = mhsSelect.value;
            const jd = jdSelect.value;
            absenBtn.disabled = !(mhs && jd && faceDetected);
        }

        // ===== EVENT =====
        mhsSelect.addEventListener('change', checkSelection);
        jdSelect.addEventListener('change', checkSelection);

        // ===== MEDIAPIPE =====
        function onResults(results) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            // Gambar video ASLI (tidak mirror)
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            if (results.detections && results.detections.length > 0) {
                faceDetected = true;
                setInfo('Wajah terdeteksi. Klik tombol absen.', 'success');
                for (const detection of results.detections) {
                    const box = detection.boundingBox;
                    ctx.strokeStyle = '#22c55e';
                    ctx.lineWidth = 3;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);
                }
                checkSelection();
            } else {
                faceDetected = false;
                absenBtn.disabled = true;
                setInfo('Tidak ada wajah terdeteksi.', 'warning');
            }
        }

        // ===== CAMERA =====
        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
                video.srcObject = stream;
                await video.play();

                faceDetection = new FaceDetection({
                    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/${file}`
                });
                faceDetection.setOptions({ model: 'short', minDetectionConfidence: 0.6 });
                faceDetection.onResults(onResults);

                const camera = new Camera(video, {
                    onFrame: async () => await faceDetection.send({ image: video }),
                    width: 640,
                    height: 480
                });
                await camera.start();
                setInfo('Kamera aktif. Pilih mahasiswa & jadwal, lalu tunjukkan wajah.', 'info');
            } catch (err) {
                console.error(err);
                setInfo('Gagal akses kamera: ' + err.message, 'error');
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Error',
                    text: err.message,
                    confirmButtonColor: '#dc2626'
                });
            }
        }

        // ===== ABSEN =====
        async function doAbsen() {
            if (isProcessing) return;

            const mhsId = mhsSelect.value;
            const jdId = jdSelect.value;

            if (!mhsId || !jdId) {
                setInfo('Pilih mahasiswa dan mata kuliah.', 'warning');
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Pilih mahasiswa dan mata kuliah terlebih dahulu.',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }
            if (!faceDetected) {
                setInfo('Wajah tidak terdeteksi.', 'warning');
                Swal.fire({
                    icon: 'warning',
                    title: 'Wajah Tidak Terdeteksi',
                    text: 'Hadapkan wajah ke kamera.',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            isProcessing = true;
            absenBtn.disabled = true;
            setInfo('Memproses...', 'info');

            try {
                const res = await fetch('?action=api&method=absen', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mahasiswa_id: mhsId, jadwal_id: jdId })
                });
                const result = await res.json();

                if (result.status === 'success') {
                    const status = result.status_kehadiran;
                    let msg = 'Absensi berhasil!';
                    if (status === 'Hadir') {
                        msg = 'Anda hadir tepat waktu.';
                    } else if (status === 'Terlambat') {
                        msg = 'Anda terlambat.';
                    } else if (status === 'Tidak Hadir') {
                        msg = 'Anda dinyatakan Alpha (Tidak Hadir).';
                    }
                    setInfo('✅ ' + msg, 'success');
                    showSweetAlert(status, msg);
                    absenBtn.disabled = true;
                    faceDetected = false;
                } else {
                    setInfo('❌ Gagal: ' + (result.message || 'Terjadi kesalahan'), 'error');
                    Swal.fire({
                        icon: 'error',
                        title: 'Absensi Gagal',
                        text: result.message || 'Coba lagi',
                        confirmButtonColor: '#dc2626'
                    });
                    absenBtn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                setInfo('Error: ' + err.message, 'error');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message,
                    confirmButtonColor: '#dc2626'
                });
                absenBtn.disabled = false;
            }

            isProcessing = false;
        }

        absenBtn.addEventListener('click', doAbsen);

        startCamera();
    })();
</script>
</body>
</html>