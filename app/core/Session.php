<?php

namespace Warkim\core;

class Session
{

    public static $session_name = "sesi";

    public static function all()
    {
        return !isset($_SESSION[static::$session_name]) ? $_SESSION[static::$session_name] = [] : $_SESSION[static::$session_name];
    }

    public static function get(string $session_name = null)
    {
        $session = !isset($_SESSION[static::$session_name]) ? ($_SESSION[static::$session_name] = []) : $_SESSION[static::$session_name];
        return !empty($session_name) ? (isset($session[$session_name]) ? $session[$session_name] : null) : $session;
    }

    public static function put(string $key, $value)
    {
        $_SESSION[static::$session_name] = array_merge($_SESSION[static::$session_name], [$key => $value]);
    }

    public static function forget(string $key)
    {
        if (isset($_SESSION[static::$session_name][$key])) unset($_SESSION[static::$session_name][$key]);
    }

    public static function flush()
    {
        unset($_SESSION[static::$session_name]);
    }
}
