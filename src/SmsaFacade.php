<?php
namespace Smsa;

use Illuminate\Support\Facades\Facade;

class SmsaFacade extends Facade {
    protected static function getFacadeAccessor() {
        return 'Smsa';
    }
}
