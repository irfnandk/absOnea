<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Wajah - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f0f4f8; }
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
        .sidebar .brand span { color: #2563eb; }
        .sidebar a { display: block; padding: 10px 14px; border-radius: 12px; color: #475569; text-decoration: none; font-weight: 500; font-size: 14px; transition: 0.15s; margin-bottom: 2px; }
        .sidebar a:hover { background: #f1f5f9; }
        .sidebar a.active { background: #2563eb; color: #fff; }
        .sidebar a.logout { margin-top: 30px; color: #dc2626; }
        .sidebar a.logout:hover { background: #fee2e2; }
        .main { flex: 1; padding: 28px 32px; background: #f0f4f8; }
        .main .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .main .header h1 { font-size: 24px; font-weight: 700; color: #0b1e33; margin: 0; }
        .main .header small { color: #6b7a8e; font-size: 14px; }
        .header-actions { display: flex; align-items: center; gap: 12px; }
        .btn-logout-header { background: #fee2e2; border: none; border-radius: 40px; padding: 8px 20px; font-weight: 600; font-size: 14px; color: #dc2626; transition: 0.2s; cursor: pointer; text-decoration: none; }
        .btn-logout-header:hover { background: #fecaca; color: #b91c1c; }
        .card-content { background: #fff; border-radius: 20px; padding: 24px; border: 1px solid #e2e8f0; max-width: 820px; margin: 0 auto; }
        .video-wrapper-reg { position: relative; background: #0b1e33; border-radius: 12px; overflow: hidden; margin-bottom: 16px; }
        #videoReg { width: 100%; display: block; background: #000; transform: none !important; -webkit-transform: none !important; }
        #canvasReg { display: none; }
        .form-row-reg { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
        .form-label { font-weight: 600; font-size: 14px; color: #1e293b; margin-bottom: 4px; display: block; }
        .form-select { border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; background: #f8fafc; font-size: 14px; width: 100%; transition: 0.2s; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%236b7a8e'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; }
        .form-select:focus { border-color: #2563eb; outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.08); background-color: #fff; }
        .info-box-reg { padding: 10px 16px; border-radius: 10px; background: #f1f5f9; color: #1e293b; font-size: 14px; margin-bottom: 12px; min-height: 44px; display: flex; align-items: center; border-left: 4px solid #94a3b8; }
        .info-box-reg.success { background: #ecfdf5; border-left-color: #16a34a; color: #065f46; }
        .info-box-reg.error { background: #fef2f2; border-left-color: #dc2626; color: #991b1b; }
        .info-box-reg.warning { background: #fffbeb; border-left-color: #f59e0b; color: #92400e; }
        .info-box-reg.info { background: #eff6ff; border-left-color: #2563eb; color: #1e3a8a; }
        .btn-group-reg { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 10px; }
        .btn-custom-reg { border: none; border-radius: 30px; padding: 10px; font-weight: 600; font-size: 14px; color: #fff; transition: 0.2s; cursor: pointer; }
        .btn-custom-reg:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-capture-reg { background: #2563eb; }
        .btn-capture-reg:hover:not(:disabled) { background: #1d4ed8; }
        .btn-simpan-reg { background: #16a34a; }
        .btn-simpan-reg:hover:not(:disabled) { background: #15803d; }
        .btn-hapus-reg { background: #dc2626; }
        .btn-hapus-reg:hover:not(:disabled) { background: #b91c1c; }
        .btn-upload-reg { background: #7c3aed; }
        .btn-upload-reg:hover:not(:disabled) { background: #6d28d9; }
        .file-input-wrapper-reg { position: relative; overflow: hidden; display: inline-block; width: 100%; }
        .file-input-wrapper-reg input[type=file] { position: absolute; left: 0; top: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
        .list-terdaftar-reg { background: #f8fafc; border-radius: 10px; padding: 10px 14px; max-height: 100px; overflow-y: auto; font-size: 13px; }
        .badge-terdaftar-reg { background: #dcfce7; color: #16a34a; padding: 4px 12px; border-radius: 30px; margin: 3px; display: inline-block; }
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: relative; } .wrapper { flex-direction: column; } .form-row-reg { grid-template-columns: 1fr; } .btn-group-reg { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>
<div class="wrapper">
    <aside class="sidebar">
        <div class="brand">Admin<span>Panel</span></div>
        <a href="?action=admin">Beranda</a>
        <a href="?action=admin&sub=mahasiswa">Mahasiswa</a>
        <a href="?action=admin&sub=dosen">Dosen</a>
        <a href="?action=admin&sub=matakuliah">Mata Kuliah</a>
        <a href="?action=admin&sub=jadwal">Jadwal</a>
        <a href="?action=admin&sub=registrasi-wajah" class="active">Registrasi Wajah</a>
        <a href="?action=admin&sub=laporan">Laporan</a>
        <a href="?action=logout" class="logout">Keluar</a>
    </aside>

    <main class="main">
        <div class="header">
            <div>
                <h1>Registrasi Wajah</h1>
                <small>Daftarkan wajah mahasiswa</small>
            </div>
            <div class="header-actions">
                <span class="badge bg-light text-dark"><?= date('d M Y, H:i') ?></span>
                <a href="?action=logout" class="btn-logout-header">Keluar</a>
            </div>
        </div>

        <div class="card-content">
            <div class="video-wrapper-reg">
                <video id="videoReg" autoplay muted></video>
                <canvas id="canvasReg"></canvas>
            </div>

            <div class="form-row-reg">
                <div>
                    <label class="form-label">Mahasiswa</label>
                    <select id="mhsSelectReg" class="form-select">
                        <option value="">-- Pilih --</option>
                        <?php if (isset($mahasiswaList) && count($mahasiswaList) > 0): ?>
                            <?php foreach ($mahasiswaList as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nim']) ?> – <?= htmlspecialchars($m['nama']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <div id="statusRegistrasiReg" class="info-box-reg info" style="min-height:40px; margin-bottom:0;">
                        <span id="statusTextReg">Pilih mahasiswa</span>
                    </div>
                </div>
            </div>

            <div id="infoReg" class="info-box-reg info">Pilih mahasiswa, lalu ambil wajah atau upload foto.</div>

            <div class="btn-group-reg">
                <button id="captureBtnReg" class="btn-custom-reg btn-capture-reg" disabled>Ambil</button>
                <button id="saveBtnReg" class="btn-custom-reg btn-simpan-reg" disabled>Simpan</button>
                <button id="deleteBtnReg" class="btn-custom-reg btn-hapus-reg" disabled>Hapus</button>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:12px;">
                <div class="file-input-wrapper-reg">
                    <button class="btn-custom-reg btn-upload-reg" style="width:100%;">Upload Foto</button>
                    <input type="file" id="fileInputReg" accept="image/*">
                </div>
                <button id="refreshBtnReg" class="btn-custom-reg" style="background:#f59e0b;">Refresh</button>
            </div>

            <div>
                <label class="form-label" style="font-size:13px;">Mahasiswa Terdaftar</label>
                <div id="listTerdaftarReg" class="list-terdaftar-reg">
                    <span class="text-muted">Memuat...</span>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/face_detection.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>

<script>
    (function() {
        const video = document.getElementById('videoReg');
        const canvas = document.getElementById('canvasReg');
        const ctx = canvas.getContext('2d');
        const mhsSelect = document.getElementById('mhsSelectReg');
        const captureBtn = document.getElementById('captureBtnReg');
        const saveBtn = document.getElementById('saveBtnReg');
        const deleteBtn = document.getElementById('deleteBtnReg');
        const refreshBtn = document.getElementById('refreshBtnReg');
        const fileInput = document.getElementById('fileInputReg');
        const info = document.getElementById('infoReg');
        const statusText = document.getElementById('statusTextReg');
        const statusReg = document.getElementById('statusRegistrasiReg');
        const listTerdaftar = document.getElementById('listTerdaftarReg');

        let faceDetection = null;
        let currentDescriptor = null;
        let currentMahasiswaId = null;
        let isProcessing = false;
        let cameraInstance = null;

        video.style.transform = 'none';
        video.style.webkitTransform = 'none';

        function setInfo(msg, type) {
            info.textContent = msg;
            info.className = 'info-box-reg ' + type;
        }

        function showToast(title, msg, icon) {
            Swal.fire({
                title: title,
                text: msg,
                icon: icon || 'success',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        }

        async function loadTerdaftar() {
            try {
                const res = await fetch('?action=admin&sub=get-terdaftar');
                const data = await res.json();
                if (data.status === 'success' && data.data && data.data.length > 0) {
                    listTerdaftar.innerHTML = data.data.map(function(item) {
                        return '<span class="badge-terdaftar-reg">' + item.nim + ' – ' + item.nama + '</span>';
                    }).join('');
                } else {
                    listTerdaftar.innerHTML = '<span class="text-muted">Belum ada mahasiswa terdaftar.</span>';
                }
            } catch (err) {
                console.error(err);
                listTerdaftar.innerHTML = '<span class="text-danger">Gagal memuat data.</span>';
            }
        }

        async function cekStatusRegistrasi(mahasiswaId) {
            if (!mahasiswaId) {
                statusText.textContent = 'Pilih mahasiswa';
                statusReg.className = 'info-box-reg info';
                captureBtn.disabled = true;
                saveBtn.disabled = true;
                deleteBtn.disabled = true;
                return;
            }
            try {
                const res = await fetch('?action=admin&sub=cek-wajah&id=' + mahasiswaId);
                const data = await res.json();
                if (data.status === 'success' && data.terdaftar) {
                    statusText.textContent = 'Wajah terdaftar';
                    statusReg.className = 'info-box-reg success';
                    deleteBtn.disabled = false;
                    captureBtn.disabled = false;
                    saveBtn.disabled = true;
                    currentDescriptor = null;
                } else {
                    statusText.textContent = 'Belum terdaftar';
                    statusReg.className = 'info-box-reg error';
                    deleteBtn.disabled = true;
                    captureBtn.disabled = false;
                    saveBtn.disabled = true;
                    currentDescriptor = null;
                }
            } catch (err) {
                console.error(err);
                statusText.textContent = 'Gagal cek status';
                statusReg.className = 'info-box-reg warning';
            }
        }

        mhsSelect.addEventListener('change', function() {
            const id = this.value;
            currentMahasiswaId = id;
            currentDescriptor = null;
            saveBtn.disabled = true;
            captureBtn.disabled = !id;
            if (id) {
                cekStatusRegistrasi(id);
                canvas.style.display = 'none';
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                setInfo('Pilih mahasiswa, lalu ambil wajah.', 'info');
            } else {
                statusText.textContent = 'Pilih mahasiswa';
                statusReg.className = 'info-box-reg info';
                deleteBtn.disabled = true;
                captureBtn.disabled = true;
                saveBtn.disabled = true;
                setInfo('Pilih mahasiswa terlebih dahulu.', 'info');
            }
        });

        function onResultsReg(results) {
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            if (results.detections && results.detections.length > 0) {
                setInfo('Wajah terdeteksi. Klik "Simpan" untuk menyimpan.', 'success');
                for (var i = 0; i < results.detections.length; i++) {
                    var box = results.detections[i].boundingBox;
                    ctx.strokeStyle = '#22c55e';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);
                }
            } else {
                setInfo('Tidak ada wajah terdeteksi.', 'warning');
            }
        }

        async function startCameraReg() {
            try {
                var stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
                video.srcObject = stream;
                await video.play();

                faceDetection = new FaceDetection({
                    locateFile: function(file) {
                        return 'https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/' + file;
                    }
                });
                faceDetection.setOptions({ model: 'short', minDetectionConfidence: 0.6 });
                faceDetection.onResults(onResultsReg);

                cameraInstance = new Camera(video, {
                    onFrame: async function() {
                        await faceDetection.send({ image: video });
                    },
                    width: 640,
                    height: 480,
                    mirror: false
                });
                await cameraInstance.start();
                setInfo('Kamera aktif. Pilih mahasiswa, lalu ambil wajah.', 'info');
            } catch (err) {
                console.error(err);
                setInfo('Gagal akses kamera: ' + err.message, 'error');
                showToast('Kamera Error', err.message, 'error');
            }
        }

        function captureFromVideoReg() {
            if (!currentMahasiswaId) {
                setInfo('Pilih mahasiswa dulu.', 'warning');
                showToast('Peringatan', 'Pilih mahasiswa dulu.', 'warning');
                return;
            }
            if (currentDescriptor !== null) {
                setInfo('Wajah sudah diambil. Klik Simpan.', 'success');
                saveBtn.disabled = false;
                return;
            }
            var tempCanvas = document.createElement('canvas');
            tempCanvas.width = video.videoWidth || 640;
            tempCanvas.height = video.videoHeight || 480;
            var tempCtx = tempCanvas.getContext('2d');
            tempCtx.drawImage(video, 0, 0, tempCanvas.width, tempCanvas.height);
            var imageData = tempCanvas.toDataURL('image/jpeg');
            tempCanvas = null;

            setInfo('Memproses...', 'info');
            captureBtn.disabled = true;

            fetch('?action=admin&sub=detect-wajah', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ image: imageData, mahasiswa_id: currentMahasiswaId })
            })
            .then(function(res) { return res.json(); })
            .then(function(result) {
                captureBtn.disabled = false;
                if (result.status === 'success' && result.detected) {
                    currentDescriptor = result.embedding;
                    saveBtn.disabled = false;
                    setInfo('Wajah berhasil diambil. Klik Simpan.', 'success');
                    showToast('Deteksi Berhasil', 'Wajah terdeteksi.', 'success');
                } else {
                    setInfo('Wajah tidak terdeteksi. Coba lagi.', 'error');
                    showToast('Deteksi Gagal', 'Wajah tidak terdeteksi.', 'error');
                }
            })
            .catch(function(err) {
                console.error(err);
                captureBtn.disabled = false;
                setInfo('Error: ' + err.message, 'error');
                showToast('Error', err.message, 'error');
            });
        }

        captureBtn.addEventListener('click', captureFromVideoReg);

        async function saveFaceReg() {
            if (!currentMahasiswaId || !currentDescriptor) {
                setInfo('Ambil wajah dulu.', 'warning');
                showToast('Peringatan', 'Ambil wajah dulu.', 'warning');
                return;
            }
            saveBtn.disabled = true;
            setInfo('Menyimpan...', 'info');
            try {
                var res = await fetch('?action=admin&sub=simpan-wajah', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        mahasiswa_id: currentMahasiswaId,
                        embedding: currentDescriptor
                    })
                });
                var result = await res.json();
                if (result.status === 'success') {
                    setInfo('Berhasil disimpan!', 'success');
                    showToast('Berhasil', 'Data wajah tersimpan.', 'success');
                    saveBtn.disabled = true;
                    captureBtn.disabled = true;
                    currentDescriptor = null;
                    cekStatusRegistrasi(currentMahasiswaId);
                    loadTerdaftar();
                } else {
                    setInfo('Gagal: ' + (result.message || 'Error'), 'error');
                    showToast('Gagal', result.message || 'Error', 'error');
                    saveBtn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                setInfo('Error: ' + err.message, 'error');
                showToast('Error', err.message, 'error');
                saveBtn.disabled = false;
            }
        }

        saveBtn.addEventListener('click', saveFaceReg);

        async function deleteFaceReg() {
            if (!currentMahasiswaId) return;
            var confirm = await Swal.fire({
                title: 'Hapus Data Wajah?',
                text: 'Data wajah mahasiswa ini akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            });
            if (!confirm.isConfirmed) return;
            try {
                var res = await fetch('?action=admin&sub=hapus-wajah', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mahasiswa_id: currentMahasiswaId })
                });
                var result = await res.json();
                if (result.status === 'success') {
                    setInfo('Data dihapus.', 'warning');
                    showToast('Berhasil', 'Data wajah dihapus.', 'success');
                    currentDescriptor = null;
                    cekStatusRegistrasi(currentMahasiswaId);
                    loadTerdaftar();
                } else {
                    showToast('Gagal', result.message || 'Error', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Error', err.message, 'error');
            }
        }

        deleteBtn.addEventListener('click', deleteFaceReg);

        fileInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            if (!currentMahasiswaId) {
                setInfo('Pilih mahasiswa dulu.', 'warning');
                showToast('Peringatan', 'Pilih mahasiswa dulu.', 'warning');
                fileInput.value = '';
                return;
            }
            var reader = new FileReader();
            reader.onload = function(event) {
                var imageData = event.target.result;
                setInfo('Memproses foto...', 'info');
                captureBtn.disabled = true;
                fetch('?action=admin&sub=detect-wajah', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ image: imageData, mahasiswa_id: currentMahasiswaId })
                })
                .then(function(res) { return res.json(); })
                .then(function(result) {
                    captureBtn.disabled = false;
                    if (result.status === 'success' && result.detected) {
                        currentDescriptor = result.embedding;
                        saveBtn.disabled = false;
                        setInfo('Wajah dari foto berhasil. Klik Simpan.', 'success');
                        showToast('Deteksi Berhasil', 'Wajah terdeteksi.', 'success');
                    } else {
                        setInfo('Wajah tidak terdeteksi di foto.', 'error');
                        showToast('Deteksi Gagal', 'Wajah tidak terdeteksi.', 'error');
                    }
                })
                .catch(function(err) {
                    console.error(err);
                    captureBtn.disabled = false;
                    setInfo('Error: ' + err.message, 'error');
                    showToast('Error', err.message, 'error');
                });
            };
            reader.readAsDataURL(file);
            fileInput.value = '';
        });

        refreshBtn.addEventListener('click', function() {
            loadTerdaftar();
            if (currentMahasiswaId) cekStatusRegistrasi(currentMahasiswaId);
            showToast('Refresh', 'Data diperbarui.', 'info');
        });

        loadTerdaftar();
        startCameraReg();

        if (mhsSelect.value) {
            currentMahasiswaId = mhsSelect.value;
            cekStatusRegistrasi(currentMahasiswaId);
        }
    })();
</script>
</body>
</html>