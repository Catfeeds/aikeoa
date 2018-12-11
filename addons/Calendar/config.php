<?php 
return [
    "name" => "日程管理",
    "order" => 31,
    "version" => "1.0",
    "description" => "日历日程安排，下属日历查看，支持流行的caldav协议。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    "controllers" => [
        "calendar" => [
            "name" => "日历",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "calendar" => [
                    "name" => "读取日历"
                ],
                "active" => [
                    "name" => "活动日历"
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
                "help" => [
                    "name" => "帮助"
                ]
            ]
        ],
        "event" => [
            "name" => "事件",
            "actions" => [
                "index" => [
                    "name" => "列表"
                ],
                "resize" => [
                    "name" => "调整事件"
                ],
                "move" => [
                    "name" => "移动事件"
                ],
                "view" => [
                    "name" => "查看"
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
        ]
    ]
];
