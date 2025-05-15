<?php

require_once __DIR__ . '/../database/Database.php';

class Booking {
    public $id;
    public $user_id;
    public $venue_id;
    public $date;
    public $status;

    private $conn;

    public function __construct($user_id = 0, $venue_id = 0, $date = '', $status = 'pending') {
        $this->conn = Database::getConnection();
        $this->user_id = $user_id;
        $this->venue_id = $venue_id;
        $this->date = $date;
        $this->status = $status;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO bookings (user_id, venue_id, date, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$this->user_id, $this->venue_id, $this->date, $this->status]);
    }

    public static function all() {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT * FROM bookings ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $status) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function recentActivities($limit = 5) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM bookings ORDER BY date DESC LIMIT " . intval($limit));
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function userCanReview($userId, $venueId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM bookings WHERE user_id = ? AND venue_id = ? AND date <= CURDATE()");
        $stmt->execute([$userId, $venueId]);
        return $stmt->fetch() !== false;
    }

    public static function forUser($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT b.*, v.name AS venue_name, v.location
            FROM bookings b
            JOIN venues v ON b.venue_id = v.id
            WHERE b.user_id = ?
            ORDER BY b.date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function filter($filters = []) {
    $pdo = Database::getConnection();
    $query = "SELECT * FROM bookings WHERE 1=1";
    $params = [];

    if (!empty($filters['user_id'])) {
        $query .= " AND user_id = :user_id";
        $params['user_id'] = $filters['user_id'];
    }

    if (!empty($filters['venue_id'])) {
        $query .= " AND venue_id = :venue_id";
        $params['venue_id'] = $filters['venue_id'];
    }

    if (!empty($filters['status'])) {
        $query .= " AND status = :status";
        $params['status'] = $filters['status'];
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
