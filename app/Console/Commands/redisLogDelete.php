<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class redisLogDelete extends Command {
    protected $signature = 'redisLog:delete';

    /**
     * The console command description.
     * 执行命令：php artisan redisLog:delete
     * 注意:不支持多进程
     * @var string
     */
    protected $description = '清理过期redis日志';

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
        $redis = Redis::connection();
        $length = $redis->LLEN('tangchen:request');
        if($length>10000) {
            $redis->LTRIM('tangchen:request',0,10000);
        }
        $length = $redis->LLEN('boss:request');
        if($length>10000) {
            $redis->LTRIM('boss:request',0,10000);
        }

    }
}
