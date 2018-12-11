<?php 
return [
    "name" => "客户订单",
    "order" => 21,
    "version" => "1.0",
    "description" => "订单管理,销售支持,生产计划,订单类型,订单发货。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "order" => [
            "name" => "订单",
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
                "export" => [
                    "name" => "导出"
                ],
                'monitor_export' => [
                    'name' => '导出订单执行'
                ],
                "syncyonyou" => [
                    "name" => "同步用友(外账)"
                ],
                "merge" => [
                    "name" => "合并"
                ],
                "part" => [
                    "name" => "拆分"
                ],
                "repeal" => [
                    "name" => "废除"
                ],
                "sendfax" => [
                    "name" => "传真订单"
                ],
                "transport" => [
                    "name" => "物流"
                ],
                "audit" => [
                    "name" => "审核"
                ],
                "print" => [
                    "name" => "打印"
                ],
                "data" => [
                    "name" => "订单数据"
                ],
                "monitor" => [
                    "name" => "监控"
                ],
                "monitor_data" => [
                    "name" => "监控数据"
                ],
                "pay" => [
                    "name" => "在线支付"
                ],
                "sync" => [
                    "name" => "订单同步"
                ],
                "product_add" => [
                    "name" => "产品添加"
                ],
                "product_edit" => [
                    "name" => "产品编辑"
                ],
                "product_delete" => [
                    "name" => "产品删除"
                ]
            ]
        ],
        "cost" => [
            "name" => "销售支持",
            "actions" => [
                "index" => [
                    "name" => "列表",
                    "access" => 1
                ]
            ]
        ],
        "plan" => [
            "name" => "生产计划",
            "actions" => [
                "index" => [
                    "name" => "订单",
                    "access" => 1
                ],
                "deliver" => [
                    "name" => "发货"
                ],
                "purchase" => [
                    "name" => "物料"
                ],
                "produce" => [
                    "name" => "生产"
                ],
                "summary" => [
                    "name" => "生产需求汇总"
                ],
                "produce_add" => [
                    "name" => "生产创建"
                ],
                "produce_state" => [
                    "name" => "生产状态"
                ],
                "count" => [
                    "name" => "订单统计"
                ],
                "coefficient" => [
                    "name" => "营运系数"
                ],
                "batch" => [
                    "name" => "生产批号"
                ],
                "materiel" => [
                    "name" => "SP汇总"
                ]
            ]
        ],
        "type" => [
            "name" => "订单类型",
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
        "transport" => [
            "name" => "订单发货",
            "actions" => [
                "index" => [
                    "name" => "在途订单",
                    "access" => 1
                ],
                "batch" => [
                    "name" => "批号查询",
                    "access" => 1
                ],
                "advance" => [
                    "name" => "预发订单",
                    "access" => 1
                ]
            ]
        ],
        "transport-settlement" => [
            "name" => "物流结算",
            "actions" => [
                "index" => [
                    "name" => "列表",
                ],
                "show" => [
                    "name" => "显示",
                ],
                "create" => [
                    "name" => "创建",
                ],
                "edit" => [
                    "name" => "编辑",
                ],
                "delete" => [
                    "name" => "删除",
                ],
                "print" => [
                    "name" => "打印",
                ],
            ]
        ],
        "logistics" => [
            "name" => "物流公司",
            "actions" => [
                "index" => [
                    "name" => "列表",
                ],
                "create" => [
                    "name" => "创建",
                ],
                "edit" => [
                    "name" => "编辑",
                ],
                "delete" => [
                    "name" => "删除",
                ],
                "dialog" => [
                    "name" => "对话框",
                ]
            ]
        ],
        "report" => [
            "name" => "客户订单报表",
            "actions" => [
                "index" => [
                    "name" => "总订单报表",
                    "access" => 1
                ],
                "category" => [
                    "name" => "品类报表"
                ],
                "single" => [
                    "name" => "单品报表"
                ],
                "increase" => [
                    "name" => "单品涨跌报表"
                ],
                "clientsort" => [
                    "name" => "客户涨跌报表"
                ],
                "city" => [
                    "name" => "城市报表"
                ],
                "citydata" => [
                    "name" => "城市数据报表"
                ],
                "client" => [
                    "name" => "单品客户报表"
                ],
                "clientdata" => [
                    "name" => "单品数据客户报表"
                ],
                "ranking" => [
                    "name" => "客户销售排行报表"
                ],
                "promotion" => [
                    "name" => "促销分类报表"
                ],
                "clienttype" => [
                    "name" => "客户销售类型报表"
                ],
                "newclient" => [
                    "name" => "新客户报表"
                ],
                "receivable" => [
                    "name" => "客户回款报表"
                ],
                "billingtype" => [
                    "name" => "开票类型订单分析"
                ],
                "stockmonth" => [
                    "name" => "三个月未进货客户报表"
                ]
            ]
        ]
    ]
];
