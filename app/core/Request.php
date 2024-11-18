<?php

namespace Warkim\core;

class Request
{
    protected $queryParams;
    protected $bodyParams;

    public function __construct()
    {
        $this->queryParams  = $_GET;
        $this->bodyParams   = $_POST;

        // Jika request body adalah JSON
        if ($this->isJson()) {
            $this->bodyParams = json_decode(file_get_contents('php://input'), true);
        }
    }

    public function get()
    {
        return $_SERVER['REQUEST_URI'];
    }

    // Metode untuk memeriksa apakah request body adalah JSON
    public function isJson()
    {
        return isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    // Mengambil query parameter
    public function query($key = null, $default = null)
    {
        if ($key === null) {
            return (object)$this->queryParams;
        }

        return $this->queryParams[$key] ?? $default;
    }

    // Mengambil body parameter
    public function input($key = null, $default = null)
    {
        if ($key === null) {
            return $this->bodyParams;
        }

        return $this->bodyParams[$key] ?? $default;
    }

    // Mengambil semua parameter (query dan body)
    public function all()
    {
        return (object)array_merge($this->queryParams, $this->bodyParams);
    }

    public function session()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION;
    }

    public function flash($key, $value)
    {
        $this->session()[$key] = $value;
    }

    public function getFlash($key)
    {
        if (isset($this->session()[$key])) {
            $message = $this->session()[$key];
            unset($this->session()[$key]);  // Flash message hanya sekali dipakai
            return $message;
        }
        return null;
    }
}
