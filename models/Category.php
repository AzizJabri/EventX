<?php
require_once __DIR__ . '/../database/Database.php';

class Category {
    public $id;
    public $name;
    public $description;

    private $conn;

    public function __construct($name = '', $description = '') {
        $this->conn = Database::getConnection();
        $this->name = $name;
        $this->description = $description;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        return $stmt->execute([$this->name, $this->description]);
    }

    public static function all()
    {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($name)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public static function update($id, $name)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public static function delete($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

}