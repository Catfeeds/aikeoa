<?php namespace Aike\Calendar;

use Auth;

use Aike\Index\BaseModel;

class CalendarAttachment extends BaseModel
{
    public $table = 'calendar_attachment';

    public function getFilterId($id)
    {
        $id = is_array($id) ? $id : explode(',', $id);
        return array_filter($id);
    }

    /**
     * 获取当前附件
     * @param $query
     * @param $id 附件编号支持数组和逗号分隔
     * @return array
     */
    public function scopeQueue($query, $id)
    {
        $id = $this->getFilterId($id);
        if (empty($id)) {
            return [];
        }
        $rows = $query->whereIn('id', $id)->where('state', '1')->get();
        return $rows->toArray();
    }

    /**
     * 获取草稿文件
     */
    public function scopeDraft($query, $userId = 0)
    {
        if ($userId == 0) {
            $userId = Auth::id();
        }
        $rows = $query->where('add_user_id', $userId)->where('state', '0')->get();
        return $rows->toArray();
    }

    /**
     * 发布附件
     */
    public function scopePublish($query, $id)
    {
        $id = $this->getFilterId($id);
        if (empty($id)) {
            return false;
        }
        $rows = $query->draft();
        foreach ($rows as $row) {
            $query->where('id', $row['id'])->update(['state'=>1]);
        }
        return true;
    }

    /**
     * 删除附件和文件
     */
    public function scopeRemove($query, $id)
    {
        $id = $this->getFilterId($id);
        if (empty($id)) {
            return 0;
        }
        $model = $query->whereIn('id', $id);
        $rows = $model->get();
        if ($rows->count()) {
            foreach ($rows as $row) {
                // 删除文件
                $file = upload_path($row->path.'/'.$row->name);
                if (is_file($file)) {
                    unlink($file);
                }
            }
            return $model->delete();
        }
        return 0;
    }
}
