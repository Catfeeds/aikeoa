<?php namespace Aike\Chat;

define('TABLE_IM_CHAT', 'im_chat');
define('TABLE_IM_MESSAGE', 'im_message');
define('TABLE_IM_CHATUSER', 'im_chatuser');
define('TABLE_IM_USERMESSAGE', 'im_usermessage');

use DB;

class Chat
{
    /**
     * Reset user status.
     *
     * @param  string $status
     * @access public
     * @return bool
     */
    public function resetUserStatus($status = 'offline')
    {
        DB::table('user')->update(['im_status'=> $status]);
        /*
        $this->dao->update(TABLE_USER)->set('status')->eq($status)->exec();
        return !dao::isError();
        */
    }

    /**
     * Create a system chat.
     *
     * @access public
     * @return bool
     */
    public function createSystemChat()
    {
        $chat = DB::table('im_chat')->where('type', 'system')->first();
        if (!$chat) {
            $id = md5(time(). mt_rand());
            $chat['gid']         = substr($id, 0, 8) . '-' . substr($id, 8, 4) . '-' . substr($id, 12, 4) . '-' . substr($id, 16, 4) . '-' . substr($id, 20, 12);
            $chat['name']        = 'system group';
            $chat['type']        = 'system';
            $chat['createdBy']   = 'system';
            $chat['createdDate'] = date('Y-m-d H:i:s');
            DB::table('im_chat')->insert($chat);
        }
        /*
        $chat = $this->dao->select('*')->from(TABLE_IM_CHAT)->where('type')->eq('system')->fetch();
        if(!$chat)
        {
            $id   = md5(time(). mt_rand());
            $chat = new stdclass();
            $chat->gid         = substr($id, 0, 8) . '-' . substr($id, 8, 4) . '-' . substr($id, 12, 4) . '-' . substr($id, 16, 4) . '-' . substr($id, 20, 12);
            $chat->name        = 'system group';
            $chat->type        = 'system';
            $chat->createdBy   = 'system';
            $chat->createdDate = helper::now();

            $this->dao->insert(TABLE_IM_CHAT)->data($chat)->exec();
        }
        return !dao::isError();
        */
    }

    /**
     * Get signed time.
     *
     * @param  string $account
     * @access public
     * @return string | int
     */
    public function getSignedTime($account = '')
    {
        // $this->app->loadModuleConfig('attend');
        // if(strpos(',all,xuanxuan,', ",{$this->config->attend->signInClient},") === false) return '';

        // $attend = $this->dao->select('*')->from(TABLE_ATTEND)->where('account')->eq($account)->andWhere('`date`')->eq(date('Y-m-d'))->fetch();
        // if($attend) return strtotime("$attend->date $attend->signIn");

        return time();
    }

    /**
     * Foramt user object
     *
     * @param  object   $user
     * @access public
     * @return object
     */
    public function formatUsers($users)
    {
        if (isset($users['id'])) {
            $user = $users;
        } else {
            foreach ($users as $user) {
                $user = $this->formatUsers($user);
            }
            return $users;
        }

        $user['id']     = (int)$user['id'];
        $user['dept']   = (int)$user['dept'];
        $user['avatar'] = '';

        //$user->avatar = !empty($user->avatar) ? commonModel::getSysURL() . $user->avatar : $user->avatar;

        if (isset($user['deleted'])) {
            $user['deleted'] = (int)$user['deleted'];
        }

        return $user;
    }

    /**
     * Get a user.
     *
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function getUserByUserID($userID = 0)
    {
        // avatar
        $user = DB::table('user')
        ->leftJoin('role', 'role.id', '=', 'user.role_id')
        ->leftJoin('department', 'department.id', '=', 'user.department_id')
        ->selectRaw('role.name as role,department.name as dept,user.id, user.username as account, user.nickname as realname, user.im_status as status, IF(user.admin=1,"super","no") as admin, user.gender, user.email, user.mobile')
        ->where('user.id', $userID)
        ->first();

        if ($user) {
            $user = $this->formatUsers($user);

            /*
            $user['id']     = (int)$user['id'];
            $user['dept']   = (int)$user['dept'];
            $user['avatar'] = '';
            */

