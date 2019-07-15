<!-- timestampConversion -->
@extends('tools.layouts')

@section('title')
    工具集 - Json-PHP数组转换
@endsection

@section('content_title')
    <h2 class="text-center">Json-PHP数组转换 <a href="/tools"><small>工具集</small></a></h2>
@endsection

@section('content')
    <div class="container" style="">
        <div class="row">
            <div class="col-md-5">
                <textarea rows="25" class="form-control" id="json" placeholder="json"></textarea>
            </div>
            <div class="col-md-1 center-block" style="padding-top: 100px;">
                <button class="btn btn-default btn-block" id="to_right"><span class="oi oi-arrow-thick-right"></span></button>
            </div>
            <div class="col-md-1 center-block" style="padding-top: 100px;">
                <button class="btn btn-default btn-block" id="to_left" disabled><span class="oi oi-arrow-thick-left"></span></button>
            </div>
            <div class="col-md-5">
                <textarea rows="25" class="form-control" id="php" placeholder="php"></textarea>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#to_right').click(function(){
            var content = $('#json').val();
            $.ajax({
                'url' : '/toolsJsonConversionAjax',
                'type': 'post',
                'data': {
                    'content' : content,
                    'type': 'json'
                },
                'success': function(res) {
                    if (res.status == '200') {
                        $('#php').val(res.data['content']);
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
        $('#to_left').click(function(){
            var content = $('#php').val();
            $.ajax({
                'url' : '/toolsJsonConversionAjax',
                'type': 'post',
                'data': {
                    'content' : content,
                    'type': 'php'
                },
                'success': function(res) {
                    if (res.status == '200') {
                        $('#json').val(res.data['content']);
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    </script>
@endsection