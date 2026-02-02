<?php
/**
 * Classe Session - Gestion des sessions
 */

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }

    public static function isLoggedIn(): bool
    {
        return self::has('user_id') || self::has('adherent_id');
    }

    public static function isAdmin(): bool
    {
        return self::get('role') === 'administrateur';
    }

    public static function isBibliothecaire(): bool
    {
        return self::get('role') === 'bibliothecaire';
    }

    public static function isAdherent(): bool
    {
        return self::has('adherent_id');
    }

    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash(): ?array
    {
        $flash = self::get('flash');
        self::remove('flash');
        return $flash;
    }
}