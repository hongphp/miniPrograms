<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class tencSleepUser extends Command {
    protected $signature = 'sleep_user:upload {date}';

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
        $redis = Redis::connection();
        $date = $this->argument('date');
	$num = DB::table('silence_user')->where('report',$date)->select('id')->count();
	var_dump($num); if($num>10000) exit();
	$redis->lpush('doLog',date('Y-m-d H:i:s',time()));
        $page = 1;
        do {
            $res = http_request("183.237.68.98:9000/index/index/qqNews?Date=" . $date . "&page=" . $page);
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
	    $p = $res?$res:$page;
            $redis->lpush('txErrorlog',$p);
        }
        $redis->set('teniid',0);
        unset($page,$res);
        //去重
        do{$all = DB::select(' SELECT	max(id) as mid	FROM silence_user where report="'.$date.'" GROUP BY	imei HAVING count(imei)>1');
            foreach ($all as $v) {
                DB::table('silence_user')->where('id',$v->mid)->delete();
            }
        }while(count($all)>0);

    }
}
