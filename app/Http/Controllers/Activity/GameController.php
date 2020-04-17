<?php
/**
 * User: jrient
 * Date: 2019/7/13
 * Time: 18:20
 */

namespace App\Http\Controllers\Activity;

use Illuminate\Support\Facades\DB;

class GameController extends CommonController
{

    public function jinShan()
    {
        DB::connection('mysql_service')->getPdo();
        $data = DB::table('jinshan_test_logs')->orderBy('id', 'desc')->limit(20)->get();
        echo '<table border="1">';
        echo '<tr><th>期</th><th>猜测</th><th style="width:60px;">标</th><th style="width:60px;">状态</th></tr>';
        foreach ($data as $item) {
            echo '<tr>';
            echo "<td>$item->id</td>";
            $testList = json_decode($item->i_num, true);
//            sort($testList);
            $testList = implode(',', $testList);
            echo "<td>$testList</td>";
            echo "<td style='text-center'>$item->win_num</td>";
            if ($item->status == 1) {
                $status = '中';
            } elseif ($item->status == 2) {
                $status = '挂';
            } else {
                $status = '';
            }
            echo "<td>$status</td>";
            echo '</tr>';
        }
        echo '</table>';

        echo '<script>setTimeout(function(){window.location.reload()}, 60000);</script>';
    }
}