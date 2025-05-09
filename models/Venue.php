<?php

require_once __DIR__ . '/../database/Database.php';

class Venue {
    public $id;
    public $name;
    public $location;
    public $capacity;

    public $price;

    public $category_id;

    public $image_url;
    private $conn;

    public function __construct($name = '', $location = '', $capacity = 0, $price = 0.0, $category_id = null, $image_url = '') {
        $this->conn = Database::getConnection();
        $this->name = $name;
        $this->location = $location;
        $this->capacity = $capacity;
        $this->price = $price;
        $this->category_id = $category_id;
        $this->image_url = $image_url;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO venues (name, location, capacity, price, category_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$this->name, $this->location, $this->capacity, $this->price, $this->category_id, $this->image_url]);
        return $this->conn->lastInsertId(); // return the inserted ID
    }

    public static function all() {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT * FROM venues");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM venues WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $name, $location, $capacity, $price, $category_id, $image_url) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE venues SET name = ?, location = ?, capacity = ?, price = ?, category_id = ?, image_url = ? WHERE id = ?");
        return $stmt->execute([$name, $location, $capacity, $price, $category_id, $image_url, $id]);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM venues WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getPopularVenues($limit = 5) {
        $conn = Database::getConnection();
    
        $stmt = $conn->prepare("
            SELECT v.*, COUNT(b.id) AS bookings_count
            FROM venues v
            LEFT JOIN bookings b ON v.id = b.venue_id
            GROUP BY v.id
            ORDER BY bookings_count DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateImage($id, $image_url) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE venues SET image_url = ? WHERE id = ?");
        return $stmt->execute([$image_url, $id]);
    }

    public static function search($query) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM venues WHERE name LIKE ? OR location LIKE ?");
        $stmt->execute(['%' . $query . '%', '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
