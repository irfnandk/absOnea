<?php
namespace App\controllers;
use App\models\jadwalmodel;
use App\models\absensimodel;
use App\models\mahasiswamodel;

class apicontroller
{
    private $jadwalModel;
    private $absensiModel;
    private $mahasiswaModel;

    public function __construct()
    {
        $this->jadwalModel = new jadwalmodel();
        $this->absensiModel = new absensimodel();
        $this->mahasiswaModel = new mahasiswamodel();
        // Set timezone ke WIB
        date_default_timezone_set('Asia/Jakarta');
    }

    public function doAbsensi()
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $mahasiswaId = $input['mahasiswa_id'] ?? null;
        $jadwalId = $input['jadwal_id'] ?? null;

        if (!$mahasiswaId && isset($_SESSION['user_id'])) {
            $mhs = $this->mahasiswaModel->getByUserId($_SESSION['user_id']);
            if ($mhs) {
                $mahasiswaId = $mhs['id'];
            }
        }

        if (!$mahasiswaId) {
            echo json_encode(['status' => 'error', 'message' => 'Pilih mahasiswa terlebih dahulu.']);
            return;
        }

        if (!$jadwalId) {
            $jadwal = $this->jadwalModel->getAktif();
            if ($jadwal) {
                $jadwalId = $jadwal['id'];
            }
        }

        if (!$jadwalId) {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada jadwal aktif atau pilih jadwal.']);
            return;
        }

        $today = date('Y-m-d');
        $cek = $this->absensiModel->getByJadwalAndTanggal($jadwalId, $today);
        foreach ($cek as $c) {
            if ($c['mahasiswa_id'] === $mahasiswaId) {
                echo json_encode(['status' => 'error', 'message' => 'Mahasiswa sudah absen hari ini.']);
                return;
            }
        }

        $jadwal = $this->jadwalModel->getById($jadwalId);
        if (!$jadwal) {
            echo json_encode(['status' => 'error', 'message' => 'Jadwal tidak ditemukan.']);
            return;
        }

        $now = new \DateTime();
        $jamMulai = new \DateTime($jadwal['jam_mulai']);
        $jamSelesai = new \DateTime($jadwal['jam_selesai']);
        $selisihMulai = ($now->getTimestamp() - $jamMulai->getTimestamp()) / 60;

        // Logika status
        if ($now <= $jamMulai) {
            $status = 'Hadir';
        } elseif ($now > $jamMulai && $now <= $jamSelesai) {
            $status = ($selisihMulai <= 15) ? 'Hadir' : 'Terlambat';
        } else {
            $status = 'Tidak Hadir';
        }

        $data = [
            'mahasiswa_id' => $mahasiswaId,
            'jadwal_id' => $jadwalId,
            'tanggal' => $today,
            'jam_absen' => $now->format('H:i:s'),
            'status' => $status
        ];

        try {
            $this->absensiModel->create($data);
            echo json_encode([
                'status' => 'success',
                'message' => 'Absensi berhasil',
                'status_kehadiran' => $status,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function getStatistik()
    {
        header('Content-Type: application/json');
        $data = $this->absensiModel->getStatistik();
        $labels = array_column($data, 'status');
        $values = array_column($data, 'total');
        echo json_encode(['labels' => $labels, 'values' => $values]);
    }
}