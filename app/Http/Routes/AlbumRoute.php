<?php

/**
 * 相册路由
 */
$router->group(['prefix' => 'album'], function($router){
    $router->get('ajaxIndex', 'AlbumController@ajaxIndex');
    $router->post('upload', 'AlbumController@upload');
    $router->get('/{id}/addPhoto', 'AlbumController@addPhoto');
    $router->get('/{id}/photoShow', 'AlbumController@photoShow');
    $router->post('/{id}/addSubmitPhoto', 'AlbumController@addSubmitPhoto');
    $router->get('/{id}/mark/{status}', 'AlbumController@mark')
        ->where([
            'id' => '[0-9]+',
            'status' => config('admin.global.status.trash').'|'.
                config('admin.global.status.audit').'|'.
                config('admin.global.status.active')
        ]);
});

$router->resource('album', 'AlbumController');