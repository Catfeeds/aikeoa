<?php namespace Aike\Index\Controllers;

use Session;
use Input;
use Request;
use Validator;
use DB;

use Aike\Index\Attachment;

class AttachmentController extends DefaultController
{
    public $permission = ['list','view','preview','create','delete','download','show', 'uploader'];

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
     * 上传文件
     */
    public function uploaderAction()
    {
        if (Request::method() == 'POST') {
            $file = Input::file('file');

            $rules = [
                'file' => 'mimes:'.$this->setting['upload_type'],
            ];
            $v = Validator::make(['file' => $file], $rules);

            if ($file->isValid() && $v->passes()) {
                // 获取上传uri第一个目录
                $path = Input::get('path', 'default').date('/Ym/');

                $upload_path = public_path().'/uploads/'.$path;
                
                // 文件后缀名
                $extension = $file->getClientOriginalExtension();

                // 文件新名字
                $filename = date('dhis_').str_random(4).'.'.$extension;
                $filename = mb_strtolower($filename);

                if ($file->move($upload_path, $filename)) {
                    $data = [
                        'name' => mb_strtolower($file->getClientOriginalName()),
                        'path' => $path.$filename,
                        'type' => $extension,
                        'size' => $file->getClientSize(),
                    ];
                    $insertId = Attachment::save($data);
                    $data['id']      = $insertId;
                    $data['success'] = true;
                    return json_encode($data);
                }
            }
        }
        $query = Input::get();
        $SERVER_URL = url("index/attachment/uploader", $query);
        return $this->render([
            'SERVER_URL' => $SERVER_URL,
        ]);
    }

    /**
     * 新建文件
     */
    public function createAction()
    {
        set_time_limit(0);

        $file = Input::file('Filedata');

        $rules = [
            'file' => 'mimes:'.$this->setting['upload_type'],
        ];
        $v = Validator::make(['file' => $file], $rules);

        if ($file->isValid() && $v->passes()) {
            // 获取上传uri第一个目录
            $path = Input::get('path', 'main').date('/Y/m/');

            $upload_path = public_path().'/uploads/'.$path;
            
            // 文件后缀名
            $extension = $file->getClientOriginalExtension();

            // 文件新名字
            $filename = date('dhis_').str_random(4).'.'.$extension;
            $filename = mb_strtolower($filename);

            if ($file->move($upload_path, $filename)) {
                return Attachment::save([
                    'name' => mb_strtolower($file->getClientOriginalName()),
                    'path' => $path.$filename,
                    'type' => $extension,
                    'size' => $file->getClientSize(),
                ]);
            }
        }
        return 0;
    }
    
    /**
     * 获取文件列表
     */
    public function listAction()
    {
        $id = Input::get('id');
        $rows = Attachment::getAll($id);
        return response()->json($rows);
    }
    
    /**
    * 预览文件
    */
    public function showAction()
    {
        $id = Input::get('id');

        $rows = Attachment::getAll($id);

        if (empty($rows)) {
            return $this->error('文件不存在。');
        }

        $image = upload_path($rows[0]['path']);

        if (is_file($image)) {
            Header('Content-type:image/'.$rows[0]['type']);
            return file_get_contents($image);
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
        $file = Attachment::getAll($id)[0];
        $stream = URL::to('uploads').'/'.$file['path'];
        
        if (in_array($file['type'], array('jpg', 'gif', 'png'))) {
            $view = 'image';
        } else {
            return $this->back()->with('error', '此格式不支持预览。');
        }
        return $this->display([
            'stream' => $stream,
        ], 'attachment.view');
    }
    
    /**
     * 下载文件
     */
    public function downloadAction()
    {
        $id = Input::get('id');
        if ($id) {
            $index = DB::table('attachment_index')->where('id', $id)->first();
            if ($index['id']) {
                $row = DB::table('attachment_'.$index['table_id'])->where('id', $id)->first();
            }
            $path = upload_path().'/'.$row['path'];
            return response()->download($path, $row['name']);
        }
    }
    
    public function deleteAction()
    {
        $gets = Input::get();
        if ($gets['id']) {
            Attachment::delete($gets['id']);
            return 1;
        }
        return 0;
    }
}
