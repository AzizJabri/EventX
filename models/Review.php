<?php

require_once __DIR__ . '/../database/Database.php';

class Review {
    public $id;
    public $booking_id;
    public $user_id;
    public $rating;
    public $comment;

    private $conn;

    public function __construct($booking_id = 0, $user_id = 0, $rating = 0, $comment = '') {
        $this->conn = Database::getConnection();
        $this->booking_id = $booking_id;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function save() {
        $stmt = $this->conn->prepare("INSERT INTO reviews (booking_id,user_id, rating, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$this->booking_id, $this->user_id, $this->rating, $this->comment]);
    }

    public static function all() {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT * FROM reviews");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allByBooking($booking_id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE booking_id = ?");
        $stmt->execute([$booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $rating, $comment) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
        return $stmt->execute([$rating, $comment, $id]);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function forVenue($venueId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT r.*, u.name AS user_name 
            FROM reviews r
            JOIN bookings b ON r.booking_id = b.id
            JOIN users u ON r.user_id = u.id
            WHERE b.venue_id = ?
        ");
        $stmt->execute([$venueId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function forUser($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT r.*, v.name as venue_name
            FROM reviews r
            JOIN bookings b ON r.booking_id = b.id
            JOIN venues v ON b.venue_id = v.id
            WHERE r.user_id = ?
            ORDER BY r.id DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
