<?php

namespace lib\Router\classes;


class Session
{
    private static $instance = null;

    // create a singleton instnace
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function has($key)
    {
        return (bool) $this->get($key);
    }

    public function insert($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        if (isset($_SESSION['_flash'][$key])) {
            return $_SESSION['_flash'][$key];
        }
        return $_SESSION[$key] ?? $default;
    }

    public function revoke($key)
    {
        if ($key == 'flash') {
            unset($_SESSION['_flash']);
            return;
        }

        if ($this->has($key)) {
            unset($_SESSION[$key]);
            return;
        }
        return false;
    }

    public function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public function flushFlash()
    {
        $_SESSION['_flash'] = [];
    }


    public function flush()
    {
        $_SESSION = [];
    }

    public function destroy()
    {
        $this->flush();

        $params = session_get_cookie_params();
        setcookie(
            session_name(),     // Use the session name to ensure the correct cookie is deleted
            '',                 // Empty value to invalidate the cookie
            time() - 3600,      // Expiration time in the past
            $params['path'],    // Cookie path
            $params['domain'],  // Cookie domain
            $params['secure'],  // Secure flag (only send over HTTPS if true)
            $params['httponly'] // HttpOnly flag
        );

    }

}