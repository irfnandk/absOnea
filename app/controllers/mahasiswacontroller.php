<?php
namespace App\controllers;
use App\models\mahasiswamodel;
use App\models\jadwalmodel;
use App\models\absensimodel;

class mahasiswacontroller
{
    private $mahasiswaModel;
    private $jadwalModel;
    private $absensiModel;

    public function __construct()
    {
        $this->mahasiswaModel = new mahasiswamodel();
        $this->jadwalModel = new jadwalmodel();
        $this->absensiModel = new absensimodel();
    }

    public function dashboard()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $mhs = $this->mahasiswaModel->getByUserId($userId);
        $jadwal = $this->jadwalModel->getAll();
        $absensi = $this->absensiModel->getByMahasiswa($mhs['id']);
        include __DIR__ . '/../views/mahasiswa/dashboard.php';
    }

    public function absensi()
    {
        $jadwalAktif = $this->jadwalModel->getAktif();
        include __DIR__ . '/../views/mahasiswa/absensi.php';
    }

    public function riwayat()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $mhs = $this->mahasiswaModel->getByUserId($userId);
        $data = $this->absensiModel->getByMahasiswa($mhs['id']);
        include __DIR__ . '/../views/mahasiswa/riwayat.php';
    }

    public function profil()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $mhs = $this->mahasiswaModel->getByUserId($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama' => $_POST['nama'] ?? '',
                'nim' => $_POST['nim'] ?? '',
                'prodi' => $_POST['prodi'] ?? '',
                'angkatan' => $_POST['angkatan'] ?? '',
                'ttl' => $_POST['ttl'] ?? '',
                'jabatan' => $_POST['jabatan'] ?? ''
            ];

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/mahasiswa/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $filename = 'foto_' . $mhs['id'] . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
                    $data['foto'] = '/uploads/mahasiswa/' . $filename;
                }
            }

            $this->mahasiswaModel->update($mhs['id'], $data);
            header('Location: ?action=mahasiswa&sub=profil&success=1');
            exit;
        }

        include __DIR__ . '/../views/mahasiswa/profil.php';
    }
}