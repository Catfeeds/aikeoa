<?php 
return [
    "name" => "客户管理",
    "order" => 42,
    "version" => "1.0",
    "description" => "潜在客户资料上传，通过手机客户端收集客户资料。",
    'dialogs' => [
        'circle' => [
            'name'  => '销售区域',
            'model' => 'Aike\Customer\Circle::Dialog',
            'table' => 'customer_circle',
            'field' => 'customer_circle.name',
            'url'   => 'customer/circle/dialog',
        ],
        'customer' => [
            'name'  => '客户',
            'model' => 'Aike\Customer\Customer::Dialog',
            'table' => 'customer',
            'join'  => ['user', 'user.id', 'customer.user_id'],
            'field' => 'user.nickname',
            'url'   => 'customer/customer/dialog',
        ],
    ],
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "customer" => [
            "name" => "客户档案",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
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
                "invoice_type" => [
                    "name" => "开票类型"
                ],
                "invoice_edit" => [
                    "name" => "开票编辑"
                ],
                "import" => [
                    "name" => "导入"
                ],
                "export" => [
                    "name" => "导出"
                ]
            ]
        ],
        "type" => [
            "name" => "客户类型",
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
        "circle" => [
            "name" => "客户圈",
            "actions" => [
                "index" => [
                    "name" => "圈列表"
                ],
                "region" => [
                    "name" => "区域列表"
                ],
                "aspect" => [
                    "name" => "方面列表"
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
        "account" => [
            "name" => "对账单",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "query" => [
                    "name" => "查询",
                    "access" => 1
                ],
                "audit" => [
                    "name" => "审核"
                ],
                "create" => [
                    "name" => "新建"
                ],
                "show" => [
                    "name" => "显示"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "receivable" => [
            "name" => "回款记录",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "create" => [
                    "name" => "新建"
                ],
                "show" => [
                    "name" => "显示"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "contribute" => [
            "name" => "贡献记录",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "create" => [
                    "name" => "新建"
                ],
                "show" => [
                    "name" => "显示"
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
                "show" => [
                    "name" => "显示"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "contact" => [
            "name" => "联系人",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "show" => [
                    "name" => "显示"
                ],
                "create" => [
                    "name" => "新建"
                ],
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "contract" => [
            "name" => "客户合同",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "box" => [
                    "name" => "盒子"
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
                "task" => [
                    "name" => "任务"
                ],
                "taskdata" => [
                    "name" => "任务数据"
                ]
            ]
        ],
        "business" => [
            "name" => "潜在客户",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ],
                "create" => [
                    "name" => "新建"
                ],
                "show" => [
                    "name" => "显示"
                ],
                "sms" => [
                    "name" => "短信"
                ],
                "destroy" => [
                    "name" => "删除"
                ]
            ]
        ]
    ]
];
