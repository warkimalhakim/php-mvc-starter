<?php

namespace Warkim\core;

class Route
{
    protected static $method;

    public static function handle(string $request_type = 'GET', string $path = '/', $callable = [])
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


        // Redirect jika URI berisi '/index'
        if (preg_match('/\/index$/', $uri)) {
            header("Location: " . rtrim(str_replace('/index', '', $uri), '/'));
            exit;
        }

        // Inisialisasi request
        $request = new Request();

        // Jika request method tidak sesuai, return false
        if ($request_type != $_SERVER['REQUEST_METHOD']) {

            // CEK METHOD YANG DIKIRIM BLADE @method
            if (!empty($request->input('_method'))) {
                $_SERVER['REQUEST_METHOD'] = $request->input('_method');
                $request_type = $request->input('_method');
                return true;
            }

            return false;
        }

        // Deteksi apakah URL yang diminta cocok dengan route
        if (self::matchUriWithPath($uri, $path, $parameters)) {
            return self::handleCallable($callable, $request, $parameters);
        }

        return false;
    }


    private static function matchUriWithPath($uri, $path, &$parameters)
    {
        // Potong URI dan path ke segmen masing-masing
        $uriSegments = explode('/', trim($uri, '/'));
        $pathSegments = explode('/', trim($path, '/'));

        // Jika jumlah segmen berbeda, langsung return false
        if (count($uriSegments) !== count($pathSegments)) {
            return false;
        }

        // Simpan parameter yang ditemukan di route
        $parameters = [];

        // Bandingkan setiap segmen
        foreach ($pathSegments as $index => $segment) {
            // Jika segmen adalah parameter dinamis (misalnya {id} atau {slug})
            if (preg_match('/\{([^\}]*)\}/', $segment, $matches)) {

                if ($uriSegments[$index] == "index") {
                    static::$method = "index";
                }

                if (in_array($uriSegments[$index], ["create", "edit", "store", "update", "delete", "destroy"])) {
                    return false;
                }

                // Simpan parameter dengan nama yang sesuai
                $parameters[$matches[1]] = $uriSegments[$index];

                // 
            } else if ($segment !== $uriSegments[$index]) {
                // Jika segmen tidak cocok dan bukan parameter dinamis, return false
                return false;
            }
        }

        return true;
    }

    private static function handleCallable($callable, $request, $parameters = [])
    {

        // Jika callable adalah function
        if (is_callable($callable)) {
            return call_user_func($callable, $request, ...array_values($parameters));
        }

        // Jika callable adalah array [ClassName, methodName]
        if (is_array($callable)) {

            if (!empty(static::$method) && static::$method === "index") {
                unset($callable[1]);
                array_push($callable, static::$method);
                $callable = array_values($callable);
            }

            list($class, $method) = $callable;

            // Cek apakah class dan method valid
            if (class_exists($class) && method_exists($class, $method)) {
                $controller = new $class();
                return call_user_func([$controller, $method], $request, ...array_values($parameters));
            }
        }

        unset($callable);

        // Jika callable tidak valid, return null
        return null;
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
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $route ? ($route === $uri) : $uri;
    }

    public static function query(string $key = null)
    {
        $query_str = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        if (empty($query_str)) return null;

        parse_str($query_str, $query);
        return !empty($key) ? ($query[$key] ?? null) : $query;
    }
}

// Route Get URL
function route(?string $route = null)
{
    return new Route($route);
}
