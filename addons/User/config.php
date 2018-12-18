<?php 
return [
    "name" => "组织架构",
    "order" => 254,
    "version" => "1.0",
    "description" => "账户、职位、角色权限、部门管理。",
    "access" => [
        1 => "本人",
        2 => "本人和下属",
        3 => "部门所有人",
        4 => "所有人"
    ],
    'dialogs' => [
        'department' => [
            'name'  => '部门',
            'model' => 'Aike\User\Department::Dialog',
            'url'   => 'user/department/dialog',
        ],
        'role' => [
            'name'  => '角色',
            'model' => 'Aike\User\Role::Dialog',
            'url'   => 'user/role/dialog',
        ],
        'user' => [
            'name'  => '用户',
            'model' => 'Aike\User\User::Dialog',
            'url'   => 'user/user/dialog',
        ],
    ],
    "controllers" => [
        "user" => [
            "name" => "用户",
            "actions" => [
                "index" => [
                    "name" => "列表"
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
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "department" => [
            "name" => "部门",
            "actions" => [
                "index" => [
                    "name" => "列表"
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
                "delete" => [
                    "name" => "删除"
                ],
                "dialog" => [
                    "name" => "对话框"
                ]
            ]
        ],
        "role" => [
            "name" => "角色",
            "actions" => [
                "index" => [
                    "name" => "列表"
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
                "config" => [
                    "name" => "权限"
                ],
                "delete" => [
                    "name" => "删除"
                ],
                "dialog" => [
                    "name" => "对话框"
                ]
            ]
        ],
        "group" => [
            "name" => "用户组",
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
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ],
        "position" => [
            "name" => "用户职位",
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
                "delete" => [
                    "name" => "删除"
                ]
            ]
        ]
    ]
];
