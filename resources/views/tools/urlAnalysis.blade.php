<!-- timestampConversion -->
@extends('tools.layouts')

@section('title')
    工具集 - UrlEncode编码转换
@endsection

@section('content_title')
    <h2 class="text-center">UrlEncode编码转换 <a href="/tools"><small>工具集</small></a></h2>
@endsection

@section('content')
    <div class="container" style="">
        <div class="row">
            <div class="col-md-5">
                <textarea rows="25" class="form-control" id="left" placeholder="编码前"></textarea>
            </div>
            <div class="col-md-1 center-block" style="padding-top: 100px;">
                <button class="btn btn-default btn-block" id="to_right"><span class="oi oi-arrow-thick-right"></span></button>
            </div>
            <div class="col-md-1 center-block" style="padding-top: 100px;">
                <button class="btn btn-default btn-block" id="to_left"><span class="oi oi-arrow-thick-left"></span></button>
            </div>
            <div class="col-md-5">
                <textarea rows="25" class="form-control" id="right" placeholder="编码后"></textarea>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#to_right').click(function(){
            var content = $('#left').val();
            $.ajax({
                'url' : '/toolsUrlEncodeConversionAjax',
                'type': 'post',
                'data': {
                    'content' : content,
                    'type': 'encode'
                },
                'success': function(res) {
                    if (res.status == '200') {
                        $('#right').val(res.data['content']);
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
        $('#to_left').click(function(){
            var content = $('#right').val();
            $.ajax({
                'url' : '/toolsUrlEncodeConversionAjax',
                'type': 'post',
                'data': {
                    'content' : content,
                    'type': 'decode'
                },
                'success': function(res) {
                    if (res.status == '200') {
                        $('#left').val(res.data['content']);
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    </script>
@endsection