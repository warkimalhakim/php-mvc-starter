<?php

use Warkim\core\Blade;
use eftec\bladeone\BladeOne;

function view(string $view_path, $data = null)
{
    $path = preg_replace("/\./", "/", $view_path);
    $path = '/' . $path;

    // Ekstrak data jika diberikan
    if (!empty($data) && is_array($data)) {
        extract($data);
    }

    // Tentukan path untuk kedua ekstensi
    $view_path          = __DIR__ . '/../views/';
    $file_path_php      = __DIR__ . '/../views/' .   $path . '.php';
    $file_path_blade    = __DIR__ . '/../views/' . $path . '.blade.php';

    $application_mode = config('APP_MODE') ? config('APP_MODE') : 'development';
    $mode = BladeOne::MODE_DEBUG;

    if ($application_mode == 'production') {
        $mode = BladeOne::MODE_FAST;
    } elseif ($application_mode == 'debug') {
        $mode = BladeOne::MODE_SLOW;
    } elseif ($application_mode == 'development') {
        $mode = BladeOne::MODE_DEBUG;
    } else {
        $mode = BladeOne::MODE_DEBUG;
    }

    // JIKA BLADE TEMPLATE TIDAK DIAKTIFKAN (FALSE) DI .ENV 
    if (config('BLADE_TEMPLATE') == 'false' || config('BLADE_TEMPLATE') === false) {

        // Periksa file mana yang ada
        if (file_exists($file_path_php)) {

            // PHP NATIVE TEMPLATE
            require_once $file_path_php;

            // 
        } else {
            // Jika tidak ada file ditemukan
            return false;
        }


        // 
    } else {


        // Periksa file mana yang ada
        if (file_exists($file_path_php)) {

            // PHP NATIVE TEMPLATE
            require_once $file_path_php;

            // 
        } elseif (file_exists($file_path_blade)) {

            // BLADE TEMPLATE ENGINE
            $blade = new Blade($view_path, $mode);

            try {
                echo $blade->render($path . '.blade.php', $data);
            } catch (Exception $e) {
                echo "Error found " . $e->getMessage() . "<br>" . $e->getTraceAsString();
            }

            // 
        } else {
            // Jika tidak ada file ditemukan
            echo "TIDAK DITEMUKAN";
            return false;
        }
    }
}
