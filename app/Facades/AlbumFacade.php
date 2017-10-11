<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/8
 * Time: 下午3:41
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AlbumFacade extends Facade
{
    protected static function getFacadeAccessor(){
        return 'AlbumRepository';
    }
}