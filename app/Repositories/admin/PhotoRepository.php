<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/9
 * Time: 下午4:51
 */

namespace App\Repositories\admin;

use App\Models\Photo;
use zgldh\QiniuStorage\QiniuStorage;
use Flash;
use App\User;

/**
 * 照片仓库
 * Class PhotoRepository
 * @package App\Repositories\admin
 */
class PhotoRepository
{
    /**
     * 新增照片
     * @param $request
     * @return bool
     */
    public function addPhoto($request)
    {
        $photo = new Photo;
        $data = $request->all();
        //是否上传了照片
        if ($request->hasFile('image_url')) {
            $data['image_url'] = $this->uploadImage($request->file('image_url'));
        }
        //获取position
        $maxPosition = $this->getMaxPosition($data['album_id']);
        $data['position'] = $maxPosition + 1;
        $data['status'] = config('admin.global.status.active');
        if ($photo->fill($data)->save()) {

            Flash::success(trans('alerts.photo.upload_success'));
            return true;
        }
        Flash::error(trans('alerts.photo.upload_error'));
        return false;
    }

    /**
     * 获取最大的positionid
     * @param $albumId
     * @return int
     */
    private function getMaxPosition($albumId)
    {
        $photo = Photo::query()->where('album_id', '=', $albumId)->orderBy('position', 'desc')->get();
        if($photo) {
            $photo = $photo->toArray();
            if($photo) {
                return current($photo)['position'];
            }
        }
        return 0;
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
     * 根据相册id获取照片
     * @param $albumId
     * @return array
     */
    public function getPhotoListByAlbumId($albumId)
    {
        $photo = Photo::query()->where('album_id', '=', $albumId)->orderBy('position', 'desc')->get();
        if($photo) {
            $photo = $photo->toArray();
            if($photo) {
                foreach ($photo as &$v) {
                    $user = User::find($v['user_id']);
                    if($user) {
                        $user = $user->toArray();
                        $v['user_id'] = $user['name'];
                    } else {
                        $v['user_id'] = '-';
                    }
                }
            }
            return $photo;
        }
        return [];
    }
}