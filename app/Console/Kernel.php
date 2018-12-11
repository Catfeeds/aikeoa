<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // */1 * * * * /www/web/php70/bin/php /www/htdocs/shenghua.app/artisan schedule:run --env=production 1>> /dev/null 2>&1

        // 每天早上8:30执行，生日提醒
        // $schedule->command('sms:birthday')->cron('30 8 * * *');

        // 每天早上8:30执行，推送
        // $schedule->command('push:market')->cron('30 8 * * *');

        // 每30分钟执行，用友库存同步
        // $schedule->command('sync:yonyoustock')->cron('*/30 * * * *');

        // 每30分钟执行，清除文档数据
        // $schedule->command('clear:document')->cron('*/30 * * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
