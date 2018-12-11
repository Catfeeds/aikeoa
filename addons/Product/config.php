<?php 
return [
    "name" => "产品管理",
    "order" => 41,
    "version" => "1.0",
    "description" => "产品列表,产品类别,库存类型,仓库类别,库存管理,仓库列表。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "product" => [
            "name" => "产品列表",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "add" => [
                    "name" => "新建"
                ],
                "export" => [
                    "name" => "导出"
                ],
                "delete" => [
                    "name" => "删除"
                ],
                "dialog" => [
                    "name"   => "对话框",
                    "access" => 1
                ]
            ]
        ],
        "bom" => [
            "name" => "物料清单",
            "actions" => [
                "edit" => [
                    "name" => "编辑"
                ]
            ]
        ],
        "category" => [
            "name" => "产品类别",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "add" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ],
                "dialog" => [
                    "name"   => "对话框",
                ]
            ]
        ],
        "price" => [
            "name" => "产品单价",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "add" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "stock" => [
            "name" => "库存管理",
            "actions" => [
                "index" => [
                    "name" => "进出存列表",
                    "access" => 1
                ],
                "create" => [
                    "name" => "成品出入库单"
                ],
                "report" => [
                    "name" => "进出存汇总表"
                ],
                "view" => [
                    "name" => "查看"
                ],
                "merge" => [
                    "name" => "合并"
                ],
                "export" => [
                    "name" => "导出"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "warehouse" => [
            "name" => "仓库类别",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "add" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "type" => [
            "name" => "库存类型",
            "actions" => [
                "index" => [
                    "name" => "列表"
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
