<!-- timestampConversion -->
@extends('tools.layouts')

@section('title')
    工具集 - 时间戳转换
@endsection

@section('content_title')
    <h2 class="text-center">时间戳转换 <a href="/tools"><small>工具集</small></a></h2>
@endsection

@section('content')
    <div class="container" style="">
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" value="{{$timestamp}}" id="timestamp">
            </div>
            <div class="col-md-1 text-center">
                <button class="btn btn-default btn-block" id="to_right"><span class="oi oi-arrow-thick-right"></span></button>
            </div>
            <div class="col-md-2 text-center">
                <button class="btn btn-default " id="refresh"><span class="oi oi-reload"></span> </button>
            </div>
            <div class="col-md-1 text-center">
                <button class="btn btn-default btn-block" id="to_left"><span class="oi oi-arrow-thick-left"></span></button>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" value="{{$datetime}}" id="datetime">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#refresh').click(function(){
            location.reload();
        });
        $('#to_right').click(function(){
            var timestamp = $('#timestamp').val();
            var datetime = $.myTime.UnixToDate(timestamp, true);
            $('#datetime').val(datetime);
        });
        $('#to_left').click(function(){
            var datetime = $('#datetime').val();
            var timestamp = $.myTime.DateToUnix(datetime);
            $('#timestamp').val(timestamp);
        });
    </script>
    <script>
        (function($) {
            $.extend({
                myTime: {
                    /**
                     * 当前时间戳
                     * @return <int>        unix时间戳(秒)
                     */
                    CurTime: function(){
                        return Date.parse(new Date())/1000;
                    },
                    /**
                     * 日期 转换为 Unix时间戳
                     * @param <string> 2014-01-01 20:20:20  日期格式
                     * @return <int>        unix时间戳(秒)
                     */
                    DateToUnix: function(string) {
                        var f = string.split(' ', 2);
                        var d = (f[0] ? f[0] : '').split('-', 3);
                        var t = (f[1] ? f[1] : '').split(':', 3);
                        return (new Date(
                                        parseInt(d[0], 10) || null,
                                        (parseInt(d[1], 10) || 1) - 1,
                                        parseInt(d[2], 10) || null,
                                        parseInt(t[0], 10) || null,
                                        parseInt(t[1], 10) || null,
                                        parseInt(t[2], 10) || null
                                )).getTime() / 1000;
                    },
                    /**
                     * 时间戳转换日期
                     * @param <int> unixTime    待时间戳(秒)
                     * @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)
                     * @param <int>  timeZone   时区
                     */
                    UnixToDate: function(unixTime, isFull, timeZone) {
                        function fillZero(num, length = 2) {
                            return (Array(length).join('0') + num).slice(-length);
                        }
                        if (typeof (timeZone) == 'number')
                        {
                            unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
                        }
                        var time = new Date(unixTime * 1000);
                        var ymdhis = "";
                        ymdhis += time.getFullYear() + "-";
                        ymdhis += fillZero(time.getMonth()+1) + "-";
                        ymdhis += fillZero(time.getDate());
                        if (isFull === true)
                        {
                            ymdhis += " " + fillZero(time.getHours()) + ":";
                            ymdhis += fillZero(time.getMinutes()) + ":";
                            ymdhis += fillZero(time.getSeconds());
                        }
                        return ymdhis;
                    }
                }
            });
        })(jQuery);
    </script>
@endsection