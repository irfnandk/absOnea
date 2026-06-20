<?php
namespace App\models;
use App\config\Database;
class MatakuliahModel
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    public function getAll()
    {
        return $this->db->query("SELECT * FROM mata_kuliah ORDER BY kode_mk")->fetchAll();
    }
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM mata_kuliah")->fetchColumn();
    }
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO mata_kuliah (id, kode_mk, nama_mk, sks) VALUES (gen_random_uuid(), ?, ?, ?)");
        return $stmt->execute([$data['kode_mk'], $data['nama_mk'], $data['sks']]);
    }
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE mata_kuliah SET kode_mk = ?, nama_mk = ?, sks = ? WHERE id = ?");
        return $stmt->execute([$data['kode_mk'], $data['nama_mk'], $data['sks'], $id]);
    }
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM mata_kuliah WHERE id = ?");
        return $stmt->execute([$id]);
    }
}