@extends('layouts.index')
@section('title')
    <title>相册 - x胖子博客</title>
@endsection
@section('css')
    <link href="{{asset('front/plugins/highlight/styles/monokai-sublime.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('front/css/album.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('promo')
    <section class="breadcrumbs-v5 bg-position-fixed breadcrumbs-v5-bg-img-v4">
        <div class="container">
            <h2 class="breadcrumbs-v5-title">I am xPangZi</h2>
            <span class="breadcrumbs-v5-subtitle">I am a slow walker, but I never walk backwards...</span>
        </div>
    </section>
    <section class="breadcrumbs-v1">
        <div class="container">
            <h2 class="breadcrumbs-v1-title">相册</h2>
            <ol class="breadcrumbs-v1-links">
                <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
            </ol>
        </div>
    </section>
@endsection
@section('content')
    <div class="masonry-grid">
        @if($album)
            @foreach($album as $v)
                <div class="single_photo" style="float: left;">
                    <ul class="pics">
                        <li>
                            <span style="padding-top: 10px;">{{$v['name']}}</span>
                            <a href="{{url('album/'.$v['id'])}}" title="Photo"><img src="{{$v['cover']}}" alt="{{$v['name']}}"></a>
                        </li>
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
@endsection