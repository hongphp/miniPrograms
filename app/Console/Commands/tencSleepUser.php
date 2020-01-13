<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class tencSleepUser extends Command {
    protected $signature = 'sleep_user:upload';

    /**
     * The console command description.
     * 执行命令：php artisan redisLog:delete
     * 注意:不支持多进程
     * @var string
     */
    protected $description = '腾讯新闻sleep用户入库';

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
        ini_set('memory_limit','80M');
        set_time_limit(0);
        ignore_user_abort();
	date_default_timezone_set('Asia/Shanghai');
        $redis = Redis::connection();
        $date = date('Y-m-d',time()-3600*24*4);
        $redis->lpush('doLog',date('Y-m-d H:i:s',time()));
        $page = 1;
        do {
            $res = http_request("127.0.0.1/index/index/qqNews?Date=" . $date . "&page=" . $page);
            if($res==false) {

                $redis->lpush('txErrorlog',$date."第".$page."页");
                echo'error';
            }
            $page++;
        }while($res==='1');
        if($res == 'ok') {
            $redis->lpush('txsucesslog',$date."第".$page."页");
        }
        else {
            $redis->lpush('txErrorlog',$res);
        }
        unset($page,$res);
        //去重
        do{$all = DB::select(' SELECT	max(id) as mid	FROM silence_user where report="'.$date.'" GROUP BY	imei HAVING count(imei)>1');
            foreach ($all as $v) {
                DB::table('silence_user')->where('id',$v->mid)->delete();
            }
        }while(count($all)>0);

    }
}
