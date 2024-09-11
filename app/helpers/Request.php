<?php

namespace Warkim\helpers;

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
}
