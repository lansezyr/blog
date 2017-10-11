@extends('layouts.index')
@section('title')
    <title>照片 - x胖子博客</title>
@endsection
@section('css')
    <link href="{{asset('front/css/photos.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('promo')
    <canvas style="cursor: move;" id="canvas">你的浏览器不支持HTML5画布技术，请使用谷歌浏览器。</canvas>
    <!-- dhteumeuleu nav menu -->
    <div id="nav">
        <input name="nav-switch" id="nav-switch" type="checkbox">
        <label class="label" for="nav-switch">
            <div class="container">
                <div class="nav-on">
                    <ul class="menu">
                        <li class="home"><a href="http://www.webhek.com/">Home</a></li>
                    </ul></div>
                <div class="nav-off">
                    <div id="icon"><div></div><div></div></div>
                    <h1 class="title">HTML5 3D相册</h1>
                </div>
            </div>
        </label>
    </div>
@endsection
@section('rightSide')
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('front/js/album/analytics.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/js/album/ge1doot.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/js/album/imageTransform3D.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/js/album/ga.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/js/album/util.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/js/album/canvas.js')}}"></script>
@endsection