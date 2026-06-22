<?php
// ============================================================
// FILE: app/models/absensimodel.php (PERBAIKAN - TANGANI TABEL KELAS TIDAK ADA)
// ============================================================
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
        $stmt = $this->db->prepare("INSERT INTO absensi (mahasiswa_id, jadwal_id, tanggal, jam_absen, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['mahasiswa_id'], $data['jadwal_id'], $data['tanggal'], $data['jam_absen'], $data['status']]);
    }

    public function getStatistik()
    {
        return $this->db->query("SELECT status, COUNT(*) as total FROM absensi GROUP BY status")->fetchAll();
    }

    public function getStatistikByDosen($dosenId)
    {
        $stmt = $this->db->prepare("
            SELECT a.status, COUNT(*) as total 
            FROM absensi a
            JOIN jadwal j ON a.jadwal_id = j.id
            WHERE j.dosen_id = ?
            GROUP BY a.status
        ");
        $stmt->execute([$dosenId]);
        return $stmt->fetchAll();
    }

    public function getTotalMahasiswaByDosen($dosenId)
    {
        // Coba gunakan tabel kelas jika ada
        try {
            $stmt = $this->db->query("SELECT to_regclass('kelas')");
            $exists = $stmt->fetchColumn();
            if ($exists) {
                $stmt = $this->db->prepare("
                    SELECT COUNT(DISTINCT m.id) as total
                    FROM mahasiswa m
                    JOIN kelas k ON m.id = k.mahasiswa_id
                    JOIN jadwal j ON k.mata_kuliah_id = j.mata_kuliah_id
                    WHERE j.dosen_id = ?
                ");
                $stmt->execute([$dosenId]);
                return (int)$stmt->fetchColumn();
            }
        } catch (\Exception $e) {
            // Tabel kelas tidak ada, lanjut ke alternatif
        }

        // Alternatif: hitung mahasiswa yang pernah absen pada jadwal dosen tersebut
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT a.mahasiswa_id) as total
            FROM absensi a
            JOIN jadwal j ON a.jadwal_id = j.id
            WHERE j.dosen_id = ?
        ");
        $stmt->execute([$dosenId]);
        return (int)$stmt->fetchColumn();
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