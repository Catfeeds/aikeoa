<?php namespace Aike\File\Controllers;

use Input;
use DB;
use Aike\Index\Controllers\DefaultController;

class DownloadController extends DefaultController
{
    public function indexAction()
    {
        $rows = DB::table('download')
        ->orderBy('add_time', 'desc')
        ->paginate();

        return $this->display([
            'rows' => $rows,
        ]);
    }

    public function downAction()
    {
        $id = (int) Input::get('id', 0);

        $row = DB::table('download')->where('id', $id)->first();

        if (empty($row)) {
            return $this->error('附件不存在。');
        }

        $name = empty($row['path']) ? 'download/'.$row['name'] : $row['path'].'/'.$row['name'];

        $downfile = upload_path($name);

        if (is_file($downfile)) {
            //打开文件
            $filename = mb_convert_encoding($row['title'], "gbk", "UTF-8");
            $file = fopen($downfile, "r");
            DB::table('download')->where('id', $id)->increment('hits');
            //输入文件标签
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($downfile));
            Header("Content-Disposition: attachment; filename=" . $filename);
            //输出文件内容
            echo fread($file, filesize($downfile));
            fclose($file);
            exit;
        } else {
            return $this->error('附件文件不存在。');
        }
    }

    public function deleteAction()
    {
        $id = (int)Input::get('id');

        $row = DB::table('download')->where('id', $id)->first();

        $name = empty($row['path']) ? 'download/'.$row['name'] : $row['path'].'/'.$row['name'];

        if (empty($row)) {
            return $this->error('附件不存在。');
        }

        if (is_file(upload_path($name))) {
            unlink(upload_path($name));
        }

        DB::table('download')->where('id', $id)->delete();
        
        return $this->success('index', '文件删除成功。');
    }

    public function addAction()
    {
        $attachList = attachment_edit('download', '', 'download');
        return $this->display(array(
            'attachList' => $attachList,
        ));
    }
}
