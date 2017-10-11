<?php
/**
 * Created by PhpStorm.
 * User: songzenglin@guazi.com
 * Date: 2017/10/8
 * Time: 下午4:42
 */

namespace App\Http\Requests;


class AlbumRequest extends Request
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
            'id' => 'numeric',
            'name' => 'required|unique:permissions,slug,'.$this->id,
            'description' => 'required',
            //'cover' => 'required',
            'status' => 'required',
            'user_id' =>  'required',
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
            'name'          => trans('labels.album.name'),
            'description'   => trans('labels.album.description'),
            'cover'         => trans('labels.album.cover'),
            'status'        => trans('labels.album.status'),
            'user_id'       => trans('labels.album.user_id'),
        ];
    }
}