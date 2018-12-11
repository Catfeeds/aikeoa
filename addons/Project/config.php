<?php 
return [
    "name" => "项目管理",
    "order" => 101,
    "version" => "1.0",
    "description" => "项目管理。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "project" => [
            "name" => "项目",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1,
                ],
                "add" => [
                    "name" => "添加",
                ],
                "show" => [
                    "name" => "显示"
                ],
                "edit" => [
                    "name" => "编辑",
                    "access" => 1,
                ],
                "delete" => [
                    "name" => "删除",
                    "access" => 1,
                ]
            ]
        ],
        "task" => [
            "name" => "任务",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "add" => [
                    "name" => "添加"
                ],
                "edit" => [
                    "name" => "编辑"
                ],
                "show" => [
                    "name" => "显示"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "comment" => [
            "name" => "评论",
            "actions" => [
                "add" => [
                    "name" => "添加"
                ],
                "edit" => [
                    "name" => "编辑"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ]
    ]
];
