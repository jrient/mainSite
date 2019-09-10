<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class JinShan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:jinshan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo ' ==== run'.date('Y-m-d H:i:s')."\n";
        echo "getAll\n";
        $this->getAll();

        echo "test\n";
        $this->test(20, 'jinshan_test_logs');
        echo "test\n";
        $this->test(10, 'jinshan_test_logs2');
        echo "test\n";
        $this->test(50, 'jinshan_test_logs3');
        echo "test\n";
        $this->test(100, 'jinshan_test_logs4');
        echo 'end'.date('Y-m-d H:i:s')."\n";
    }

    private function test($num = 100, $table = 'jinshan_test_logs')
    {
        $thisInfo = DB::table($table)->where(['status' => 0])->orderBy('id', 'desc')->first();
        if (!empty($thisInfo)) {
            $winNum = DB::table('jinshan_win_logs')->where(['id' => $thisInfo->id])->value('i2');
            if (!empty($winNum)) {
                $testList = json_decode($thisInfo->i_num, true);
                $status = 2;
                if (in_array($winNum,$testList)) {
                    $status = 1;
                }
                DB::table($table)->where(['id' => $thisInfo->id])->update(['win_num' => $winNum, 'status' => $status]);
            }
        }

        $thisMaxId = DB::table('jinshan_win_logs')->max('id');
        $nowId = $thisMaxId+1;

        $logInfo = DB::table($table)->where(['id' => $nowId])->value('id');
        if (!empty($logInfo)) {
            echo "have max id $logInfo \n";
            return false;
        }

        $result = DB::table('jinshan_win_logs')
            ->select(DB::raw('count(*) num, i2 item'))
            ->whereBetween('id', [$thisMaxId-$num, $thisMaxId])
            ->orderBy('item')
            ->groupBy('item')
            ->get();
        $total = array_sum(array_column(json_decode(json_encode($result), true), 'num'));
        $data = [];
        $perData = [];
        foreach ($result as $item) {
            $per = sprintf('%.4f', $item->num/$total);
            $data[] = [
                'id' => $item->item,
                'num' => $item->num,
                'per' => $per
            ];
            $key = '#'.$item->item;
            $perData[$key] = $per;
        }

        $perDataLog = $perData;
        //todo 删除上一次中奖的数字
//        unset($perData['#'.$lastKey]);
        $testMap = [];
        asort($perData);
        $i = 0;
        foreach (array_keys($perData) as $item) {
            if ($i < 6) {
                $testMap[] = trim($item, '#');
            }
            $i++;
        }

        $saveData = [
            'id' => $nowId,
            'i_num' => json_encode($testMap),
            'per' => json_encode($perDataLog),
            'win_num' => '',
            'status' => 0
        ];
        DB::table($table)->insert($saveData);
    }

    private function getAll()
    {
        echo 'sleep3';
        sleep(3);
        $thisMaxId = DB::table('jinshan_win_logs')->max('id');
        $flag = 1;
        for($i = 1; $i <1000; $i++) {
            $res = $this->curlData(1000, $i);
            if (!empty($res['list']) && $flag) {
                $data = [];
                foreach ($res['list'] as $item) {
                    if ($item['num'] <= $thisMaxId) {
                        $flag = 0;
                        break;
                    }
                    $iData = [];
                    foreach (explode(',', $item['winData']) as $k =>$ii) {
                        $iData['i'.($k+1)] = $ii;
                    }
                    unset($iData[0]);
                    $iData['id'] = $item['num'];
                    $data[] = $iData;
                }
                echo "maxId:".$thisMaxId."\n";
                echo json_encode(array_column($data, 'id'))."\n";
                $result = DB::table('jinshan_win_logs')->insert($data);
            } else {
                if (empty($res['list'])) {
                    var_dump($res);
                }
                break;
            }
        }
    }

    private function curlData($size = 100, $page = 1)
    {
        $url = 'http://api1.5w7s.cn/api/Platform/GetLotteryDataPage?stid=36&pageIndex='.$page.'&pageSize='.$size;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: '.config('app.JIN_SHAN_AUTHORIZATION'),
            'User-Agent:  Dalvik/2.1.0 (Linux; U; Android 9; MI 8 MIUI/V10.3.6.0.PEACNXM)',
            'Host:  api1.5w7s.cn'
        ]);
        curl_setopt ($ch,CURLOPT_URL, $url);

        $response = curl_exec($ch);

        return json_decode($response, true);
    }
}
