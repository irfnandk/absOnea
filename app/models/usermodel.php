<?php
namespace App\models;
use App\config\Database;

class UserModel
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    public function getAll()
    {
        return $this->db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    }
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    public function create($email, $password, $role)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (id, email, password_hash, role) VALUES (gen_random_uuid(), ?, ?, ?) RETURNING id");
        $stmt->execute([$email, $hash, $role]);
        return $stmt->fetchColumn();
    }
    public function update($id, $email, $password = null)
    {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET email = ?, password_hash = ? WHERE id = ?");
            return $stmt->execute([$email, $hash, $id]);
        }
        $stmt = $this->db->prepare("UPDATE users SET email = ? WHERE id = ?");
        return $stmt->execute([$email, $id]);
    }
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}