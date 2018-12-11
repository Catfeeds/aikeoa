<?php

class Module
{
    public static $modules;

    public static $dialogs = [];

    public static function all()
    {
        if (static::$modules) {
            return static::$modules;
        }

        $path = base_path().'/addons';

        if (is_dir($path)) {
            $folders = new DirectoryIterator($path);

            foreach ($folders as $folder) {
                if (!$folder->isDot() && $folder->isDir()) {
                    $folder = $folder->getFileName();
                    $file = $path.'/'.$folder.'/config.php';
                    
                    if (is_file($file)) {
                        static::$modules[$folder] = $path.'/'.$folder;
                    }
                }
            }
        }
        return static::$modules;
    }

    public static function allWithDetails()
    {
        $modules = static::all();
        $json = [];
        foreach ($modules as $key => $module) {
            $file = $module.'/config.php';

            if (is_file($file)) {
                $content = include($file);
                $content['path'] = $module;

                $listens = isset($content['listens']) ? $content['listens'] : [];
                
                // 注册事件
                foreach ($listens as $k => $v) {
                    Hook::listen($v[0], $v[1]);
                }

                if (isset($content['dialogs'])) {
                    static::$dialogs = static::$dialogs + $content['dialogs'];
                }
                
                $json[strtolower($key)] = $content;
            }
        }
        return $json;
    }

    public static function dialogs($key = null)
    {
        if ($key) {
            return static::$dialogs[$key];
        }
        return static::$dialogs;
    }
}
