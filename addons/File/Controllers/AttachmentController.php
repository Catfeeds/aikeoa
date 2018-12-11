<?php namespace Aike\File\Controllers;

use Request;
use Input;
use Validator;
use DB;
use Auth;
use Session;
use URL;

use Aike\Index\Attachment;

use Aike\Index\Controllers\DefaultController;

class AttachmentController extends DefaultController
{
    public $permission = ['preview', 'draft', 'info', 'remove', 'add', 'uploader', 'delete', 'file', 'view'];

    public function __construct()
    {
        $PHPSESSID = Input::get('PHPSESSID');
        if ($PHPSESSID) {
            Session::setId($PHPSESSID);
            Session::start();
        }
        parent::__construct();
    }
    
    /**
     * 添加文件到草稿
     */
    public function draftAction()
    {
        set_time_limit(0);

        $file = Input::file('Filedata');
        $mime_type = $this->setting['upload_type'];
        $validator = Validator::make(
            array('file' => $file),
            array('file' => 'mimes:'.$mime_type)
        );

        if ($validator->passes()) {
            // 上传文件的模块名
            $module = Input::get('module');

            $path = $module.'/'.date('Y/m');

            $upload_path = upload_path().'/'.$path;

            // 扩展名称
            $extension = $file->getClientOriginalExtension();
            // 附件新名字
            $filename = date('dhis_').str_random(4).'.'.$extension;
            $filename = mb_strtolower($filename);

            $uploadSuccess = $file->move($upload_path, $filename);
            if ($uploadSuccess) {
                // 数据库写入
                $draft = new Attachment;
                $draft->name = mb_strtolower($file->getClientOriginalName());
                $draft->path = $path.'/'.$filename;
                $draft->type = $extension;
                $draft->size = $file->getClientSize();
                $draft->save();
                return $draft->id;
            }
            return 0;
        }
        return 0;
    }

    /**
     * 查看文件
     */
    public function infoAction()
    {
        $id = Input::get('id', 0);
        if ($id > 0) {
            $file = Attachment::where('id', $id)->first();
            $path = upload_path($file->path);

            if (is_file($path)) {
                if (in_array($file->type, array('gif', 'jpg', 'bmp', 'png'))) {
                    $response = response(file_get_contents($path));
                    return $response->header('Content-Type', mime_content_type($path));
                } else {
                    return response()->download($path);
                }
            } else {
                return $this->error('文件不存在。');
            }
        }
    }

    public function removeAction()
    {
        $id = Input::get('id', 0);
        if ($id > 0) {
            $row = Attachment::where('id', $id)->first();
            if ($row->path) {
                $file = upload_path($row->path);
                if (is_file($file)) {
                    unlink($file);
                }
                $row->delete();
                return 1;
            }
        }
        return 0;
    }

    public function uploaderAction()
    {
        set_time_limit(0);

        if (Request::method() == 'POST') {
            $upload_type = $this->setting['upload_type'];
            $upload_max  = $this->setting['upload_max']*(1024*1024);

            $gets = Input::get();
            $file = Input::file('file');

            // 上传文件开始
            if ($file->isValid()) {
                $size = $file->getSize();
                $name = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                //$name = $paths['filename'];
                //$extension = strtolower($name);
                //$_FILES['Filedata']['size']

                // 保存附件的表名
                $table = empty($gets['model']) ? 'attachment' : $gets['model'];

                // 获取当前要传的基础路径
                $path = empty($gets['path']) ? date('Y/m') : $gets['path'].date('/Y/m');

                // 附件存放目录
                $upload_path = upload_path($path);
                /*
                if(!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }
                */
                // 附件新名字
                $filename = date('ymdhi').str_random(4).".".$extension;

                if (in_array($extension, explode(',', $upload_type))) {
                    // 移动文件
                    if ($file->move($upload_path, $filename)) {
                        $data = [
                            'title'       => $name,
                            'path'        => $path,
                            'type'        => $extension,
                            'name'        => $filename,
                            'size'        => $size,
                            'add_time'    => time(),
                            'add_user_id' => Auth::id(),
                        ];

                        $insert_id = DB::table($table)->insertGetId($data);
                        $data['id'] = $insert_id;
                        return response()->json($data);
                    }
                }
                return response()->json([]);
            }
        }
        $query = Input::get();
        $SERVER_URL = url("file/attachment/uploader", $query);
        return $this->render([
            'SERVER_URL' => $SERVER_URL,
        ]);
    }

