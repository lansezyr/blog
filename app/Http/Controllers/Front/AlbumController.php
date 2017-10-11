<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/9
 * Time: 下午7:50
 */

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use AlbumRepository;
use PhotoRepository;

class AlbumController extends Controller
{
    public function show()
    {
      $album = AlbumRepository::getAll();
      return view('front.album.show')->with(compact('album'));
    }

    public function list($id)
    {
        $photoList = PhotoRepository::getPhotoListByAlbumId($id);
        return view('front.album.photos')->with(compact('photoList'));
    }
}