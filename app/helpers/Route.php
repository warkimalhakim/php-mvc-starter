<?php

namespace Warkim\helpers;

class Route
{
    public static function handle(string $request_type = 'GET', string $path = '/', $callable = [])
    {

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $query = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);

        // JIKA METHOD TIDAK SAMA
        if ($request_type != $_SERVER['REQUEST_METHOD']) return false;

        // DETEKSI PATH
        if ($path != $uri) return false;

        // DEFINISIKAN REQUEST JIKA ADA
        $request = new Request();

        // JIKA CALLABLE ADALAH FUNCTION
        if (is_callable($callable)) {
            return call_user_func($callable, $request);
        }

        // CEK JIKA ADA PARAMETER YANG DIKIRIM
        // DISIMPAN KE VARIABEL $parameters
        // parse_str($query, $parameters);

        // JIKA CALLABLE ADALAH ARRAY
        if (is_array($callable)) {
            count($callable) < 2 ? array_push($callable, 'index') : $callable;

            list($class, $method) = $callable;
            // Jika callable adalah class dan method adalah method dari class tersebut
            if (is_string($class) && is_string($method)) {
                // Cek & Memanggil method dari class
                return method_exists($class, $method) ?
                    call_user_func([new $class, $method], $request) :
                    null;
            }
            // 
        } else {
            return null;
        }
    }

    public static function get(string $path, $callable = 'callable', $method = 'index')
    {
        return self::handle('GET', $path, $callable, $method);
    }

    public static function post(string $path, $callable = 'callable', $method = 'index')
    {
        return self::handle('POST', $path, $callable, $method);
    }

    public static function put(string $path, $callable = 'callable', $method = 'index')
    {
        return self::handle('PUT', $path, $callable, $method);
    }

    public static function delete(string $path, $callable = 'callable', $method = 'index')
    {
        return self::handle('DELETE', $path, $callable, $method);
    }


    public static function is($route = null)
    {
        $uri = !empty($route) ? ($route === parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ? true : false) : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $uri;
    }

    public static function query(string $key = null)
    {
        $query_str = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        if (empty($query_str)) return null;

        parse_str($query_str, $query);
        return !empty($key) ? (!empty($query[$key]) ? $query[$key] : null) : $query;
    }
}
