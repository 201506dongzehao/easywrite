<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // 显示所有错误信息, false 时屏蔽错误信息, 上线后 设置为 false
		'db' => [ 
				'host' => "127.0.0.1",
				'user' => 'root',
				'pass' => 'root',
				'dbname' => 'note'
		],
		
        // 视图文件目录
        'render' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
		'tests' => [
			
        ],
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => Monolog\Logger::DEBUG,
        ],
    ]
];
