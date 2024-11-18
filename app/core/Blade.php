<?php

namespace Warkim\core;

use eftec\bladeone\BladeOne;

class Blade
{
    protected $blade;

    public function __construct(string $views_path, $mode = null)
    {
        if (empty($mode)) $mode = BladeOne::MODE_DEBUG;
        $cache = __DIR__ . '/../storage/cache/';
        if (!is_dir($cache)) mkdir($cache, 0755, true);

        $this->blade = new BladeOne($views_path, $cache, $mode);
    }

    public function render(string $view, array $data = [])
    {
        return $this->blade->run($view, $data);
    }
}
