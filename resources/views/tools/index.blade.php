<!-- timestampConversion -->
@extends('tools.layouts')

@section('title')
    工具集
@endsection

@section('content_title')
    <h2 class="text-center">工具集</h2>
@endsection

@section('content')
    <div class="container" style="">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary" href="/toolsTimestampConversion">时间戳转换</a>
            </div>
            <div class="col-md-3">
                <a class="btn btn-secondary" href="/toolsJsonConversion">Json-PHP转换</a>
            </div>
            <div class="col-md-3">
                <a class="btn btn-secondary" href="/toolsBase64Conversion">Base64编码转换</a>
            </div>
            <div class="col-md-3">
                <a class="btn btn-secondary" href="/toolsUrlEncodeConversion">UrlEncode编码转换</a>
            </div>
        </div>
    </div>
@endsection