<?php 
return [
    "name" => "文件管理",
    "order" => 33,
    "version" => "1.0",
    "description" => "文件查看下载，供大家下载资料的通用功能。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "download" => [
            "name" => "下载",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "view" => [
                    "name" => "查看"
                ],
                "add" => [
                    "name" => "新建"
                ],
                "down" => [
                    "name" => "下载"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ]
    ]
];
