<?php 
return [
    "name" => "销售考勤",
    "order" => 51,
    "version" => "1.0",
    "description" => "市场巡查,库存上报,市场亮点标准。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "stock" => [
            "name" => "库存上报",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "count" => [
                    "name" => "统计",
                    "access" => 1
                ],
                "print" => [
                    "name" => "打印"
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
        "market" => [
            "name" => "市场巡查",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "count" => [
                    "name" => "统计",
                    "access" => 1
                ],
                "view" => [
                    "name" => "查看"
                ],
                "print" => [
                    "name" => "打印"
                ],
                "delete" => [
                    "name" => "删除"
                ],
                "category" => [
                    "name" => "类别列表"
                ],
                "category_add" => [
                    "name" => "创建类别"
                ],
                "category_delete" => [
                    "name" => "删除类别"
                ]
            ]
        ],
        "mart" => [
            "name" => "市场销售资讯",
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
                ],
                "category" => [
                    "name" => "类别"
                ],
                "category_add" => [
                    "name" => "类别创建"
                ],
                "category_delete" => [
                    "name" => "类别删除"
                ]
            ]
        ]
    ]
];
