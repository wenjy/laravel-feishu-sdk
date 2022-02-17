<?php

namespace LaravelFeiShu\Controllers;

use Barryvdh\Debugbar\LaravelDebugbar;

class Controller
{
    public function __construct()
    {
        if (class_exists(LaravelDebugbar::class)) {
            resolve(LaravelDebugbar::class)->disable();
        }
    }
}
