<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/6
 * Time: 上午9:34
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use AlbumRepository;
use PhotoRepository;
use App\Http\Requests\AlbumRequest;
use App\Http\Requests\PhotoRequest;

class AlbumController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('checkPermission:'.config('admin.permissions.album.list'), ['only' => ['index', 'ajaxIndex']]);
        $this->middleware('checkPermission:'.config('admin.permissions.album.create'), ['only' => ['create', 'store']]);
    }

    public function index()
    {
        return view('admin.album.list');
    }

    public function ajaxIndex()
    {
        $data = AlbumRepository::ajaxIndex();
        return response()->json($data);
    }

    /**
     * 添加相册
     * @author 胖子
     * @return [type]                   [description]
     */
    public function create()
    {
        $userId = $this->userId;
        return view('admin.album.create')->with(compact('userId'));
    }

    /**
     * 添加文章
     * @date   2016-05-06
     * @author 胖子
     * @param  AlbumRequest $request [description]
     * @return [type]                     [description]
     */
    public function store(AlbumRequest $request)
    {
        AlbumRepository::store($request);
        return redirect('admin/album');
    }

    /**
     * 修改相册视图
     * @author 胖子
     */
    public function edit($id)
    {
        $album = AlbumRepository::edit($id);
        return view('admin.album.edit')->with(compact('album'));
    }
    /**
     * 修改相册
     * @author 胖子
     * @date   2016-05-08T11:00:37+0800
     * @param  AlbumRequest           $request [description]
     * @param  [type]                   $id      [description]
     * @return [type]                            [description]
     */
    public function update(AlbumRequest $request,$id)
    {
        AlbumRepository::update($request,$id);
        return redirect('admin/album');
    }

    /**
     * 修改相册状态
     * @author 胖子
     * @date   2016-05-08T11:00:53+0800
     * @param  [type]                   $id     [description]
     * @param  [type]                   $status [description]
     * @return [type]                           [description]
     */
    public function mark($id,$status)
    {
        AlbumRepository::mark($id,$status);
        return redirect('admin/album');
    }

    /**
     * 删除文章
     * @author 胖子
     * @date   2016-05-08T11:01:06+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function destroy($id)
    {
        AlbumRepository::destroy($id);
        return redirect('admin/album');
    }

    /**
     * 相册新增照片
     * @param $id
     * @return [type]
     */
    public function addPhoto($id)
    {
        $userId = $this->userId;
        $album = AlbumRepository::getRow($id);
        return view('admin.album.add_photo')->with(compact('id', 'album','userId'));
    }

    public function addSubmitPhoto(PhotoRequest $request)
    {
        $data = PhotoRepository::addPhoto($request);
        return response()->json($data);
    }

    /**
     * 相册照片展示
     * @param $id
     * @return [type]
     */
    public function photoShow($id)
    {
        $album = AlbumRepository::getRow($id);
        $photos = PhotoRepository::getPhotoListByAlbumId($id);
        return view('admin.album.photos')->with(compact('id', 'album', 'photos'));
    }

}