            //$user->avatar = !empty($user->avatar) ? commonModel::getSysURL() . $user->avatar : $user->avatar;
        }

        /*
        $user = $this->dao->select('id, account, realname, avatar, role, dept, status, admin, gender, email, mobile, phone, site')->from(TABLE_USER)->where('id')->eq($userID)->fetch();
        if($user)
        {
            $user->id     = (int)$user->id;
            $user->dept   = (int)$user->dept;
            $user->avatar = !empty($user->avatar) ? commonModel::getSysURL() . $user->avatar : $user->avatar;
        }*/

        return $user;
    }
    
    /**
     * Get user list.
     *
     * @param  string $status
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getUserList($status = '', $idList = array(), $idAsKey = true)
    {
        // avatar
        $model = DB::table('user')
        ->leftJoin('role', 'role.id', '=', 'user.role_id')
        ->leftJoin('department', 'department.id', '=', 'user.department_id')
        ->selectRaw('
            role.name as role,
            department.name as dept,
            user.id, 
            user.username as account, 
            user.nickname as realname, 
            user.im_status as status, 
            IF(user.admin=1,"super","no") as admin, 
            user.gender, 
            user.email, 
            user.mobile
        ')
        ->where('user.status', 1)

        // 只显示用户
        ->where('user.group_id', 1);

        if ($status && $status == 'online') {
            $model->where('user.im_status', '!=', 'offline');
        }

        if ($status && $status != 'online') {
            $model->where('user.im_status', $status);
        }

        if ($idList) {
            $model->whereIn('user.id', $idList);
        }

        $users = $model->get();
        if ($idAsKey) {
            $users = array_by($users, 'id');
        }

        $users = $this->formatUsers($users);
        /*
        foreach($users as $user)
        {
            $user['id']     = (int)$user['id'];
            $user['dept']   = (int)$user['dept'];
            $user['avatar'] = '';
            //$user->avatar = !empty($user->avatar) ? commonModel::getSysURL() . $user->avatar : $user->avatar;
        }
        */
        return $users;
    }
    
    /**
     * Edit a user.
     *
     * @param  object $user
     * @access public
     * @return object
     */
    public function editUser($user = null)
    {
        if (empty($user['id'])) {
            return null;
        }
        DB::table('user')->where('id', $user['id'])->update($user);
        return $this->getUserByUserID($user['id']);
    }

    /**
     * Get member list by gid.
     *
     * @param  string $gid
     * @access public
     * @return array
     */
    public function getMemberListByGID($gid = '')
    {
        $chat = $this->getByGID($gid);
        if (!$chat) {
            return array();
        }

        if ($chat['type'] == 'system') {
            $memberList = DB::table('user')
            ->where('status', 1)
            ->where('group_id', 1)
            ->pluck('id');

        // $memberList = $this->dao->select('id')->from(TABLE_USER)->where('deleted')->eq('0')->fetchPairs();
        } else {

            /*
            $memberList = $this->dao->select('user as id')
            ->from(TABLE_IM_CHATUSER)
            ->where('quit')->eq('0000-00-00 00:00:00')
            ->beginIF($gid)->andWhere('cgid')->eq($gid)->fi()
            ->fetchPairs();
            */
            
            $model = DB::table(TABLE_IM_CHATUSER)
            ->where('quit', '0000-00-00 00:00:00');
            if ($gid) {
                $model->where('cgid', $gid);
            }
            $memberList = $model->pluck('user');
        }
        
        $members = array();
        foreach ($memberList as $member) {
            $members[] = (int)$member;
        }

        return $members;
    }

    /**
     * Get message list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getMessageList($idList = array(), &$pager = null, $startDate = '')
    {
        $model = DB::table(TABLE_IM_MESSAGE);
        if ($idList) {
            $model->whereIn('id', $idList);
        }
        if ($startDate) {
            $model->where('date', $startDate);
        }
        $model->orderBy('id', 'desc');

        if ($pager) {
            $res = $model->paginate($pager['recPerPage'], ['*'], 'page', $pager['pageID'])->toArray();
            $pager['recTotal'] = $res['total'];
            $messages          = $res['data'];
        } else {
            $messages = $model->get();
        }

        foreach ($messages as &$message) {
            $message['id']    = (int)$message['id'];
            $message['order'] = (int)$message['order'];
            $message['user']  = (int)$message['user'];
            $message['date']  = strtotime($message['date']);
        }
        return $messages;
    }

    /**
     * Get message list by cgid.
     *
     * @param  string $cgid
     * @access public
     * @return array
     */
    public function getMessageListByCGID($cgid = '', &$pager = null, $startDate = '')
    {
        $model = DB::table(TABLE_IM_MESSAGE)
        ->where('cgid', $cgid)
        ->orderBy('id', 'desc');

        if ($startDate) {
            $model->where('date', $startDate);
        }

        if ($pager) {
            $res = $model->paginate($pager['recPerPage'], ['*'], 'page', $pager['pageID'])->toArray();
            $pager['recTotal'] = $res['total'];
            $messages          = $res['data'];
        } else {
            $messages = $model->get();
        }

        foreach ($messages as &$message) {
            $message['id']   = (int)$message['id'];
            $message['user'] = (int)$message['user'];
            $message['date'] = strtotime($message['date']);
        }

        return $messages;
    }

    /**
     * Foramt chat object
     *
     * @param  object   $chat
     * @access public
     * @return object
     */
    public function formatChats($chats)
    {
        if (isset($chats['id'])) {
            $chat = $chats;
        } else {
            foreach ($chats as $chat) {
                $this->formatChats($chat);
            }
            return $chats;
        }

        $chat['id']             = (int)$chat['id'];
        $chat['subject']        = (int)$chat['subject'];
        $chat['public']         = (int)$chat['public'];
        $chat['createdDate']    = strtotime($chat['createdDate']);
        $chat['editedDate']     = $chat['editedDate'] == '0000-00-00 00:00:00' ? 0 : strtotime($chat['editedDate']);
        $chat['lastActiveTime'] = $chat['lastActiveTime'] == '0000-00-00 00:00:00' ? 0 : strtotime($chat['lastActiveTime']);
        $chat['dismissDate']    = $chat['dismissDate'] == '0000-00-00 00:00:00' ? 0 : strtotime($chat['dismissDate']);

        if ($chat['type'] == 'one2one') {
            $chat['name'] = '';
        }

        if (isset($chat['star'])) {
            $chat['star'] = (int)$chat['star'];
        }
        if (isset($chat['hide'])) {
            $chat['hide'] = (int)$chat['hide'];
        }
        if (isset($chat['mute'])) {
            $chat['mute'] = (int)$chat['mute'];
        }

        return $chat;
    }

    /**
     * Get chat list.
     *
     * @param  bool   $public
     * @access public
     * @return array
     */
    public function getList($public = true)
    {
        $model = DB::table(TABLE_IM_CHAT)
        ->where('public', $public);
        if ($public) {
            $model->where('dismissDate', '0000-00-00 00:00:00');
        }
        $chats = $model->get();

        /*
        $chats = $this->dao->select('*')->from(TABLE_IM_CHAT)
            ->where('public')->eq($public)
            ->beginIF($public)->andWhere('dismissDate')->eq('0000-00-00 00:00:00')->fi()
            ->fetchAll();
        */

        $this->formatChats($chats);

        return $chats;
    }

    /**
     * Get chat list by userID.
     *
     * @param  int    $userID
     * @param  bool   $star
     * @access public
     * @return array
     */
    public function getListByUserID($userID = 0, $star = false)
    {
        /*
        $systemChat = $this->dao->select('*, 0 as star, 0 as hide, 0 as mute')
            ->from(TABLE_IM_CHAT)
            ->where('type')->eq('system')
            ->fetchAll();
        */

        $systemChat = DB::table(TABLE_IM_CHAT)
        ->selectRaw('*, 0 as star, 0 as hide, 0 as mute')
        ->where('type', 'system')
        ->get();

        $model = DB::table(TABLE_IM_CHAT.' as t1')->selectRaw('t1.*, t2.star, t2.hide, t2.mute, t2.category')
        ->leftjoin(TABLE_IM_CHATUSER.' as t2', 't1.gid', '=', 't2.cgid')
        ->where('t2.quit', '0000-00-00 00:00:00')
        ->where('t2.user', $userID);
        if ($star) {
            $model->where('t2.star', $star);
        }
        $chats = $model->get();

        /*
        $chats = $this->dao->select('t1.*, t2.star, t2.hide, t2.mute, t2.category')
            ->from(TABLE_IM_CHAT)->alias('t1')
            ->leftjoin(TABLE_IM_CHATUSER)->alias('t2')->on('t1.gid=t2.cgid')
            ->where('t2.quit')->eq('0000-00-00 00:00:00')
            ->andWhere('t2.user')->eq($userID)
            ->beginIF($star)->andWhere('t2.star')->eq($star)->fi()
            ->fetchAll();
        */
        
        $chats = array_merge($systemChat->toArray(), $chats->toArray());

        $this->formatChats($chats);

        return $chats;
    }

    /**
     * Get a chat by gid.
     *
     * @param  string $gid
     * @param  bool   $members
     * @access public
     * @return object
     */
    public function getByGID($gid = '', $members = false)
    {
        $chat = DB::table(TABLE_IM_CHAT)->where('gid', $gid)->first();

        //$chat = $this->dao->select('*')->from(TABLE_IM_CHAT)->where('gid')->eq($gid)->fetch();

        if ($chat) {
            $this->formatChats($chat);

            if ($members) {
                $chat['members'] = $this->getMemberListByGID($gid);
            }
        }

        return $chat;
    }

    /**
     * Get offline messages.
     *
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getOfflineMessages($userID = 0)
    {
        $messages = array();
        $dataList = DB::table(TABLE_IM_USERMESSAGE)->where('user', $userID)->orderBy('level', 'desc')->orderBy('id', 'desc')->get();
        foreach ($dataList as $data) {
            $messages = array_merge($messages, json_decode($data['message']));
        }
        DB::table(TABLE_IM_USERMESSAGE)->where('user', $userID)->delete();
        return $messages;
    }

    /**
     * Create a chat.
     *
     * @param  string $gid
     * @param  string $name
     * @param  string $type
     * @param  array  $members
     * @param  int    $subjectID
     * @param  bool   $public
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function create($gid = '', $name = '', $type = '', $members = array(), $subjectID = 0, $public = false, $userID = 0)
    {
        $user = $this->getUserByUserID($userID);

        $chat = [];
        $chat['gid']         = $gid;
        $chat['name']        = $name;
        $chat['type']        = $type;
        $chat['subject']     = $subjectID;
        $chat['createdBy']   = !empty($user['account']) ? $user['account'] : '';
        $chat['createdDate'] = date('Y-m-d H:i:s');

        if ($public) {
            $chat['public'] = 1;
        }

        DB::table(TABLE_IM_CHAT)->insert($chat);

        /* Add members to chat. */
        foreach ($members as $member) {
            $this->joinChat($gid, $member);
        }

        return $this->getByGID($gid, true);
    }

    /**
     * Update a chat.
     *
     * @param  object $chat
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function update($chat = null, $userID = 0)
    {
        if ($chat) {
            $user = $this->getUserByUserID($userID);
            $chat['editedBy']   = !empty($user['account']) ? $user['account'] : '';
            $chat['editedDate'] = date('Y-m-d H:i:s');

            DB::table(TABLE_IM_CHAT)->where('gid', $chat['gid'])->update($chat);
        }

        /* Return the changed chat. */
        return $this->getByGID($chat['gid'], true);
    }

    /**
     * Set admins of a chat.
     *
     * @param  string $gid
     * @param  array  $admins
     * @param  bool   $isAdmin
     * @access public
     * @return object
     */
    public function setAdmin($gid = '', $admins = array(), $isAdmin = true)
    {
        $chat = $this->getByGID($gid);
        $adminList = explode(',', $chat['admins']);
        foreach ($admins as $admin) {
            if ($isAdmin) {
                $adminList[] = $admin;
            } else {
                $key = array_search($admin, $adminList);
                if ($key) {
                    unset($adminList[$key]);
                }
            }
        }
        $adminList = implode(',', $adminList);
        DB::table(TABLE_IM_CHAT)->where('gid', $gid)->update(['admins' => $adminList]);

        return $this->getByGID($gid, true);
    }

    /**
     * Star or cancel star a chat.
     *
     * @param  string $gid
     * @param  bool   $star
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function starChat($gid = '', $star = true, $userID = 0)
    {
        DB::table(TABLE_IM_CHATUSER)
        ->where('cgid', $gid)
        ->where('user', $userID)
        ->update(['star' => $star]);
            
        return $this->getByGID($gid, true);
    }

    /**
     * Hide or display a chat.
     *
     * @param  string $gid
     * @param  bool   $hide
     * @param  int    $userID
     * @access public
     * @return bool
     */
    public function hideChat($gid = '', $hide = true, $userID = 0)
    {
        DB::table(TABLE_IM_CHATUSER)
        ->where('cgid', $gid)
        ->where('user', $userID)
        ->update(['hide' => $hide]);

        return 1;
    }

    /**
     * Set category for a chat
     *
     * @param  array  $gids
     * @param  string $category
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function categoryChat($gids = array(), $category = '', $userID = 0)
    {
        DB::table(TABLE_IM_CHATUSER)
            ->whereIn('cgid', $gids)
            ->where('user', $userID)
            ->update(['category' => $category]);

        return 1;
    }

    /**
     * Join or quit a chat.
     *
     * @param  string $gid
     * @param  int    $userID
     * @param  bool   $join
     * @access public
     * @return bool
     */
    public function joinChat($gid = '', $userID = 0, $join = true)
    {
        if ($join) {

            /* Join chat. */
            $data = DB::table(TABLE_IM_CHATUSER)
            ->where('cgid', $gid)
            ->where('user', $userID)->first();

            /*
            $data = $this->dao->select('*')
            ->from(TABLE_IM_CHATUSER)
            ->where('cgid', $gid)->eq($gid)
            ->andWhere('user')->eq($userID)->fetch();
            */

            if ($data) {
                /* If user hasn't quit the chat then return. */
                if ($data['quit'] == '0000-00-00 00:00:00') {
                    return true;
                }

                /* If user has quited the chat then update the record. */
                $data = [];
                $data['join'] = date('Y-m-d H:i:s');
                $data['quit'] = '0000-00-00 00:00:00';

                DB::table(TABLE_IM_CHATUSER)->where('cgid', $gid)->where('user', $userID)->update($data);
                /*
                $this->dao->update(TABLE_IM_CHATUSER)->data($data)->where('cgid')->eq($gid)->andWhere('user')->eq($userID)->exec();
                */
                return 1;
                //return !dao::isError();
            }

            /* Create a new record about user's chat info. */
            $data = [];
            $data['cgid'] = $gid;
            $data['user'] = $userID;
            $data['join'] = date('Y-m-d H:i:s');
            $id = DB::table(TABLE_IM_CHATUSER)->insertGetId($data);

            DB::table(TABLE_IM_CHATUSER)->where('id', $id)->update(['order' => $id]);

        //$this->dao->update(TABLE_IM_CHATUSER)->set('`order`')->eq($id)->where('id')->eq($id)->exec();
        } else {
            /* Quit chat. */
            DB::table(TABLE_IM_CHATUSER)->where('cgid', $gid)->where('user', $userID)->update(['quit' => date('Y-m-d H:i:s')]);
            //$this->dao->update(TABLE_IM_CHATUSER)->set('quit')->eq(helper::now())->where('cgid')->eq($gid)->andWhere('user')->eq($userID)->exec();
        }
        return 1;
    }

    /**
     * Create messages.
     *
     * @param  array  $messageList
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function createMessage($messageList = array(), $userID = 0)
    {
        $idList   = array();
        $chatList = array();
        foreach ($messageList as $message) {
            $msg = DB::table('im_message')->where('gid', $message['gid'])->first();
            if ($msg) {
                if ($msg['contentType'] == 'image' || $msg['contentType'] == 'file') {
                    DB::table('im_message')->where('id', $msg['id'])->update(['content' => $message['content']]);
                }
                $idList[] = $msg['id'];
            } elseif (!$msg) {
                if (!(isset($message['user']) && $message['user'])) {
                    $message['user'] = $userID;
                }
                if (!(isset($message['date']) && $message['date'])) {
                    $message['date'] = date('Y-m-d H:i:s');
                }
                
                $idList[] = DB::table('im_message')->insertGetId($message);
            }
            $chatList[$message['cgid']] = $message['cgid'];
        }

        if (empty($idList)) {
            return array();
        }

        DB::table('im_chat')->whereIn('gid', $chatList)->update(['lastActiveTime' => date('Y-m-d H:i:s')]);

        return $this->getMessageList($idList);
    }

    /**
     * Save offline messages.
     *
     * @param  array  $messages
     * @param  array  $users
     * @access public
     * @return bool
     */
    public function saveOfflineMessages($messages = array(), $users = array())
    {
        foreach ($users as $user) {
            $data = [];
            $data['user']    = $user;
            $data['message'] = json_encode($messages);
            DB::table('im_usermessage')->insert($data);
        }
        return 1;
    }

    /**
     * Upgrade xuanxuan.
     *
     * @access public
     * @return void
     */
    public function upgrade()
    {
        $version = $this->getVersion();
        if (version_compare($this->config->xuanxuan->version, $version, '<=')) {
            $output = <<<EOT
<html>
  <head><meta charset='utf-8'></head>
  <body>
    <div style='text-align: center'>
      <h1>{$this->lang->chat->latestVersion}</h1>
    </div>
  </body>
</html>
EOT;
            die($output);
        }

        switch ($version) {
        case '1.0': $this->loadModel('upgrade')->execSQL($this->getUpgradeFile($version));
        // no break
        case '1.1.0':
        default: $this->loadModel('setting')->setItem('system.sys.xuanxuan.global.version', $this->config->xuanxuan->version);
        }

        if (dao::isError()) {
            $error  = dao::getError(true);
            $output = <<<EOT
<html>
  <head><meta charset='utf-8'></head>
  <body>
    <div style='text-align: center'>
      <h1>{$this->lang->chat->upgradeFail}</h1>
      <p>{$error}</p>
    </div>
  </body>
</html>
EOT;
        } else {
            $output = <<<EOT
<html>
  <head><meta charset='utf-8'></head>
  <body>
    <div style='text-align: center'>
      <h1>{$this->lang->chat->upgradeSuccess}</h1>
    </div>
  </body>
</html>
EOT;
        }
        die($output);
    }

    /**
     * Get version of xuanxuan.
     *
     * @access public
     * @return string
     */
    public function getVersion()
    {
        $version = !empty($this->config->xuanxuan->global->version) ? $this->config->xuanxuan->global->version : '1.0';
        return $version;
    }

    /**
     * Get upgrade file.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getUpgradeFile($version = '1.0')
    {
        return $this->app->getBasepath() . 'db' . DS . 'upgradexuanxuan' . $version . '.sql';
    }
}
