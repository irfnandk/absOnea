<?php
namespace App\models;
use App\config\database;

class absensimodel
{
    private $db;
    public function __construct()
    {
        $this->db = database::getConnection();
    }
    public function getAll()
    {
        return $this->db->query("SELECT a.*, m.nama as mahasiswa, mk.nama_mk FROM absensi a JOIN mahasiswa m ON a.mahasiswa_id = m.id JOIN jadwal j ON a.jadwal_id = j.id JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id ORDER BY a.tanggal DESC, a.jam_absen DESC LIMIT 10")->fetchAll();
    }
    public function getByMahasiswa($mahasiswaId)
    {
        $stmt = $this->db->prepare("SELECT a.*, mk.nama_mk FROM absensi a JOIN jadwal j ON a.jadwal_id = j.id JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id WHERE a.mahasiswa_id = ? ORDER BY a.tanggal DESC");
        $stmt->execute([$mahasiswaId]);
        return $stmt->fetchAll();
    }
    public function getByJadwalAndTanggal($jadwalId, $tanggal)
    {
        $stmt = $this->db->prepare("SELECT * FROM absensi WHERE jadwal_id = ? AND tanggal = ?");
        $stmt->execute([$jadwalId, $tanggal]);
        return $stmt->fetchAll();
    }
    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO absensi (mahasiswa_id, jadwal_id, tanggal, jam_absen, status) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['mahasiswa_id'],
                $data['jadwal_id'],
                $data['tanggal'],
                $data['jam_absen'],
                $data['status']
            ]);
        } catch (\Exception $e) {
            error_log('Error insert absensi: ' . $e->getMessage());
            return false;
        }
    }
    public function getStatistik()
    {
        return $this->db->query("SELECT status, COUNT(*) as total FROM absensi GROUP BY status")->fetchAll();
    }
    public function getLaporan($filter)
    {
        $sql = "SELECT a.*, m.nama as mahasiswa, mk.nama_mk FROM absensi a JOIN mahasiswa m ON a.mahasiswa_id = m.id JOIN jadwal j ON a.jadwal_id = j.id JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id WHERE 1=1";
        $params = [];
        if (!empty($filter['mata_kuliah_id'])) {
            $sql .= " AND j.mata_kuliah_id = ?";
            $params[] = $filter['mata_kuliah_id'];
        }
        if (!empty($filter['dosen_id'])) {
            $sql .= " AND j.dosen_id = ?";
            $params[] = $filter['dosen_id'];
        }
        if (!empty($filter['mahasiswa_id'])) {
            $sql .= " AND a.mahasiswa_id = ?";
            $params[] = $filter['mahasiswa_id'];
        }
        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
            $sql .= " AND a.tanggal BETWEEN ? AND ?";
            $params[] = $filter['tanggal_awal'];
            $params[] = $filter['tanggal_akhir'];
        }
        $sql .= " ORDER BY a.tanggal DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM absensi")->fetchColumn();
    }
    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM absensi WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }
    public function getRecent($limit = 10)
    {
        $stmt = $this->db->prepare("SELECT a.*, m.nama, mk.nama_mk FROM absensi a JOIN mahasiswa m ON a.mahasiswa_id = m.id JOIN jadwal j ON a.jadwal_id = j.id JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id ORDER BY a.tanggal DESC, a.jam_absen DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}