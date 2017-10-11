<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/9
 * Time: 下午4:52
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PhotoFacade extends Facade
{
    protected static function getFacadeAccessor(){
        return 'PhotoRepository';
    }
}