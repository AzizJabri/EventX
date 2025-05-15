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

    public static function getTopRatedVenues($limit = 5) {
        $conn = Database::getConnection();

        // First: Get venues ordered by average rating
        $stmt = $conn->prepare("
            SELECT v.*, AVG(r.rating) AS average_rating, COUNT(r.id) AS review_count
            FROM venues v
            JOIN bookings b ON v.id = b.venue_id
            JOIN reviews r ON b.id = r.booking_id
            GROUP BY v.id
            ORDER BY average_rating DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $ratedVenues = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $remaining = $limit - count($ratedVenues);

        if ($remaining > 0) {
            // Second: Fill in with random venues not already included
            $placeholders = implode(',', array_fill(0, count($ratedVenues), '?'));
            $query = "
                SELECT * FROM venues
                WHERE id NOT IN ($placeholders)
                ORDER BY RAND()
                LIMIT $remaining
            ";
            $stmt2 = $conn->prepare($query);
            foreach ($ratedVenues as $i => $venue) {
                $stmt2->bindValue($i + 1, $venue['id'], PDO::PARAM_INT);
            }
            $stmt2->execute();
            $randomVenues = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // Combine rated and random venues
            $ratedVenues = array_merge($ratedVenues, $randomVenues);
        }

        return $ratedVenues;
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

    public static function filter($filters = [])
{
    $pdo = Database::getConnection();
    $query = "SELECT * FROM venues WHERE 1=1";
    $params = [];

    if (!empty($filters['name'])) {
        $query .= " AND name LIKE :name";
        $params[':name'] = '%' . $filters['name'] . '%';
    }

    if (!empty($filters['category_id'])) {
        $query .= " AND category_id = :category_id";
        $params[':category_id'] = $filters['category_id'];
    }

    if (!empty($filters['location'])) {
        $query .= " AND location LIKE :location";
        $params[':location'] = '%' . $filters['location'] . '%';
    }

    if (!empty($filters['capacity'])) {
        $query .= " AND capacity >= :capacity";
        $params[':capacity'] = $filters['capacity'];
    }

    if (!empty($filters['price'])) {
        $query .= " AND price <= :price";
        $params[':price'] = $filters['price'];
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
