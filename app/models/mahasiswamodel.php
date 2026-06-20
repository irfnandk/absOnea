<?php
namespace App\models;
use App\config\database;

class mahasiswamodel
{
    private $db;
    public function __construct()
    {
        $this->db = database::getConnection();
    }

    public function getAll()
    {
        return $this->db->query("SELECT m.*, u.email FROM mahasiswa m JOIN users u ON m.user_id = u.id")->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    }

    public function create($data)
    {
        $this->db->beginTransaction();
        try {
            $userModel = new \App\models\usermodel();
            $userId = $userModel->create($data['email'], $data['password'], 'mahasiswa');
            if (!$userId) throw new \Exception('Gagal membuat user');
            $stmt = $this->db->prepare("INSERT INTO mahasiswa (nim, nama, prodi, angkatan, user_id, ttl, jabatan) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['nim'], $data['nama'], $data['prodi'], $data['angkatan'], $userId, $data['ttl'] ?? null, $data['jabatan'] ?? null]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [];
        if (isset($data['nim'])) {
            $fields[] = "nim = ?";
            $params[] = $data['nim'];
        }
        if (isset($data['nama'])) {
            $fields[] = "nama = ?";
            $params[] = $data['nama'];
        }
        if (isset($data['prodi'])) {
            $fields[] = "prodi = ?";
            $params[] = $data['prodi'];
        }
        if (isset($data['angkatan'])) {
            $fields[] = "angkatan = ?";
            $params[] = $data['angkatan'];
        }
        if (isset($data['ttl'])) {
            $fields[] = "ttl = ?";
            $params[] = $data['ttl'];
        }
        if (isset($data['jabatan'])) {
            $fields[] = "jabatan = ?";
            $params[] = $data['jabatan'];
        }
        if (isset($data['foto'])) {
            $fields[] = "foto = ?";
            $params[] = $data['foto'];
        }
        if (empty($fields)) return false;
        $params[] = $id;
        $sql = "UPDATE mahasiswa SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $this->db->beginTransaction();
        try {
            $mhs = $this->getById($id);
            if (!$mhs) throw new \Exception('Data tidak ditemukan');
            $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = ?");
            $stmt->execute([$id]);
            $userModel = new \App\models\usermodel();
            $userModel->delete($mhs['user_id']);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}