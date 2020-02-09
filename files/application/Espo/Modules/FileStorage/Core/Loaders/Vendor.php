<?php

namespace Espo\Modules\FileStorage\Core\Loaders;

class Vendor
{
    public function load()
    {
        require_once __DIR__.'/vendor/autoload.php';
    }
}
