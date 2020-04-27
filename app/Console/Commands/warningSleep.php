<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class sleepUserLoop extends Command {
    protected $signature = 'warning:sleep';

    /**
     * The console command description.
     * 执行命令：php artisan redisLog:delete
     * 注意:不支持多进程
     * @var string
     */
    protected $description = '腾讯新闻sleep用户入库异常预警';

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

        //进度太慢预警
        $redis = Redis::connection();
        $date = $redis->get('silence_date');
        if(strtotime($date)<strtotime('-25days')) {
            http_request('https://sc.ftqq.com/SCU70950Td706ca17997ccd2e1d1d4d39dfba49b25e003627e0913.send?text='.urlencode('主人任务还没开始啊~'),'',[],3);
        }
        //9点未结束则预警
        if(date("H")==9) {
            $date = date("Y-m-d",time()-3600*24*4);
            $num = DB::table('silence_user')->where('report', $date)->count();
            if ($num == 0) {
                http_request('https://sc.ftqq.com/SCU70950Td706ca17997ccd2e1d1d4d39dfba49b25e003627e0913.send?text='.urlencode('主人任务卡住啦~'),'',[],3);
            }
        }


    }
}
