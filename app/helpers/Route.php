<?php

namespace Warkim\helpers;

// class Route
// {

//     public static function handle(string $request_type = 'GET', string $path = '/', $callable = [])
//     {
//         $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//         // Jika request method tidak sesuai, return false
//         if ($request_type != $_SERVER['REQUEST_METHOD']) {
//             return false;
//         }

//         // Inisialisasi request
//         $request = new Request();

//         // Deteksi apakah URL yang diminta cocok dengan route
//         if ($path !== $uri) {
//             // Cek apakah ada pattern {} di path
//             if (preg_match('/\{([^\}]*)\}/', $path)) {
//                 // Buat path route dan request tanpa parameter terakhir (biasanya ID atau slug)
//                 $path_route = rtrim(preg_replace('/\{([^\}]*)\}/', '', $path), '/');
//                 $path_request = implode('/', array_slice(explode('/', ltrim($uri, '/')), 0, -1));
//                 $path_request = '/' . $path_request;

//                 // Argument yang ada di URL, seperti ID atau slug
//                 $argument = explode('/', $uri);
//                 $argument = end($argument);

//                 // Jika path route dan request cocok
//                 if ($path_route === $path_request) {
//                     return self::handleCallable($callable, $request, $argument);
//                 }
//             }
//             return false;
//         }

//         // Jika path tanpa pattern cocok, panggil callable
//         return self::handleCallable($callable, $request);
//     }

//     private static function handleCallable($callable, $request, $argument = null)
//     {
//         // Jika callable adalah function
//         if (is_callable($callable)) {
//             return $argument ? call_user_func($callable, $request, $argument) : call_user_func($callable, $request);
//         }

//         // Jika callable adalah array [ClassName, methodName]
//         if (is_array($callable)) {

//             list($class, $method) = $callable;

//             // Cek apakah class dan method valid
//             if (class_exists($class) && method_exists($class, $method)) {
//                 $controller = new $class();
//                 return $argument ? call_user_func([$controller, $method], $request, $argument) : call_user_func([$controller, $method], $request);
//             }
//         }

//         // Jika callable tidak valid, return null
//         return null;
//     }


//     public static function get(string $path, $callable = 'callable', $method = 'index')
//     {
//         return self::handle('GET', $path, $callable, $method);
//     }

//     public static function post(string $path, $callable = 'callable', $method = 'index')
//     {
//         return self::handle('POST', $path, $callable, $method);
//     }

//     public static function put(string $path, $callable = 'callable', $method = 'index')
//     {
//         return self::handle('PUT', $path, $callable, $method);
//     }

//     public static function delete(string $path, $callable = 'callable', $method = 'index')
//     {
//         return self::handle('DELETE', $path, $callable, $method);
//     }


//     public static function is($route = null)
//     {
//         $uri = !empty($route) ? ($route === parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ? true : false) : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//         return $uri;
//     }

//     public static function query(string $key = null)
//     {
//         $query_str = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
//         if (empty($query_str)) return null;

//         parse_str($query_str, $query);
//         return !empty($key) ? (!empty($query[$key]) ? $query[$key] : null) : $query;
//     }
// }


class Route
{
    public static function handle(string $request_type = 'GET', string $path = '/', $callable = [])
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Jika request method tidak sesuai, return false
        if ($request_type != $_SERVER['REQUEST_METHOD']) {
            return false;
        }

        // Inisialisasi request
        $request = new Request();

        // Deteksi apakah URL yang diminta cocok dengan route
        $parameters = [];
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
                // Simpan parameter dengan nama yang sesuai
                $parameters[$matches[1]] = $uriSegments[$index];
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
            list($class, $method) = $callable;

            // Cek apakah class dan method valid
            if (class_exists($class) && method_exists($class, $method)) {
                $controller = new $class();
                return call_user_func([$controller, $method], $request, ...array_values($parameters));
            }
        }

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
