<?php

function view(string $view_path, $data = null)
{
    $path = preg_replace("/\./", "/", $view_path);

    (!empty($data) && is_array($data))
        ? extract($data)
        : $data;

    $file_path = __DIR__ . '/../views/' . $path . '.php';

    // JIKA FILE TIDAK DITEMUKAN
    if (!file_exists($file_path)) return false;

    require_once $file_path;
}
