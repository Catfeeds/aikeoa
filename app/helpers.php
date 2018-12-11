<?php

/**
 * 公共文件目录
 */
function public_path($path = '')
{
    return base_path('public').($path ? '/'.$path : $path);
}

/**
 * 文件上传目录
 */
function upload_path($path = '')
{
    return public_path('uploads').($path ? '/'.$path : $path);
}

/*
 * 抛出异常
 */
function abort_error($message)
{
    throw new App\Exceptions\CustomException($message);
}
