<?php
namespace App;

class Auth {
    // put this in an env var later if you want
    private const TOKEN = 'super-secret-123';

    public static function requireBearer(): void {
        $hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/^Bearer\s+(.+)$/i', $hdr, $m) || $m[1] !== self::TOKEN) {
            Response::json(['error' => 'Unauthorized'], 401);
        }
    }
}
