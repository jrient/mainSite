<?php
/**
 * User: jrient
 * Date: 2018/12/24
 * Time: 22:22
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Demo2Controller extends Controller
{
    /**
     * session 的三种方式
     * @param Request $request\
     */
    public function session1(Request $request)
    {
        $key = 'session_test_key';
        $value = 'session_test';
        $default = 'session_default';

        $request->setSession($key, $value);
        $request->getSession($key);

        session()->put($key, $value);
        session()->get($key);

        Session::put($key, $value);
        Session::get($key, $default);
        //可以存入一个数组
        Session::push($key, $value);
        Session::pull($key);
        Session::all();
        Session::has($key);
        //删除单条
        Session::forget($key);
        //删除所有
        Session::flush();
        // 暂存 取出后就消失
        Session::flash($key, $value);
    }

    public function index()
    {
        echo 'index';
    }

    public function show()
    {
        echo 'show';
    }

    public function request1(Request $request)
    {
        $request->input('param_a');
        $request->input('param_a', 'defaultValue');
        $request->all();

        $request->getMethod();
        $request->isMethod('GET');
        $request->ajax();
        $request->is('demo/*');

        $currentUrl = $request->url();
    }

    public function logs()
    {
        Log::info('这是一个info日志');
        Log::warning('这是一个 warming日志');
    }

    public function errors()
    {
        // 输出错误模板 模板文件在 views/errors下面
        abort(500);
    }

    public function cache1()
    {
        $key = 'cache_test1';
        // 写入缓存
        $result1 =Cache::put($key, 'aaa','minutes');
        // 添加到缓存 如果key存在则不进行覆盖 并 返回false
        $result2 = Cache::add($key, 'aaa', 'minutes');
        // 添加永久缓存
        $result3 = Cache::forever($key, 'aaa');

        // 判断缓存key是否存在
        $status1 = Cache::has($key);

        // 获取缓存
        $data1 = Cache::get($key);
        // 获取缓存 并删除缓存
        $data2 = Cache::put($key);

        // 从缓存中删除一个对象
        $status1 = Cache::forget($key);

    }

    public function test1(Request $request)
    {
        $data =[
            'q' => '',
            'p' => '',
            'bs' => '',
            'csor' => '',
            [
                [
                    'q' => '章子欣',
                    't' => 'hs',
                    [
                        'q' => '章子欣',
                        'ts' => '',
                        'src' => '1',
                        'mtype' => '',
                        'ek' => '',
                    ],
                ],
                [
                    'q' => '掌中之物',
                    't' => 'hs',
                    [
                        'q' => '掌中之物',
                        'ts' => '',
                        'src' => '1',
                        'mtype' => '',
                        'ek' => '',
                    ],
                ],
                [
                    'q' => '润典',
                    't' => 'hs',
                    [
                        'q' => '润典',
                        'ts' => '',
                        'src' => '1',
                        'mtype' => '',
                        'ek' => '',
                    ],
                ],
                [
                    'q' => '天猫精灵 蓝牙连接',
                    't' => 'hs',
                    [
                        'q' => '天猫精灵 蓝牙连接',
                        'ts' => '',
                        'src' => '1',
                        'mtype' => '',
                        'ek' => '',
                    ],
                ],
                [
                    'q' => 'tgp该页面暂时无法显示',
                    't' => 'hs',
                    [
                        'q' => 'tgp该页面暂时无法显示',
                        'ts' => '',
                        'src' => '1',
                        'mtype' => '',
                        'ek' => '',
                    ],
                ],
                [
                    'q' => '输入方糖r的ping',
                    't' => 'hs',
                    [
                        'q' => '输入方糖r的ping',
                        'ts' => '',
                        'src' => '1',
                        'mtype' => '',
                        'ek' => '',
                    ],
                ],
            ],
            's' => [],
        ];
    }

    public function section1()
    {
        return view('demo.section1');
    }

    public function view1()
    {
        // 优先使用blade模板
        return view('demo/demo-view', [
            'name' => 'jrient',
            'age' => 20
        ]);
    }

    public function hello()
    {
        echo route('demoHello');
        return 'hello world';
    }

    public function bindParams($id)
    {
        return 'bindParams-'.$id;
    }

    public function info()
    {
        phpinfo();
    }

    public function upload()
    {
        //上传文件
        Storage::disk('upload')->put('filename.jpg', file_get_contents('fileName'));
    }
}