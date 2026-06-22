<?php

namespace App\models;
use App\config\database;

class jadwalmodel
{
    private $db;
    public function __construct()
    {
        $this->db = database::getConnection();
    }
    public function getAll()
    {
        return $this->db->query("SELECT j.*, mk.nama_mk, d.nama as dosen_nama FROM jadwal j JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id JOIN dosen d ON j.dosen_id = d.id ORDER BY j.hari, j.jam_mulai")->fetchAll();
    }
    public function getByDosen($dosenId)
    {
        $stmt = $this->db->prepare("SELECT j.*, mk.nama_mk FROM jadwal j JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id WHERE j.dosen_id = ? ORDER BY j.hari, j.jam_mulai");
        $stmt->execute([$dosenId]);
        return $stmt->fetchAll();
    }
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM jadwal WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO jadwal (id, mata_kuliah_id, dosen_id, hari, jam_mulai, jam_selesai, ruang) VALUES (gen_random_uuid(), ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['mata_kuliah_id'], $data['dosen_id'], $data['hari'], $data['jam_mulai'], $data['jam_selesai'], $data['ruang']]);
    }
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE jadwal SET mata_kuliah_id = ?, dosen_id = ?, hari = ?, jam_mulai = ?, jam_selesai = ?, ruang = ? WHERE id = ?");
        return $stmt->execute([$data['mata_kuliah_id'], $data['dosen_id'], $data['hari'], $data['jam_mulai'], $data['jam_selesai'], $data['ruang'], $id]);
    }
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM jadwal WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getAktif()
    {
        $now = date('H:i:s');
        $hari = date('l');
        $map = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
        $hariIndo = $map[$hari];
        $stmt = $this->db->prepare("SELECT j.*, mk.nama_mk FROM jadwal j JOIN mata_kuliah mk ON j.mata_kuliah_id = mk.id WHERE j.hari = ? AND ? BETWEEN j.jam_mulai AND j.jam_selesai");
        $stmt->execute([$hariIndo, $now]);
        return $stmt->fetch();
    }
}