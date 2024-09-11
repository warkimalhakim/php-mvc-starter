<?php

use Warkim\helpers\Route;

function config(string $env_key)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/app/config/app.php';

    if (file_exists($path)) {
        require_once $path;
        $app = app();
        $getEnv = $app['env'] ??  $_SERVER['DOCUMENT_ROOT'] . '/_env/.env';
        $env = loadEnv($getEnv);
        return !empty($env[$env_key]) ? $env[$env_key] . "\n" : null;
    } else {
        return null;
    }
}

function loadEnv($file)
{
    if (!file_exists($file)) {
        throw new Exception("File .env not found.");
    }

    $env = [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Mengabaikan komentar
        if (strpos($line, '#') === 0) {
            continue;
        }

        // Memisahkan key dan value
        list($key, $value) = explode('=', $line, 2) + [NULL, NULL];

        if (!is_null($key) && !is_null($value)) {
            $env[trim($key)] = trim(str_replace(' ', '', $value));
        }
    }

    return $env;
}



function route(string $path, $data = null)
{
    $pattern = '/[^a-zA-Z0-9\/\-\_\.\:]/';
    $base_url = config('BASE_URL');
    $base_url = preg_replace($pattern, '', $base_url);
    // Hapus trailing slash
    $base_url = rtrim($base_url, '/');

    // Hapus spasi dari $path jika diperlukan
    $path = preg_replace('/[\.]/', '/', $path);
    $path = str_replace(' ', '', $path);
    $path = rtrim(ltrim($path, '/'), '/');

    $route = new Route();

    // Kembalikan base_url + path
    if (!empty($data)):

        $full_url = $base_url . '/' . $path . '/' . $data;
        return $full_url;

    else:
        return $base_url . '/' . $path;
    endif;
}

// function is(string $route = null)
// {
//     return route()->is($route);
// }
