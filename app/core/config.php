<?php

use Warkim\core\Route;
use Warkim\core\Session;
use Warkim\core\Redirect;

function config(string $env_key)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/app/config/app.php';

    if (file_exists($path)) {
        require_once $path;
        $app = app();
        $getEnv = $app['env'] ??  $_SERVER['DOCUMENT_ROOT'] . '/_env/.env';
        $env = loadEnv($getEnv);
        return !empty($env[$env_key]) ? $env[$env_key] : null;
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
            $env[trim($key)] = str_replace(' ', '', trim($value, ' '));
        }
    }

    return $env;
}

// FUNCTION CONTROL

function route(string $path, $data = null, $http_host = false)
{
    $pattern = '/[^a-zA-Z0-9\/\-\_\.\:]/';
    $base_url = !empty(config('BASE_URL')) && !$http_host ? config('BASE_URL') : 'http://' . $_SERVER['HTTP_HOST'];
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

function redirect(?string $url = null)
{
    $request = new Redirect($url);
    return $request;
}

function to($url)
{
    return redirect()::to($url);
}

// function with(string $type, $message)
// {
//     // SET SESSION
//     Session::put($type, $message);
//     return back();
// }

function back()
{
    return redirect()->back();
}

function session(?string $key = null)
{
    $session = new Session();
    return !empty($key) ? (!empty($session->get($key)) ? true : false) : $session;
}
function all()
{
    $session = new Session();
    return $session->all();
}

function get(?string $session_name = null)
{
    $session = session();
    return $session->get($session_name);
}

function put(string $key, $value)
{
    return session()->put($key, $value);
}

function forget(string $key)
{
    session()->forget($key);
}
function clear()
{
    session()->flush();
}

function flash(string $key)
{
    $message = session()->get($key);
    if (session($key)) session()->forget($key);
    return $message;
}
