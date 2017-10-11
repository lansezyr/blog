@extends('layouts.index')
@section('promo')
<div class="promo-block-v1 promo-block-v1-bg-img-v3 fullheight text-center">
    <div class="container vertical-center-aligned">
        <h1 class="promo-block-v1-title wow fadeInUp" data-wow-duration="1s" data-wow-delay="0">Welcome To xPangZi's Blog</h1>
        <p class="promo-block-v1-text margin-b-40 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
            I am a slow walker, but I never walk backwards...
        </p>
    </div>
</div>
<div class="bg-color-sky-light">
    <div class="container-sm">
        <div class="bg-color-white border-1 padding-40 margin-t-o-80">
            <div class="row">
                <div class="col-sm-12">
                    <div class="heading-v1 margin-b-20 text-center">
                        <h2 class="heading-v1-title">Story About Me</h2>
                        <span class="heading-v1-subtitle">碎碎念念的话，伴随着成长</span>
                    </div>

                    <p>时间真的好快，嗖的一下就长成了今天这模样。永远不会像《挪威森林》村上写的那一句话：一直以为十八岁之后是十九岁，十九岁后是十八岁，如此反复。如今说好的十八岁离开好几年了，好宅不代表着我老，如果认真的去做的更好，只是个开始。如今过的日子并不好过的话，完全可以付出更多的努力再来活一次，找到真正想要的自己。</p>
                    <p>执着的去做，不怕舍不得睡觉、玩乐、安逸的时间去拼，要知道现在的痛苦和难受都是以前放弃了太多努力。所以现在要抓紧努力，哪怕需要你花全部精力。去拼，如果没有天分，就用时间去换，走得再慢也不要后退。希望再过几年真的被喊叔叔的年纪，那时候回头感谢一下现在选择拼搏的我。</p>
                    <p>没有天分，就用时间去换......</p>
                </div>
            </div>
        </div>
        <div class="content"></div>
    </div>
</div>

<div class="content-md container" id="pageScroll">
    <div class="heading-v3 text-center">
        <h2 class="heading-v3-title">Great Diary</h2>
        <div class="divider-v3"><div class="divider-v3-element"><i class="divider-v3-icon fa fa-skyatlas"></i></div></div>
        <p class="heading-v3-text">It's the small details that will make a big difference</p>
    </div>
</div>
@endsection
@section('content')
<div class="masonry-grid">
    @if($articles)
    @foreach($articles as $v)
    <div class="masonry-grid-item col-1">
        <article class="blog-grid">
            <div class="blog-grid-box-shadow">
                <div class="blog-grid-content">
                    <h2 class="blog-grid-title-md"><a href="{{url('article/'.$v->id)}}">{{$v->title}}</a></h2>
                    @if($v->img)
                        <div class="starImg">
                            <a href="{{url('article/'.$v->id)}}"><img class="img-responsive margin-b-10" src="{{$v->img}}" alt="{{$v->title}}"></a>
                        </div>
                    @endif
                    <p>{!!$v->intro!!}</p>
                </div>
                <div class="blog-grid-supplemental">
                    <span class="blog-grid-supplemental-title">
                        <a class="blog-grid-supplemental-category" href="{{url('cate/'.$v->category_id)}}"><i class="fa fa-leaf"></i> {!!$cate[$v->category_id]!!}</a>
                         -  <i class="fa fa-clock-o"></i> {{$v->created_at}}
                    </span>
                    <span class="blog-grid-supplemental-title pull-right">
                        <i class="fa fa-eye"></i>
                        {{\Illuminate\Support\Facades\Redis::get(config('admin.global.redis.article_view').$v->id)}}
                    </span>
                </div>
            </div>
        </article>
    </div>
    @endforeach
    @endif
</div>
{!! $articles->fragment('pageScroll')->links() !!}
@endsection