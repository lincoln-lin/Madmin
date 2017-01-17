<?php

return [
    'mz.admin' => [
        'mz.admin.allowActions' => [], // 不受权限控制的 Action 列表，为完整的路由路径，比如 /user/index，/role/index，配置具有继承性，如果存在配置 /user，那么 /user/index ，/user/create，/user/sub/sub 也不受权限控制
        'user' => [
            'rememberMeDuration' => 86400 * 7, // 记住登录时间，单位秒
            'passwordResetTokenExpire' => 3600, // 重置密码Token有效期，单位秒
        ],
        'cacheTag' => 'yii2-admin', // 缓存 TagDependency 使用的 TAG 前缀，默认为 _mz.admin.cache.tag_，如果多个使用了 meizu/yii2-admin 扩展的项目使用同一个缓存（Redis），那么需要配置该属性，否则会冲突
        'cacheDuration' => 86400 * 10, // 后台 rbac 相关缓存的缓存有效期
    ],
];
