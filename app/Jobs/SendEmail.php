<?php namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mail;
use DB;
use Aike\User\User;
use Aike\System\Setting;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    protected $subject;
    protected $content;
    protected $setting;

    public function __construct($users, $subject, $content = '')
    {
        $this->users   = $users;
        $this->subject = $subject;
        $this->content = $content;
        $this->setting = Setting::pluck('value', 'key');
    }

    public function handle()
    {
        $users   = $this->users;
        $subject = $this->subject;
        $content = $this->content;

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
