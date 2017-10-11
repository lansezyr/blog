@extends('layouts.admin')
@section('css')
    <link href="{{asset('backend/plugins/bootstrap-fileinput-4-3-6/css/fileinput.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('backend/plugins/bootstrap-select/css/bootstrap-select.min.css')}}">
@endsection
@section('content')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('admin')}}">{!! trans('labels.breadcrumb.home') !!}</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{url('admin/album')}}">{!! trans('labels.breadcrumb.albumList') !!}</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <span>{!! trans('labels.breadcrumb.albumAddPhoto') !!}</span>
            </li>
        </ul>
    </div>
    <div class="row margin-top-40">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-haze">
                        <i class="icon-settings font-green-haze"></i>
                        <span class="caption-subject bold uppercase">{!! trans('labels.breadcrumb.albumAddPhoto') !!}-->{{$album['name']}}</span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    @if (isset($errors) && count($errors) > 0 )
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            @foreach($errors->all() as $error)
                                <span class="help-block"><strong>{{ $error }}</strong></span>
                            @endforeach
                        </div>
                    @endif
                    <form role="form" class="form-horizontal" method="POST" action="#" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-body">
                            <input type="hidden" value="{{$id}}" name="album_id" id="album_id" >
                            <input type="hidden" value="{{$userId}}" name="user_id" id="user_id" >
                            <div class="form-group">
                                <input id="image_url" name="image_url" type="file" multiple class="file" data-overwrite-initial="false" data-min-file-count="1">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('backend/plugins/bootstrap-fileinput-4-3-6/js/fileinput.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/bootstrap-fileinput-4-3-6/js/locales/zh.js')}}" type="text/javascript"></script>
    <script>
        $("#image_url").fileinput({
            uploadUrl: "{{url('admin/album/'.$id.'/addSubmitPhoto')}}", // you must set a valid URL here else you will get an error
            language : 'zh',
            allowedFileExtensions : ['jpg', 'png','gif', 'jpeg'],
            overwriteInitial: false,
            maxFileSize: 10000,
            maxFilesNum: 10,
            //allowedFileTypes: ['image', 'video', 'flash'],
            slugCallback: function(filename) {
                console.log(filename);
                return filename.replace('(', '_').replace(']', '_');
            },
            uploadExtraData:function (previewId, index) {
                //向后台传递id作为额外参数，是后台可以根据id修改对应的图片地址。
                var obj = {};
                obj.album_id = $('#album_id').val();
                obj._token = $("input[name='_token']").val();
                obj.user_id = $('#user_id').val();
                return obj;
            }
        }).on("filebatchuploadsuccess", function(event, data) {
            console.log(data);
            window.location.href = "{{url('admin/album')}}";
        }).on("fileuploaded", function(event, data) {
            console.log(data);
            window.location.href = "{{url('admin/album')}}";
        });
    </script>
@endsection