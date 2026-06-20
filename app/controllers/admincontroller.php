<?php
// ============================================================
// FILE: app/controllers/admincontroller.php (EXPORT EXCEL FIX)
// ============================================================
namespace App\controllers;

use App\models\MahasiswaModel;
use App\models\DosenModel;
use App\models\MatakuliahModel;
use App\models\JadwalModel;
use App\models\AbsensiModel;
use App\models\DataWajahModel;
use App\models\UserModel;

// Pastikan PhpSpreadsheet di-load
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AdminController
{
    private $mahasiswaModel;
    private $dosenModel;
    private $matakuliahModel;
    private $jadwalModel;
    private $absensiModel;
    private $dataWajahModel;
    private $userModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->dosenModel = new DosenModel();
        $this->matakuliahModel = new MatakuliahModel();
        $this->jadwalModel = new JadwalModel();
        $this->absensiModel = new AbsensiModel();
        $this->dataWajahModel = new DataWajahModel();
        $this->userModel = new UserModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function dashboard()
    {
        $totalMahasiswa = $this->mahasiswaModel->count();
        $totalDosen = $this->dosenModel->count();
        $totalMatakuliah = $this->matakuliahModel->count();
        $totalAbsensi = $this->absensiModel->count();
        $hadir = $this->absensiModel->countByStatus('Hadir');
        $terlambat = $this->absensiModel->countByStatus('Terlambat');
        $alpha = $this->absensiModel->countByStatus('Tidak Hadir');
        $riwayat = $this->absensiModel->getRecent(10);
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    public function mahasiswaIndex()
    {
        $data = $this->mahasiswaModel->getAll();
        include __DIR__ . '/../views/admin/mahasiswa/index.php';
    }

    public function mahasiswaCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->mahasiswaModel->create($_POST);
            header('Location: ?action=admin&sub=mahasiswa');
            exit;
        }
        include __DIR__ . '/../views/admin/mahasiswa/create.php';
    }

    public function mahasiswaEdit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->mahasiswaModel->update($id, $_POST);
            header('Location: ?action=admin&sub=mahasiswa');
            exit;
        }
        $data = $this->mahasiswaModel->getById($id);
        include __DIR__ . '/../views/admin/mahasiswa/edit.php';
    }

    public function mahasiswaDelete($id)
    {
        $this->mahasiswaModel->delete($id);
        header('Location: ?action=admin&sub=mahasiswa');
    }

    public function dosenIndex()
    {
        $data = $this->dosenModel->getAll();
        include __DIR__ . '/../views/admin/dosen/index.php';
    }

    public function dosenCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->dosenModel->create($_POST);
            header('Location: ?action=admin&sub=dosen');
            exit;
        }
        include __DIR__ . '/../views/admin/dosen/create.php';
    }

    public function dosenEdit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->dosenModel->update($id, $_POST);
            header('Location: ?action=admin&sub=dosen');
            exit;
        }
        $data = $this->dosenModel->getById($id);
        include __DIR__ . '/../views/admin/dosen/edit.php';
    }

    public function dosenDelete($id)
    {
        $this->dosenModel->delete($id);
        header('Location: ?action=admin&sub=dosen');
    }

    public function matakuliahIndex()
    {
        $data = $this->matakuliahModel->getAll();
        include __DIR__ . '/../views/admin/matakuliah/index.php';
    }

    public function matakuliahCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'kode_mk' => $_POST['kode'] ?? '',
                'nama_mk' => $_POST['nama'] ?? '',
                'sks' => $_POST['sks'] ?? 0
            ];
            $this->matakuliahModel->create($data);
            header('Location: ?action=admin&sub=matakuliah');
            exit;
        }
        include __DIR__ . '/../views/admin/matakuliah/create.php';
    }

    public function matakuliahEdit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'kode_mk' => $_POST['kode'] ?? '',
                'nama_mk' => $_POST['nama'] ?? '',
                'sks' => $_POST['sks'] ?? 0
            ];
            $this->matakuliahModel->update($id, $data);
            header('Location: ?action=admin&sub=matakuliah');
            exit;
        }
        $data = $this->matakuliahModel->getById($id);
        include __DIR__ . '/../views/admin/matakuliah/edit.php';
    }

    public function matakuliahDelete($id)
    {
        $this->matakuliahModel->delete($id);
        header('Location: ?action=admin&sub=matakuliah');
    }

    public function jadwalIndex()
    {
        $data = $this->jadwalModel->getAll();
        $dosenList = $this->dosenModel->getAll();
        $mkList = $this->matakuliahModel->getAll();
        include __DIR__ . '/../views/admin/jadwal/index.php';
    }

    public function jadwalCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->jadwalModel->create($_POST);
            header('Location: ?action=admin&sub=jadwal');
            exit;
        }
        $dosenList = $this->dosenModel->getAll();
        $mkList = $this->matakuliahModel->getAll();
        include __DIR__ . '/../views/admin/jadwal/create.php';
    }

    public function jadwalEdit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->jadwalModel->update($id, $_POST);
            header('Location: ?action=admin&sub=jadwal');
            exit;
        }
        $data = $this->jadwalModel->getById($id);
        $dosenList = $this->dosenModel->getAll();
        $mkList = $this->matakuliahModel->getAll();
        include __DIR__ . '/../views/admin/jadwal/edit.php';
    }

    public function jadwalDelete($id)
    {
        $this->jadwalModel->delete($id);
        header('Location: ?action=admin&sub=jadwal');
    }

    public function registrasiWajah()
    {
        $mahasiswaList = $this->mahasiswaModel->getAll();
        $dataWajah = $this->dataWajahModel->getAll();
        include __DIR__ . '/../views/admin/registrasi-wajah.php';
    }

    public function simpanWajah()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $mahasiswaId = $input['mahasiswa_id'] ?? null;
        $embedding = $input['embedding'] ?? [];

        if ($mahasiswaId && count($embedding) > 0) {
            try {
                $this->dataWajahModel->create($mahasiswaId, $embedding);
                echo json_encode(['status' => 'success']);
            } catch (\Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
        }
    }

    public function cekWajah()
    {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
            return;
        }
        $data = $this->dataWajahModel->getByMahasiswa($id);
        echo json_encode([
            'status' => 'success',
            'terdaftar' => $data ? true : false,
            'data' => $data
        ]);
    }

    public function getTerdaftar()
    {
        header('Content-Type: application/json');
        $data = $this->dataWajahModel->getAll();
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function hapusWajah()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $mahasiswaId = $input['mahasiswa_id'] ?? null;
        if (!$mahasiswaId) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
            return;
        }
        try {
            $this->dataWajahModel->delete($mahasiswaId);
            echo json_encode(['status' => 'success']);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function detectWajah()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $imageData = $input['image'] ?? null;
        $mahasiswaId = $input['mahasiswa_id'] ?? null;

        if (!$imageData) {
            echo json_encode(['status' => 'error', 'message' => 'Gambar tidak ditemukan']);
            return;
        }

        $dummyEmbedding = [];
        for ($i = 0; $i < 128; $i++) {
            $dummyEmbedding[] = (float)(rand(-1, 1) + rand(0, 100) / 100);
        }

        echo json_encode([
            'status' => 'success',
            'detected' => true,
            'embedding' => $dummyEmbedding
        ]);
    }

    public function laporan()
    {
        $filter = [];
        if ($_GET) {
            if (!empty($_GET['mata_kuliah_id'])) $filter['mata_kuliah_id'] = $_GET['mata_kuliah_id'];
            if (!empty($_GET['dosen_id'])) $filter['dosen_id'] = $_GET['dosen_id'];
            if (!empty($_GET['mahasiswa_id'])) $filter['mahasiswa_id'] = $_GET['mahasiswa_id'];
            if (!empty($_GET['tanggal_awal'])) $filter['tanggal_awal'] = $_GET['tanggal_awal'];
            if (!empty($_GET['tanggal_akhir'])) $filter['tanggal_akhir'] = $_GET['tanggal_akhir'];
        }
        $data = $this->absensiModel->getLaporan($filter);
        $mkList = $this->matakuliahModel->getAll();
        $dosenList = $this->dosenModel->getAll();
        $mhsList = $this->mahasiswaModel->getAll();
        include __DIR__ . '/../views/admin/laporan/index.php';
    }

    public function exportExcel()
    {
        // Ambil filter dari GET
        $filter = [];
        if ($_GET) {
            if (!empty($_GET['mata_kuliah_id'])) $filter['mata_kuliah_id'] = $_GET['mata_kuliah_id'];
            if (!empty($_GET['dosen_id'])) $filter['dosen_id'] = $_GET['dosen_id'];
            if (!empty($_GET['mahasiswa_id'])) $filter['mahasiswa_id'] = $_GET['mahasiswa_id'];
            if (!empty($_GET['tanggal_awal'])) $filter['tanggal_awal'] = $_GET['tanggal_awal'];
            if (!empty($_GET['tanggal_akhir'])) $filter['tanggal_akhir'] = $_GET['tanggal_akhir'];
        }

        // Ambil data laporan
        $data = $this->absensiModel->getLaporan($filter);
        $namaMK = '';
        if (!empty($filter['mata_kuliah_id'])) {
            $mk = $this->matakuliahModel->getById($filter['mata_kuliah_id']);
            $namaMK = $mk ? '_' . str_replace(' ', '_', $mk['nama_mk']) : '';
        }

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header judul
        $sheet->setCellValue('A1', 'LAPORAN ABSENSI MAHASISWA');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Sub judul (tanggal cetak)
        $sheet->setCellValue('A2', 'Dicetak: ' . date('d-m-Y H:i:s'));
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A2')->getFont()->setSize(10);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $headers = ['No', 'Tanggal', 'Mahasiswa', 'Mata Kuliah', 'Status'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $sheet->getStyle($col . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9E1F2');
            $col++;
        }

        // Data
        $row = 5;
        $no = 1;
        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $d['tanggal']);
            $sheet->setCellValue('C' . $row, $d['mahasiswa']);
            $sheet->setCellValue('D' . $row, $d['nama_mk']);
            $sheet->setCellValue('E' . $row, $d['status']);
            $row++;
        }

        // Auto size kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A4:E' . ($row - 1))->applyFromArray($styleArray);

        // Set response header untuk download
        $filename = 'Absensi' . $namaMK . '_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}