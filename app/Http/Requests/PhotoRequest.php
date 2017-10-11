<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/9
 * Time: 下午4:41
 */

namespace App\Http\Requests;


class PhotoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
            'required'  => trans('validation.required'),
            'unique'    => trans('validation.unique'),
            'numeric'   => trans('validation.numeric'),
        ];
    }

    public function attributes()
    {
        return [
            'id'            => trans('labels.id'),
            'album_id'      => trans('labels.photo.album_id'),
            'image_url'     => trans('labels.photo.description'),
            'description'   => trans('labels.photo.description'),
            'position'      => trans('labels.photo.status'),
            'status'        => trans('labels.photo.status'),
            'user_id'       => trans('labels.photo.user_id'),
        ];
    }
}