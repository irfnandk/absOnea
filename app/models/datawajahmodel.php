<?php
// ============================================================
// FILE: app/models/datawajahmodel.php
// ============================================================
namespace App\models;
use App\config\database;

class datawajahmodel
{
    private $db;
    public function __construct()
    {
        $this->db = database::getConnection();
    }

    public function getAll()
    {
        return $this->db->query("SELECT dw.*, m.nim, m.nama FROM data_wajah dw JOIN mahasiswa m ON dw.mahasiswa_id = m.id")->fetchAll();
    }

    public function getByMahasiswa($mahasiswaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM data_wajah WHERE mahasiswa_id = ?");
        $stmt->execute([$mahasiswaId]);
        return $stmt->fetch();
    }

    public function create($mahasiswaId, $embedding, $fotoUrl = null)
    {
        $embeddingStr = '{' . implode(',', $embedding) . '}';
        $stmt = $this->db->prepare("INSERT INTO data_wajah (mahasiswa_id, embedding, foto_url) VALUES (?, ?, ?) ON CONFLICT (mahasiswa_id) DO UPDATE SET embedding = ?, foto_url = ?");
        return $stmt->execute([$mahasiswaId, $embeddingStr, $fotoUrl, $embeddingStr, $fotoUrl]);
    }

    public function delete($mahasiswaId)
    {
        $stmt = $this->db->prepare("DELETE FROM data_wajah WHERE mahasiswa_id = ?");
        return $stmt->execute([$mahasiswaId]);
    }

    public function getAllEmbeddings()
    {
        $stmt = $this->db->query("SELECT dw.mahasiswa_id, dw.embedding, m.nama FROM data_wajah dw JOIN mahasiswa m ON dw.mahasiswa_id = m.id");
        $rows = $stmt->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $embeddingArray = array_map('floatval', explode(',', trim($row['embedding'], '{}')));
            $result[] = ['mahasiswa_id' => $row['mahasiswa_id'], 'nama' => $row['nama'], 'embedding' => $embeddingArray];
        }
        return $result;
    }
}