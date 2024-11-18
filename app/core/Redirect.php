<?php

namespace Warkim\core;

use Warkim\core\Request;

class Redirect
{
    public $url;
    public $request;

    public function __construct($url = null)
    {
        $this->url = !empty($url) ? $url : null;
        $this->request = new Request();
    }

    public static function to(string $url)
    {
        new self($url);
        return header("Location: $url");
    }

    public function with($key, $message)
    {
        $this->request->flash($key, $message);

        if (!empty($this->url)) {
            return header("Location: $this->url");
        } else {
            return header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
    public function back()
    {
        new self();

        if (!empty($this->url)) {
            return header("Location: $this->url");
        } else {
            return header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
}
