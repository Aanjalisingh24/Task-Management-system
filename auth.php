<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "your_secret_key";

function generateJWT($user_id, $role) {
    global $secret_key;
    $payload = [
        'iss' => 'http://yourdomain.com',
        'aud' => 'http://yourdomain.com',
        'iat' => time(),
        'nbf' => time(),
        'exp' => time() + (60*60), 
        'data' => [
            'id' => $user_id,
            'role' => $role
        ]
    ];
    return JWT::encode($payload, $secret_key, 'HS256');
}

function validateJWT($token) {
    global $secret_key;
    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        return null;
    }
}
?>
