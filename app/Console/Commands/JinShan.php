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
        echo 'run';
        $this->getAll();

        $this->compare();

        $this->test(15);
    }

    private function compare()
    {
        $thisInfo = DB::table('jinshan_test_logs')->where(['status' => 0])->orderBy('id', 'desc')->first();
        if (empty($thisInfo)) {
            return false;
        }

        $winNum = DB::table('jinshan_win_logs')->where(['id' => $thisInfo->id])->value('i2');
        if (!empty($winNum)) {
            $testList = json_decode($thisInfo->i_num, true);
            $status = 2;
            if (in_array($winNum,$testList)) {
                $status = 1;
            }
            DB::table('jinshan_test_logs')->where(['id' => $thisInfo->id])->update(['win_num' => $winNum, 'status' => $status]);
        }
    }

    private function test($num = 10, $field = 'i_num')
    {
        $thisMaxId = DB::table('jinshan_win_logs')->max('id');
        $lastKey = DB::table('jinshan_win_logs')->where('id', $thisMaxId)->value('i2');
        $nowId = $thisMaxId+1;

        $result = DB::table('jinshan_win_logs')
            ->select(DB::raw('count(*) num, i2 item'))
            ->orderBy('item')
            ->groupBy('item')
            ->limit($num)
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
        unset($perData['#'.$lastKey]);
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
            'i_num' => '',
            'per' => json_encode($perDataLog),
            'win_num' => '',
            'status' => 0
        ];
        $saveData[$field] = json_encode($testMap);
        DB::table('jinshan_test_logs')->insert($saveData);
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
                var_dump(count($data));
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
