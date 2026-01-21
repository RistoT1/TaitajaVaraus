<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Composer autoload
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


define('JWT_SECRET', '7kvVBQrxYCHk3hAm7PDN8OAQ8Fv3r1ZZkXSj2x16WRZ');
define('JWT_ALGO', 'HS256');

// Generate a JWT token for a user
function generateToken($user) {
    $payload = [
        'iat' => time(),
        'exp' => time() + 3600, // 1 hour expiration
        'sub' => $user['id'],
        'role' => $user['role'] ?? 'user'
    ];
    return JWT::encode($payload, JWT_SECRET, JWT_ALGO);
}

// Check JWT token from Authorization header
function requireAuth() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Missing Authorization header"]);
        exit;
    }

    $authHeader = $headers['Authorization'];
    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Invalid Authorization header"]);
        exit;
    }

    $token = $matches[1];

    try {
        $decoded = JWT::decode($token, new Key(JWT_SECRET, JWT_ALGO));
        $user = (array)$decoded;
        return $user; 
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Invalid or expired token"]);
        exit;
    }
}

// Admin rooli
function requireAdmin() {
    $user = requireAuth();
    if (!isset($user['role']) || $user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Forbidden: admin only"]);
        exit;
    }
    return $user;
}


