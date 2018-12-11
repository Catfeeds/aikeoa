<?php 
return [
    "name" => "人事管理",
    "order" => 14,
    "version" => "1.0",
    "description" => "人力资源档案",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "hr" => [
            "name" => "档案",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "view" => [
                    "name" => "查看"
                ],
                "create" => [
                    "name" => "新建"
                ],
                "edit" => [
                    "name" => "编辑"
                ],
                "export" => [
                    "name" => "导出"
                ],
                "trash" => [
                    "name" => "回收站"
                ],
                "delete" => [
                    "name" => "删除"
                ],
                "destroy" => [
                    "name" => "销毁"
                ]
            ]
        ],
        "job" => [
            "name" => "工作记录",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "create" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "cultivate" => [
            "name" => "培训记录",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "create" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "punish" => [
            "name" => "扣罚记录",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "create" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ]
    ]
];
