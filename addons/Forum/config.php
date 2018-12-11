<?php 
return [
    "name" => "内部论坛",
    "order" => 4,
    "version" => "1.0",
    "description" => "论坛管理",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "category" => [
            "name" => "板块",
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
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "post" => [
            "name" => "帖子",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "category" => [
                    "name" => "类别"
                ],
                "reply" => [
                    "name" => "回复"
                ],
                "view" => [
                    "name" => "查看"
                ],
                "add" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ]
    ]
];
