<?php 
return [
    "name" => "促销管理",
    "order" => 35,
    "version" => "1.0",
    "description" => "促销管理。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "promotion" => [
            "name" => "促销管理",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "trash" => [
                    "name" => "回收"
                ],
                "count" => [
                    "name" => "汇总"
                ],
                "report" => [
                    "name" => "统计"
                ],
                "show" => [
                    "name" => "查看"
                ],
                "create" => [
                    "name" => "新建"
                ],
                "edit" => [
                    "name" => "编辑"
                ],
                "print" => [
                    "name" => "打印"
                ],
                "import" => [
                    "name" => "导入"
                ],
                "export" => [
                    "name" => "导出"
                ],
                "delete" => [
                    "name" => "删除"
                ],
                "restore" => [
                    "name" => "恢复"
                ],
                "destroy" => [
                    "name" => "销毁"
                ],
            ]
        ],
        "material" => [
            "name" => "促销核销",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "show" => [
                    "name" => "显示"
                ],
                "audit" => [
                    "name" => "审核"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "cash" => [
            "name" => "核销兑现",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "show" => [
                    "name" => "显示"
                ],
                "audit" => [
                    "name" => "审核"
                ],
                "create" => [
                    "name" => "创建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ]
    ]
];
