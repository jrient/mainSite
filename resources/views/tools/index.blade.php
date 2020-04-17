<!-- timestampConversion -->
@extends('tools.layouts')

@section('title')
    工具集
@endsection

@section('content_title')
    <div style="margin: 10px auto">
        <h1 class="text-center">工具集</h1>
    </div>
@endsection

@section('content')
    <div class="container" style="">
        @foreach ($map as $k =>$item)
            @if ($k%4 === 0)
                <div class="row" style="margin-top: 1rem;">
            @endif
            <div class="col-md-3">
                <a class="btn btn-secondary" href="{{$item['url']}}">{{$item['name']}}</a>
            </div>

            @if ($k%4 === 3 || ($k+1) === $total)
                </div>
            @endif
        @endforeach
    </div>
@endsection
