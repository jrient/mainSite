<?php
/**
 * User: jrient
 * Date: 2019/7/13
 * Time: 18:20
 */

namespace App\Http\Controllers\Tools;

use App\Http\Components\ApiRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ToolsController extends CommonController
{

    public function index()
    {
        $map = [
            [
                'name' => '时间戳转换',
                'url' => '/toolsTimestampConversion'
            ],
            [
                'name' => 'Json-PHP转换',
                'url' => '/toolsJsonConversion'
            ],
            [
                'name' => 'Base64编码转换',
                'url' => '/toolsBase64Conversion'
            ],
            [
                'name' => 'UrlEncode编码转换',
                'url' => '/toolsUrlEncodeConversion'
            ],
            [
                'name' => 'Url参数解析',
                'url' => '/toolsUrlAnalysis'
            ],
        ];
        return view('tools.index', [
            'map' => $map,
            'total' => count($map)
        ]);
    }

    /**
     *  时间戳转换
     */
    public function timestampConversion()
    {
        $time = time();
        $datetime = date('Y-m-d H:i:s', $time);
        return view('tools.timestampConversion',[
            'timestamp' => $time,
            'datetime' => $datetime
        ]);
    }

    public function jsonConversion()
    {
        return view('tools.jsonConversion');
    }

    public function base64Conversion()
    {
        return view('tools.base64Conversion');
    }

    public function urlEncodeConversion()
    {
        return view('tools.urlEncodeConversion');
    }

    public function urlAnalysis()
    {
        return view('tools.urlAnalysis');
    }

    public function offTime()
    {
	    $lastTime = strtotime(date('Y-m-d 17:30:00'));
        $nowTime = time();
        $offTime = ($lastTime-$nowTime)>0 ? $lastTime - $nowTime : 0;
        $h = floor($offTime/3600);
        $m = ceil(($offTime-$h*3600)/60);
	    echo '<h2>距离下班还有： '.$h.':'.$m.'</h2>';
        echo '<script>setTimeout(function(){window.location.reload();}, 10000)</script>';
    }

    //json=========================================================
    public function urlEncodeConversionAjax()
    {
        $type =  Input::get('type');
        $content = Input::get('content');
        if (empty($type) || empty($content)) {
            ApiRequest::failJsonRequest('empty params');
        }
        if ($type === 'encode') {
            $newContent = urlencode($content);
        } else {
            $newContent = urldecode($content);
        }
        ApiRequest::successJsonRequest(['content' => $newContent]);
    }

    public function base64ConversionAjax()
    {
        $type =  Input::get('type');
        $content = Input::get('content');
        if (empty($type) || empty($content)) {
            ApiRequest::failJsonRequest('empty params');
        }
        if ($type === 'encode') {
            $newContent = base64_encode($content);
        } else {
            $newContent = base64_decode($content);
        }
        ApiRequest::successJsonRequest(['content' => $newContent]);
    }

    public function jsonConversionAjax()
    {
        $type =  Input::get('type');
        $content = Input::get('content');
        if (empty($type) || empty($content)) {
            ApiRequest::failJsonRequest('empty params');
        }
        if  ($type === 'json') {
            $newContent = rtrim(self::formatPhp(json_decode($content, true)),",\n");
        } else {
//            $tmpContent = eval('return '.$content.';');
            $newContent = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        ApiRequest::successJsonRequest(['content' => $newContent]);
    }

    public function urlAnalysisAjax()
    {
        ini_set('opcache.enable', 0);
        $content = Input::get('content');
        if (empty($content)) {
            ApiRequest::failJsonRequest('empty params');
        }
        $newContent = '';
        $content = parse_url($content);
        foreach ($content as $key => $item) {
            if ($key == 'query') {
                $newContent .= 'Query:'."\n\n";
                $queryArray = explode('&', $item);
                foreach ($queryArray as $queryValue) {
                    $explodeData = explode('=', $queryValue);
                    $k = $explodeData[0] ?? '';
                    if (empty($k)) {
                        continue;
                    }
                    $v = $explodeData[1] ?? '';
                    $v = urldecode($v);
                    $newContent .= "{$k} : {$v}";
                    $newContent .= "\n";
                }
            } else {
                $newContent .= "{$key} : {$item}";
            }
            $newContent .= "\n";
        }

        ApiRequest::successJsonRequest(['content' => $newContent]);
    }

    static private function formatPhp($array, $prefix = '')
    {
        $content = '';
        $borderPrefix = $prefix;
        $prefix .= "\t";
        $content .= $borderPrefix.'['."\n";
        if (is_array($array) && !empty($array)) {
            foreach($array as $key => $value) {
                if (empty($value)) {
                    if (!is_array($value)) {
                        $content .= $prefix."'{$key}' => '',"."\n";
                    } else {
                        $content .= $prefix."'{$key}' => [],"."\n";
                    }
                } else {
                    if (!is_array($value)) {
                        $content .= $prefix."'{$key}' => '{$value}',"."\n";
                    } else {
                        $content .= self::formatPhp($value,  $prefix);
                    }
                }
            }
        }
        $content .= $borderPrefix.'],'."\n";
        return $content;
    }
}
