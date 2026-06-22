<?php
namespace App\controllers;
use App\models\jadwalmodel;
use App\models\absensimodel;
use App\models\matakuliahmodel;
use App\models\dosenmodel;

class dosencontroller
{
    private $jadwalModel;
    private $absensiModel;
    private $matakuliahModel;
    private $dosenModel;

    public function __construct()
    {
        $this->jadwalModel = new jadwalmodel();
        $this->absensiModel = new absensimodel();
        $this->matakuliahModel = new matakuliahmodel();
        $this->dosenModel = new dosenmodel();
    }

    public function dashboard()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $dosen = $this->dosenModel->getByUserId($userId);
        if (!$dosen) {
            echo "Data dosen tidak ditemukan.";
            return;
        }
        $namaDosen = $dosen['nama'];
        $jadwal = $this->jadwalModel->getByDosen($dosen['id']);
        $statistik = $this->absensiModel->getStatistikByDosen($dosen['id']);
        $hadir = 0;
        $terlambat = 0;
        $alpha = 0;
        foreach ($statistik as $s) {
            if ($s['status'] == 'Hadir') $hadir = $s['total'];
            elseif ($s['status'] == 'Terlambat') $terlambat = $s['total'];
            elseif ($s['status'] == 'Tidak Hadir') $alpha = $s['total'];
        }
        $totalMahasiswa = $this->absensiModel->getTotalMahasiswaByDosen($dosen['id']);
        include __DIR__ . '/../views/dosen/dashboard.php';
    }

    public function laporan()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $dosen = $this->dosenModel->getByUserId($userId);
        if (!$dosen) {
            echo "Data dosen tidak ditemukan.";
            return;
        }
        $filter = [];
        if ($_GET) {
            if (!empty($_GET['mata_kuliah_id'])) $filter['mata_kuliah_id'] = $_GET['mata_kuliah_id'];
            if (!empty($_GET['tanggal_awal'])) $filter['tanggal_awal'] = $_GET['tanggal_awal'];
            if (!empty($_GET['tanggal_akhir'])) $filter['tanggal_akhir'] = $_GET['tanggal_akhir'];
        }
        $filter['dosen_id'] = $dosen['id'];
        $data = $this->absensiModel->getLaporan($filter);
        $mkList = $this->matakuliahModel->getAll();
        include __DIR__ . '/../views/dosen/laporan.php';
    }

    public function exportExcel()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $dosen = $this->dosenModel->getByUserId($userId);
        if (!$dosen) {
            echo "Data dosen tidak ditemukan.";
            return;
        }
        $filter = [];
        if ($_GET) {
            if (!empty($_GET['mata_kuliah_id'])) $filter['mata_kuliah_id'] = $_GET['mata_kuliah_id'];
            if (!empty($_GET['tanggal_awal'])) $filter['tanggal_awal'] = $_GET['tanggal_awal'];
            if (!empty($_GET['tanggal_akhir'])) $filter['tanggal_akhir'] = $_GET['tanggal_akhir'];
        }
        $filter['dosen_id'] = $dosen['id'];
        $data = $this->absensiModel->getLaporan($filter);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'LAPORAN ABSENSI DOSEN');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A2', 'Dicetak: ' . date('d-m-Y H:i:s'));
        $sheet->mergeCells('A2:E2');
        $headers = ['No', 'Tanggal', 'Mahasiswa', 'Mata Kuliah', 'Status'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $col++;
        }
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
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Absensi_Dosen_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}