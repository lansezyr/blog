<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/8
 * Time: 下午3:43
 */

namespace App\Repositories\admin;

use App\Models\Album;
use zgldh\QiniuStorage\QiniuStorage;
use Flash;
use Cache;
use App\User;

/**
 * 相册仓库
 * Class AlbumRepository
 * @package App\Repositories\admin
 */
class AlbumRepository
{
    /**
     * datatable获取数据
     * @date   2016-05-06
     * @author 胖子
     * @return [type]     [description]
     */
    public function ajaxIndex()
    {
        $draw = request('draw', 1);/*获取请求次数*/
        $start = request('start', config('admin.golbal.list.start')); /*获取开始*/
        $length = request('length', config('admin.golbal.list.length')); ///*获取条数*/

        $search_pattern = request('search.regex', true); /*是否启用模糊搜索*/

        $title = request('name' ,'');
        $status = request('status' ,'');
        $created_at_from = request('created_at_from' ,'');
        $created_at_to = request('created_at_to' ,'');
        $updated_at_from = request('updated_at_from' ,'');
        $updated_at_to = request('updated_at_to' ,'');
        $orders = request('order', []);

        $album = new Album;

        /*文章名称搜索*/
        if($title){
            if($search_pattern){
                $album = $album->where('name', 'like', $title);
            }else{
                $album = $album->where('name', $title);
            }
        }
        /*状态搜索*/
        if ($status) {
            $album = $album->where('status', $status);
        }

        /*文章创建时间搜索*/
        if($created_at_from){
            $album = $album->where('created_at', '>=', getTime($created_at_from));
        }
        if($created_at_to){
            $album = $album->where('created_at', '<=', getTime($created_at_to, false));
        }

        /*文章修改时间搜索*/
        if($updated_at_from){
            $album = $album->where('created_at', '>=', getTime($updated_at_from));
        }
        if($updated_at_to){
            $album = $album->where('created_at', '<=', getTime($updated_at_to, false));
        }

        $count = $album->count();


        if($orders){
            $orderName = request('columns.' . request('order.0.column') . '.name');
            $orderDir = request('order.0.dir');
            $album = $album->orderBy($orderName, $orderDir);
        }

        $album = $album->offset($start)->limit($length);
        $albums = $album->get();

        if ($albums) {
            foreach ($albums as &$v) {
                $user = User::find($v['user_id']);
                if($user) {
                    $user = $user->toArray();
                    $v['user_id'] = $user['name'];
                } else {
                    $v['user_id'] = '-';
                }
                $v['cover'] = "<img src='{$v['cover']}' style='width: 50px;height: 50px;'>";
                $v['actionButton'] = $v->getActionButtonAttribute();
            }
        }

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $albums,
        ];
    }

    /**
     * 添加文章
     * @date   2016-05-06
     * @author 胖子
     * @param  [type]     $request [description]
     * @return [type]              [description]
     */
    public function store($request)
    {
        $album = new Album;
        $data = $request->all();
        //是否上传了相册封面
        if ($request->hasFile('cover')) {
            $data['cover'] = $this->uploadImage($request->file('cover'));
        }
        if ($album->fill($data)->save()) {

            Flash::success(trans('alerts.album.created_success'));
            return true;
        }
        Flash::error(trans('alerts.album.created_error'));
        return false;
    }

    /**
     * 修改视图
     * @author 胖子
     * @date   2016-04-12T16:48:46+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function edit($id)
    {
        $album = Album::query()->find($id);
        if ($album) {
//            $album = $album->toArray();
            return $album;
        }
        abort(404);
    }
    /**
     * 修改相册
     * @author 胖子
     * @param  [type]                   $request [description]
     * @return [type]                            [description]
     */
    public function update($request,$id)
    {
        $album = Album::find($id);
        if ($album) {
            $data = $request->all();
            //是否上传了相册封面
            if ($request->hasFile('cover')) {
                //判断之前是否有封面,有则删掉之前的封面
                if ($album->cover) {
                    $disk = QiniuStorage::disk('qiniu');
                    $disk->delete(substr($album->cover, strpos($album->cover,config('admin.global.imagePath'))));
                }
                $data['cover'] = $this->uploadImage($request->file('cover'));
            }
            if ($album->fill($data)->save()) {
                return true;
            }
            Flash::error(trans('alerts.album.updated_error'));
            return false;
        }
        abort(404);
    }

    /**
     * 修改文章状态
     * @author 胖子
     * @date   2016-04-13T09:35:34+0800
     * @param  [type]                   $id     [description]
     * @param  [type]                   $status [description]
     * @return [type]                           [description]
     */
    public function mark($id,$status)
    {
        $album = Album::find($id);
        if ($album) {
            $album->status = $status;
            if ($album->save()) {
                Flash::success(trans('alerts.album.updated_success'));
                return true;
            }
            Flash::error(trans('alerts.album.updated_error'));
            return false;
        }
        abort(404);
    }

    public function destroy($id)
    {
        $isDelete = Album::destroy($id);
        if ($isDelete) {
            Flash::success(trans('alerts.album.deleted_success'));
            return true;
        }
        Flash::error(trans('alerts.album.deleted_error'));
        return false;
    }

    /**
     * 上传图片到七牛
     * @author 胖子
     * @date   2016-05-07T11:05:27+0800
     * @param  [type]                   $request [description]
     * @return [type]                            [description]
     */
    private function uploadImage($file)
    {
        $disk = QiniuStorage::disk('qiniu');
        $fileName = md5($file->getClientOriginalName().time().rand()).'.'.$file->getClientOriginalExtension();
        $bool = $disk->put(config('admin.global.imagePath').$fileName,file_get_contents($file->getRealPath()));
        if ($bool) {
            $path = $disk->downloadUrl(config('admin.global.imagePath').$fileName);
            return $path;
        }
        return '';
    }

    /**
     * 获取一条数据
     * @param $id
     * @return array|mixed
     */
    public function getRow($id)
    {
        $album = Album::find($id);
        if ($album) {
            $album = $album->toArray();
           return $album;
        }
        abort(404);
    }

    /**
     * 获取所有相册
     */
    public function getAll()
    {
        $albums = Album::all();
        if ($albums) {
            $albums = $albums->toArray();
            return $albums;
        }
        abort(404);
    }
}