    public function addAction()
    {
        set_time_limit(0);

        $upload_type = $this->setting['upload_type'];
        $upload_max  = $this->setting['upload_max']*(1024*1024);

        $gets = Input::get();

        // 上传文件开始
        if (Input::file('Filedata')->isValid()) {
            $file = Input::file('Filedata');
            $size = $file->getSize();
            $name = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            //$name = $paths['filename'];
            //$extension = strtolower($name);
            //$_FILES['Filedata']['size']

            // 保存附件的表名
            $table = empty($gets['model']) ? 'attachment' : $gets['model'];

            // 获取当前要传的基础路径
            $path = empty($gets['path']) ? date('Y/m') : $gets['path'].date('/Y/m');

            // 附件存放目录
            $upload_path = upload_path($path);
            /*
            if(!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }
            */
            // 附件新名字
            $filename = date('ymdhi').str_random(4).".".$extension;

            if (in_array($extension, explode(',', $upload_type))) {
                // 移动文件
                if ($file->move($upload_path, $filename)) {
                    $insert_id = DB::table($table)->insertGetId([
                        'title' => $name,
                        'path'  => $path,
                        'type'  => $extension,
                        'name'  => $filename,
                        'size'  => $size,
                        'add_time' => time(),
                        'add_user_id' => Auth::id(),
                    ]);
                    return response($insert_id);
                }
            }
            return response('0');
        }
    }

    public function deleteAction()
    {
        $gets = Input::get();
        if ($gets['id'] > 0) {
            $table = empty($gets['model']) ? 'attachment' : $gets['model'];
            $row = DB::table($table)->find($gets['id']);

            if (is_array($row)) {
                $file = upload_path($row['path'].'/'.$row['name']);
                if (is_file($file)) {
                    unlink($file);
                }
                DB::table($table)->where('id', $row['id'])->delete();
                return '1';
            }
        }
        return '0';
    }

    // 预览文件
    public function viewAction()
    {
        $id = (int)Input::get('id');
        $model = Input::get('model');
        $model = empty($model) ? 'attachment' : $model;

        $row = DB::table($model)->where('id', $id)->first();

        if (empty($row)) {
            return $this->error('文件不存在。');
        }

        $image = upload_path($row['path'].'/'.$row['name']);

        if (is_file($image)) {
            // 打开文件
            $data = file_get_contents($image);
            // 输入文件标签
            Header('Content-type:image/'.$row['type']);
            // 输出文件内容
            echo $data;
            exit;
        } else {
            return $this->error('文件不存在。');
        }
    }

    /**
     * 预览文件
     */
    public function previewAction()
    {
        $id = Input::get('id');
        $file = Attachment::where('id', $id)->first();
        $stream = URL::to('uploads').'/'.$file->path.'/'.$file->name;

        if (in_array($file->type, array('jpg', 'gif', 'png'))) {
            $view = 'image';
        } else {
            return $this->error('此格式不支持预览。');
        }
        return $this->display([
            'stream' => $stream,
        ], 'attachment.image');
    }
    
    // 下载文件
    public function fileAction()
    {
        $id    = (int)Input::get('id');
        $model = Input::get('model');
        $model = empty($model) ? 'attachment' : $model;
        $row = DB::table($model)->where('id', $id)->first();

        if (empty($row)) {
            return $this->error('附件不存在。');
        }

        $file = upload_path($row['path'].'/'.$row['name']);

        if (is_file($file)) {
            // 下载文件
            return response()->download($file, $row['title']);
        } else {
            return $this->error('文件不存在。');
        }
    }
}
