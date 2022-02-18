<?php

namespace LaravelFeiShu;

use FeiShu\OpenPlatform\Application;
use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * Class Facade.
 */
class Facade extends LaravelFacade
{
    /**
     * 默认为 Server.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'fieshu.open_platform';
    }

    /**
     * @return Application
     */
    public static function openPlatform($name = '')
    {
        return $name ? app('fieshu.open_platform.'.$name) : app('fieshu.open_platform');
    }
}
