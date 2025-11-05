<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TechStore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'techstore.facade';
    }
}