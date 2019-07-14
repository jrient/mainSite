<?php
/**
 * User: jrient
 * Date: 2019/7/13
 * Time: 18:20
 */

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class ToolsController extends CommonController
{
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
}