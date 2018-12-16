<?php namespace Aike\Index;

use Aike\Index\BaseModel;
use Aike\User\User;

class Share extends BaseModel
{
    public $table = 'share';
    
    /**
     * 获取分享数据
     */
    public static function getItemsSourceBy(array $source_type, $user_id)
    {
        $user = User::find($user_id);
        return Share::leftJoin('user', 'share.created_by', '=', 'user.id')
        ->permission('share.receive_id', $user)
        ->whereIn('share.source_type', $source_type)
        ->get(['share.*', 'user.nickname', 'user.username']);
    }

    /**
     * 获取分享数据
     */
    public static function getItemsCreatedBy(array $source_type, $user_id)
    {
        return Share::where('created_by', $user_id)
        ->whereIn('source_type', $source_type)
        ->get();
    }

    /**
     * 添加分享数据
     */
    public function getItemsSourceId($source_type, $source_id)
    {
        return Share::where('source_id', $source_id)
        ->where('source_type', $source_type)
        ->get();
    }

    /**
     * 添加分享数据
     */
    public function getItem($source_type, $source_id)
    {
        return Share::where('source_id', $source_id)
        ->where('source_type', $source_type)
        ->first();
    }

    /**
     * 删除一条分享数据
     */
    public function removeItem($source_type, $source_id)
    {
        return Share::where('source_id', $source_id)
        ->where('source_type', $source_type)
        ->delete();
    }

    /**
     * 添加分享数据
     */
    public function addItem($data)
    {
        if ($data['receive_id'] == '') {
            return;
        }
        if (empty($data['source_id']) || empty($data['source_type'])) {
            return;
        }
        Share::insert($data);
    }

    /**
     * 编辑分享数据
     */
    public function editItem($source_type, $source_id, $data)
    {
        if ($data['receive_id'] == '') {
            // 共享对象为空删除共享记录
            Share::removeItem($source_type, $source_id);
            return;
        }
        if (empty($source_id) || empty($source_type)) {
            return;
        }
        return Share::where('source_id', $source_id)
        ->where('source_type', $source_type)
        ->update($data);
    }
}
