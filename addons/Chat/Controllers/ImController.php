<?php namespace Aike\Index\Controllers;

use Request;
use URL;
use Input;
use DB;
use JWT;
use Log;
use Session;
use Config;

use Aike\User\Department;

class ImController extends Controller
{
    // 接收消息
    protected function startAction()
    {
        $gets = Request::all();

        // 调用方法
        $res = app('Aike\Index\Controllers\ImController')->{$gets["action"]}($gets['data']);
        $res['action'] = $gets["action"];
        return response()->json($res)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function login($data)
    {
        $json = [];
        $json['status'] = false;

        Log::info($data);

        $res = ['message' => '请求参数不正确。'];

        if ($data['token']) {
            try {
                $payload = (array)JWT::decode($data['token'], Config::get('app.key'));
                $user_id = $payload['sub'];
                $res = DB::table('user')->where('id', $user_id)->first(['id as user_id', 'username as login', 'nickname as name']);
                $json['status'] = true;
            } catch (Exception $e) {
                $res = ['message' => $e->getMessage()];
            }
        }
        $json['data'] = $res;
        return $json;
    }

    public function logout()
    {
    }

    public function layimMessage($req)
    {
        $mine = $req['mine'];
        $to   = $req['to'];
        
        $data['id']       = $mine['id'];
        $data['avatar']   = $mine['avatar'];
        $data['username'] = $mine['username'];
        $data['content']  = $mine['content'];

        $data['users'] = [$to['id']];
        $data['type']  = $to['type'];
        
        $json['data']   = $data;
        $json['status'] = true;

        return $json;
    }

    public function listAction()
    {
        $user = auth()->user();

        $mine = [
            'username' => $user['nickname'],
            'id'       => $user['id'],
            'status'   => $user['im_status'],
            'sign'     => '',
            'avatar'   => avatar(),
        ];

        $departments = Department::with(['users' => function ($q) {
            $q->where('group_id', 1)->where('status', 1);
        }])->get();

        $friends = [];
        foreach ($departments as $department) {
            $friend = ['id' => $department['id'], 'groupname' => $department['title']];
            $list = [];
            foreach ($department['users'] as $user) {
                $list[] = [
                    'id'       => $user['id'],
                    'username' => $user['nickname'],
                    'status'   => $user['im_status'],
                    'avatar'   => avatar(96, $user['id'])
                ];
            }
            $friend['list'] = $list;
            $friends[] = $friend;
        }

        $res['data'] = ['mine' => $mine, 'friend' => $friends];
        $res['code'] = 0;
        $res['msg']  = '';

        return response()->json($res)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
