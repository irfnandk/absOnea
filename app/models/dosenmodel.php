<?php
namespace App\models;
use App\config\Database;

class DosenModel
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    public function getAll()
    {
        return $this->db->query("SELECT d.*, u.email FROM dosen d JOIN users u ON d.user_id = u.id")->fetchAll();
    }
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM dosen WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM dosen WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM dosen")->fetchColumn();
    }
    public function create($data)
    {
        $this->db->beginTransaction();
        try {
            $userModel = new UserModel();
            $userId = $userModel->create($data['email'], $data['password'], 'dosen');
            if (!$userId) throw new \Exception('Gagal membuat user');
            $stmt = $this->db->prepare("INSERT INTO dosen (nidn, nama, email, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['nidn'], $data['nama'], $data['email'], $userId]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE dosen SET nidn = ?, nama = ?, email = ? WHERE id = ?");
        return $stmt->execute([$data['nidn'], $data['nama'], $data['email'], $id]);
    }
    public function delete($id)
    {
        $this->db->beginTransaction();
        try {
            $dosen = $this->getById($id);
            if (!$dosen) throw new \Exception('Data tidak ditemukan');
            $stmt = $this->db->prepare("DELETE FROM dosen WHERE id = ?");
            $stmt->execute([$id]);
            $userModel = new UserModel();
            $userModel->delete($dosen['user_id']);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}