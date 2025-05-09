<?php

require_once __DIR__ . '/../database/Database.php';
class User {
    private $pdo;

    private $id;
    private $name;
    private $email;
    private $password;
    private $role = 'client'; // default role

    private $created_at;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // Setters
    public function setName($name) {
        $this->name = htmlspecialchars(trim($name));
    }

    public function setEmail($email) {
        $this->email = htmlspecialchars(trim($email));
    }

    public function setPassword($password) {
        // We store hashed password directly
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    // Getters (optional, for session or display)
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }

    // Save method
    public function save() {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$this->name, $this->email, $this->password, $this->role, $this->created_at]);
    }

    public static function all() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Static-like method to fetch by email (can be refactored later)
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->role = $user['role'];
            $this->created_at = $user['created_at'];
            return true;
        }

        return false;
    }

    public static function update($id, $name, $email, $role) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $role, $id]);
    }

    public static function delete($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
