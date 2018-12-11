<?php 
return [
    "name" => "进店管理",
    "order" => 36,
    "version" => "1.0",
    "description" => "条码进店。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "approach" => [
            "name" => "入场管理",
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
        ]
    ]
];
