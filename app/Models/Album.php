<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/8
 * Time: 下午3:44
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use ActionAttributeTrait;

    protected $table = 'album';

    protected $fillable = ['id','name','cover','user_id','description','status'];

    private $action;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->action = config('admin.global.album.action');
    }
}