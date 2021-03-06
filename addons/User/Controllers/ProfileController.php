<?php namespace Aike\User\Controllers;

use DB;
use Auth;
use Input;
use Hash;
use Request;
use Validator;
use URL;
use File;

use Totp;
use Pinyin;

use App\License;

use Aike\Hr\Hr;
use Aike\User\UserPosition;
use Aike\User\User;

use Aike\Index\Controllers\DefaultController;

class ProfileController extends DefaultController
{
    public $permission = ['index', 'password', 'avatar', 'secret'];

    // 资料修改
    public function indexAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $user = User::find(Auth::id());

            $rules = [];

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v);
            }
            $user->fill($gets);
            $user->save();
            return $this->success('index', '资料修改成功。');
        }

        $t = new Totp();
        $secretURL = $t->getURL(Auth::user()->username, Request::server('HTTP_HOST'), Auth::user()->auth_secret);

        $user = User::find(Auth::id());

        return $this->display([
            'user'      => $user,
            'secretURL' => $secretURL,
        ]);
    }

    /**
     * 更新安全密钥
     *
     */
    public function secretAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $t = new Totp();
            $secretKey = $t->generateSecret();
            $data['auth_secret'] = $secretKey;
            User::where('id', $id)->update($data);
            return $this->json($secretKey, true);
        }
    }

    /* 修改密码 */
    public function passwordAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $user = User::find(Auth::id());

            $rules = [
                'old_password'              => 'required',
                'new_password'              => 'required|confirmed|different:old_password',
                'new_password_confirmation' => 'required|different:old_password|same:new_password'
            ];

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v);
            }

            // 旧密码不正确
            if (Hash::check($gets['old_password'], $user->getAuthPassword()) === false) {
                return $this->back()->withErrors(['old password' => 'old password 不正确。']);
            }

            $user->password = bcrypt($gets['new_password']);
            $user->password_text = $gets['new_password'];
            $user->save();

            return $this->success('password', '密码修改成功。');
        }
        $user = User::find(Auth::id());
        return $this->display([
            'user' => $user,
        ]);
    }

    // 用户头像
    public function avatarAction()
    {
        $gets = Input::all();

        if (Request::method() == 'POST') {

            if (Request::hasFile('image')) {
                $rules = [
                    'image' => 'image',
                ];
                $v = Validator::make($gets, $rules);

                if ($v->fails()) {
                    return $this->back()->withErrors($v);
                }

                $userId = Auth::id();

                $avatar_path = upload_path('avatar');
                File::isDirectory($avatar_path) or File::makeDirectory($avatar_path, 0777, true, true);

                $file = Input::file('image');
                $filename = $userId.'.'.$file->extension();

                if ($file->move($avatar_path, $filename)) {
                    $user = User::find($userId);
                    $user->avatar = $filename;
                    $user->save();
                    return $this->json($filename, true);
                }
            }
        }
        return $this->render();
    }
}
