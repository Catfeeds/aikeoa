<?php 
return [
    "name" => "工作流程",
    "order" => 31,
    "version" => "1.0",
    "description" => "流程管理。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "workflow" => [
            "name" => "流程列表",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "view" => [
                    "name" => "查看"
                ],
                "list" => [
                    "name" => "发起"
                ],
                "monitor" => [
                    "name" => "监控"
                ],
                "trash" => [
                    "name" => "回收站",
                    "access" => 1
                ],
                "query" => [
                    "name" => "统计",
                    "access" => 1
                ],
                "add" => [
                    "name" => "新建"
                ],
                "edit" => [
                    "name" => "办理"
                ],
                "delete" => [
                    "name" => "删除"
                ],
                "destroy" => [
                    "name" => "销毁"
                ]
            ]
        ],
        "widget" => [
            "name" => "流程部件",
            "actions" => [
                "efficiency" => [
                    "name" => "流程效率",
                    "access" => 1
                ]
            ]
        ],
        "monitor" => [
            "name" => "监控流程",
            "actions" => [
                "summary" => [
                    "name" => "汇总"
                ]
            ]
        ],
        "category" => [
            "name" => "流程类别",
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
        "step" => [
            "name" => "步骤设计",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "view" => [
                    "name" => "查看"
                ],
                "save" => [
                    "name" => "保存"
                ],
                "add" => [
                    "name" => "新建"
                ],
                "edit" => [
                    "name" => "编辑"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "form" => [
            "name" => "表单设计",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "view" => [
                    "name" => "查看"
                ],
                "count" => [
                    "name" => "新建"
                ]
            ]
        ],
        "design" => [
            "name" => "流程设计",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "process" => [
                    "name" => "编辑"
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
