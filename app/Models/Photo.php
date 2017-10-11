<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/9
 * Time: 下午4:21
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use ActionAttributeTrait;

    protected $table = 'photo';

    protected $fillable = ['id','album_id','image_url','position','user_id','description','status'];

    private $action;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->action = config('admin.global.photo.action');
    }
}