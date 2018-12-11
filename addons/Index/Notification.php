<?php namespace Aike\Index;

use Aike\System\Setting;
use DB;
use Yunpian;

class Notification extends BaseModel
{
    public $table = 'notification';

    public $users = [];

    public $setting = [];

    public $errors = [];

    public function __construct($users = [], $content = [], $options = [])
    {
        $this->setting = Setting::pluck('value', 'key');

        if (func_num_args() == 0) {
            return $this;
        }

        $this->users = $users;

        $sms = $mail = $message = $bigAnt = [];

        foreach ($this->users as $user) {
            if ($user['mobile']) {
                $sms[] = $user['mobile'];
            }
            if ($user['email']) {
                $mail[] = $user['email'];
            }
            if ($user['id']) {
                $message[] = $user['id'];
            }
            if ($user['username']) {
                $bigAnt[] = $user['username'];
            }
        }

        if ($options['message']) {
            $this->site($message, $content['subject'], $content['body'], $content['url']);
        }

        if ($options['mail']) {
            $this->mail($mail, $content['subject'], $content['body']);
        }

        if ($options['sms']) {
            $this->sms($sms, $content['subject'], $content['body']);
        }

        if ($options['bigAnt']) {
            $this->bigAnt($bigAnt, $content['subject'], $content['body']);
        }
    }

    /**
     * 站内通知
     */
    public function site($users, $subject, $content, $url)
    {
        if (empty($subject) || empty($content) || empty($users)) {
            return false;
        }

        foreach ($users as $id) {
            DB::table('user_message')->insert([
                'content' => $subject.$content,
                'url'     => $url,
                'read_by' => $id,
            ]);
        }
        return true;
    }

    /**
     * 添加新通知
     */
    public function sms($users, $subject, $content = '')
    {
        if (empty($subject) || empty($users)) {
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

    /**
     * 邮件通知
     */
    public function mail($users, $subject, $content)
    {
        if ($subject == '' || $content == '' || empty($users)) {
            return false;
        }

        $mail = DB::table('mail')->orderBy('sort', 'asc')->first();
        $config = config('mail');
        config([
            'mail' => array_merge($config, [
                'host'        => $mail['smtp'],
                'port'        => $mail['port'],
                'encryption'  => $mail['secure'],
                'username'    => $mail['user'],
                'password'    => $mail['password'],
                'from'        => [
                    'address' => $mail['user'],
                    'name'    => $mail['name'],
                ],
            ])
        ]);

        $data['subject'] = $subject;
        $data['content'] = $content;

        return Mail::send('emails.notification', $data, function ($message) use ($users, $subject) {
            foreach ($users as $user) {
                $message->to($user);
            }
            $message->subject($this->setting['title']);
        });
    }
}
