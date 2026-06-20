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
        body { font-family:'Inter',sans-serif; background:#f4f6f9; }
        .container { max-width:600px; margin:50px auto; padding:20px; }
        .card { background:#fff; border-radius:20px; padding:30px; border:1px solid #e9edf4; }
        video { width:100%; border-radius:12px; background:#000; }
        .btn-primary { background:#2563eb; border:none; padding:14px; border-radius:40px; color:#fff; font-weight:600; width:100%; }
        .btn-primary:hover { background:#1d4ed8; }
        #info { margin-top:16px; padding:12px 16px; border-radius:12px; }
        .alert-success { background:#dcfce7; color:#16a34a; border:1px solid #bbf7d0; }
        .alert-danger { background:#fee2e2; color:#dc2626; border:1px solid #fecaca; }
        .alert-warning { background:#fef9c3; color:#ca8a04; border:1px solid #fde68a; }
        .alert-info { background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe; }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Absensi Wajah</h2>
    <div class="card">
        <video id="video" width="100%" autoplay muted></video>
        <button id="absenBtn" class="btn-primary mt-3">Mulai Absensi</button>
        <div id="jadwalInfo" class="alert alert-info mt-3">
            <?php if (isset($jadwalAktif) && $jadwalAktif): ?>
                Mata Kuliah: <strong><?= $jadwalAktif['nama_mk'] ?></strong><br>
                Jam: <?= $jadwalAktif['jam_mulai'] ?> - <?= $jadwalAktif['jam_selesai'] ?>
            <?php else: ?>
                Tidak ada jadwal aktif saat ini.
            <?php endif; ?>
        </div>
        <div id="info" class="alert-warning">Klik tombol untuk memulai absensi</div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tensorflow/2.8.0/tf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/face-api.js/0.22.2/face-api.min.js"></script>

<script>
    (function() {
        const video = document.getElementById('video');
        const absenBtn = document.getElementById('absenBtn');
        const info = document.getElementById('info');

        const MODEL_URL = '/models';

        function updateInfo(message, type) {
            info.textContent = message;
            info.className = 'alert-' + type;
        }

        async function loadModels() {
            try {
                if (typeof faceapi === 'undefined') {
                    throw new Error('face-api.js gagal dimuat.');
                }
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                updateInfo('Model siap. Klik tombol absensi.', 'success');
            } catch (err) {
                console.error(err);
                updateInfo('Gagal load model. Pastikan folder /models ada.', 'danger');
            }
        }

        async function startVideo() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
                video.srcObject = stream;
                await video.play();
            } catch (err) {
                console.error(err);
                updateInfo('Gagal akses kamera. Berikan izin kamera.', 'danger');
            }
        }

        async function doAbsensi() {
            updateInfo('Mendeteksi wajah...', 'warning');

            try {
                // 1. Deteksi wajah dari video
                const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    updateInfo('Wajah tidak terdeteksi. Pastikan wajah terlihat jelas.', 'danger');
                    return;
                }

                const descriptor = Array.from(detection.descriptor);

                // 2. Ambil semua data wajah dari server
                const faceResponse = await fetch('/?action=api&method=face-data');
                const faceData = await faceResponse.json();

                if (faceData.length === 0) {
                    updateInfo('Belum ada data wajah terdaftar. Hubungi admin.', 'danger');
                    return;
                }

                // 3. Cocokkan dengan database
                let bestMatch = null;
                let minDistance = 0.6;

                for (const item of faceData) {
                    const labeledDescriptor = new faceapi.LabeledFaceDescriptors(
                        item.mahasiswa_id,
                        [new Float32Array(item.embedding)]
                    );
                    const faceMatcher = new faceapi.FaceMatcher(labeledDescriptor, 0.6);
                    const match = faceMatcher.findBestMatch(detection.descriptor);
                    if (match.label !== 'unknown') {
                        bestMatch = match;
                        break;
                    }
                }

                if (!bestMatch) {
                    updateInfo('Wajah tidak dikenali. Silakan registrasi ulang.', 'danger');
                    return;
                }

                // 4. Kirim ke server untuk validasi jadwal dan simpan absensi
                const absenRes = await fetch('/?action=api&method=absen', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mahasiswa_id: bestMatch.label })
                });

                const result = await absenRes.json();

                if (result.status === 'success') {
                    updateInfo('✅ Absensi berhasil! Status: ' + result.status_kehadiran, 'success');
                } else {
                    updateInfo('❌ ' + result.message, 'danger');
                }

            } catch (err) {
                console.error(err);
                updateInfo('Error: ' + err.message, 'danger');
            }
        }

        absenBtn.addEventListener('click', doAbsensi);

        loadModels().then(startVideo);
    })();
</script>
</body>
</html>