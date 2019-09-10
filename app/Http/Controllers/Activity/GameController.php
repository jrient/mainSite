<?php
/**
 * User: jrient
 * Date: 2019/7/13
 * Time: 18:20
 */

namespace App\Http\Controllers\Activity;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class GameController extends CommonController
{

    public function jinShan()
    {
        echo "<div style='width:1500px;'>";

        echo "<div style='float: left; margin: 10px;'>";
        echo '<h2>统计基数 前20个</h2>';
        $this->test('jinshan_test_logs');
        echo "</div>";

        echo "<div style='float: left; margin: 10px;'>";
        echo '<h2>统计基数 前10个</h2>';
        $this->test('jinshan_test_logs2');
        echo "</div>";

        echo "<div style='float: left; margin: 10px;'>";
        echo '<h2>统计基数 前50个</h2>';
        $this->test('jinshan_test_logs3');
        echo "</div>";

        echo "<div style='float: left; margin: 10px;'>";
        echo '<h2>统计基数 前100个</h2>';
        $this->test('jinshan_test_logs4');
        echo "</div>";

        echo "</div>";

        echo '<script>setTimeout(function(){window.location.reload()}, 60000);</script>';
    }

    private function test($table)
    {
        DB::connection('mysql_service')->getPdo();
        $data = DB::table($table)->orderBy('id', 'desc')->limit(31)->get();
        echo '<table border="1">';
        echo '<tr>
            <th>期</th>
            <th>猜测</th>
            <th style="width:60px;">标</th>
            <th style="width:60px;">状态</th>
        </tr>';
        foreach ($data as $item) {
            $per = json_decode($item->per, true);
            asort($per);
            $testListFull = implode(',', array_keys($per));

            echo '<tr>';
            echo "<td>$item->id</td>";
            $testList = json_decode($item->i_num, true);
            sort($testList);
            $testList = implode(',', $testList);
            echo "<td title='$testListFull'>$testList</td>";
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
    }
}