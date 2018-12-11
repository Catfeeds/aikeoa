<?php namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use DB;
use Yunpian;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    protected $subject;
    protected $content;

    public function __construct($users, $subject, $content = '')
    {
        $this->users   = $users;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function handle()
    {
        $users   = $this->users;
        $subject = $this->subject;
        $content = $this->content;

        if (empty($users)) {
            return false;
        }

        // 短信群发一次最大条数
        $users = array_chunk($users, 500);
        foreach ($users as $user) {
            $user = join(',', $user);
            if ($user) {
                // 记录发送结果
                $res = Yunpian::send($user, $subject.$content);
                foreach ($res['data'] as $row) {
                    $data = json_encode([
                        'msg'   => $row['msg'],
                        'code'  => $row['code'],
                        'count' => $row['count'],
                    ], JSON_UNESCAPED_UNICODE);

                    $log = [
                        'content' => $subject.$content,
                        'data'    => $data,
                        'mobile'  => $row['mobile'],
                        'status'  => $row['code'] == 0 ? 1 : 0,
                    ];
                    DB::table('sms_log')->insert($log);
                }
            }
        }
        return true;
    }
}
