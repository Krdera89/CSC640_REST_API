<?php
namespace App;

class Response {
    public static function json($data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        // Basic CORS 
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    public static function methodNotAllowed(array $allowed): void {
        header('Allow: ' . implode(', ', $allowed));
        self::json(['error' => 'Method not allowed', 'allowed' => $allowed], 405);
    }
}
