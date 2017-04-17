<?php

namespace App;

use Illuminate\Support\Facades\Facade;

class CustomHelperFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return 'CustomHelper';
    }
}