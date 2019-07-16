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
        return view('tools.index');
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

    //json
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