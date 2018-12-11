<?php
return [
    "name" => "模型管理",
    "order" => 255,
    "version" => "1.0",
    "description" => "模型管理",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "model" => [
            "name" => "模型",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "create" => [
                    "name" => "新建"
                ],
                "view" => [
                    "name" => "视图"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "template" => [
            "name" => "视图",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "create" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "field" => [
            "name" => "字段",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "create" => [
                    "name" => "新建"
                ],
                "type" => [
                    "name" => "类型"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "step" => [
            "name" => "步骤",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "create" => [
                    "name" => "新建"
                ],
                "edit" => [
                    "name" => "编辑"
                ],
                "save" => [
                    "name" => "保存"
                ],
                "condition" => [
                    "name" => "条件"
                ],
                "delete" => [
                    "name" => "删除"
                ],
                "move" => [
                    "name" => "移交"
                ]
            ]
        ]
    ]
];
