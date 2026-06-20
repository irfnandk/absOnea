<?php
namespace App\controllers;
use App\models\JadwalModel;
use App\models\MahasiswaModel;
use App\models\AbsensiModel;
use App\models\MatakuliahModel;
use App\models\DosenModel;
class DosenController
{
    private $jadwalModel;
    private $mahasiswaModel;
    private $absensiModel;
    private $matakuliahModel;
    private $dosenModel;
    public function __construct()
    {
        $this->jadwalModel = new JadwalModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->absensiModel = new AbsensiModel();
        $this->matakuliahModel = new MatakuliahModel();
        $this->dosenModel = new DosenModel();
    }
    public function dashboard()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $dosen = $this->dosenModel->getByUserId($userId);
        $jadwal = $this->jadwalModel->getAll();
        include __DIR__ . '/../views/dosen/dashboard.php';
    }
    public function laporan()
    {
        $filter = [];
        if ($_GET) {
            if (!empty($_GET['mata_kuliah_id'])) $filter['mata_kuliah_id'] = $_GET['mata_kuliah_id'];
            if (!empty($_GET['tanggal_awal'])) $filter['tanggal_awal'] = $_GET['tanggal_awal'];
            if (!empty($_GET['tanggal_akhir'])) $filter['tanggal_akhir'] = $_GET['tanggal_akhir'];
        }
        $userId = $_SESSION['user_id'] ?? null;
        $dosen = $this->dosenModel->getByUserId($userId);
        if ($dosen) $filter['dosen_id'] = $dosen['id'];
        $data = $this->absensiModel->getLaporan($filter);
        $mkList = $this->matakuliahModel->getAll();
        include __DIR__ . '/../views/dosen/laporan.php';
    }
    public function exportExcel()
    {
        $filter = [];
        if ($_GET) {
            if (!empty($_GET['mata_kuliah_id'])) $filter['mata_kuliah_id'] = $_GET['mata_kuliah_id'];
            if (!empty($_GET['tanggal_awal'])) $filter['tanggal_awal'] = $_GET['tanggal_awal'];
            if (!empty($_GET['tanggal_akhir'])) $filter['tanggal_akhir'] = $_GET['tanggal_akhir'];
        }
        $userId = $_SESSION['user_id'] ?? null;
        $dosen = $this->dosenModel->getByUserId($userId);
        if ($dosen) $filter['dosen_id'] = $dosen['id'];
        $data = $this->absensiModel->getLaporan($filter);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'LAPORAN ABSENSI DOSEN');
        $sheet->setCellValue('A3', 'Tanggal');
        $sheet->setCellValue('B3', 'Mahasiswa');
        $sheet->setCellValue('C3', 'Mata Kuliah');
        $sheet->setCellValue('D3', 'Jam Absen');
        $sheet->setCellValue('E3', 'Status');
        $row = 4;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['tanggal']);
            $sheet->setCellValue('B' . $row, $item['mahasiswa']);
            $sheet->setCellValue('C' . $row, $item['nama_mk']);
            $sheet->setCellValue('D' . $row, $item['jam_absen']);
            $sheet->setCellValue('E' . $row, $item['status']);
            $row++;
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Absensi_Dosen.xlsx"');
        $writer->save('php://output');
        exit;
    }
}