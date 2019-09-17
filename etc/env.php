<?php
return [
    'backend' => [
        'frontName' => 'admin_fcp49g'
    ],
    'db' => [
        'connection' => [
            'indexer' => [
                'host' => 'cmzpwitlf5f6ce.clh7wwi8fjvi.us-east-1.rds.amazonaws.com',
                'dbname' => 'MagentoQuickstartDB',
                'username' => 'isndba',
                'password' => 'Tse96SAB9KS3bbJW',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1',
                'persistent' => NULL
            ],
            'default' => [
                'host' => 'cmzpwitlf5f6ce.clh7wwi8fjvi.us-east-1.rds.amazonaws.com',
                'dbname' => 'MagentoQuickstartDB',
                'username' => 'isndba',
                'password' => 'Tse96SAB9KS3bbJW',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1'
            ]
        ],
        'table_prefix' => ''
    ],
    'crypt' => [
        'key' => '41e7020a509875c34ba82720732f7315'
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'developer',
    'session' => [
        'save' => 'redis',
        'redis' => [
            'host' => 'coetsmgxua6e4e8.6ghdhh.ng.0001.use1.cache.amazonaws.com',
            'port' => '6379',
            'password' => '',
            'timeout' => '2.5',
            'persistent_identifier' => '',
            'database' => '2',
            'compression_threshold' => '2048',
            'compression_library' => 'gzip',
            'log_level' => '3',
            'max_concurrency' => '6',
            'break_after_frontend' => '5',
            'break_after_adminhtml' => '30',
            'first_lifetime' => '600',
            'bot_first_lifetime' => '60',
            'bot_lifetime' => '7200',
            'disable_locking' => '0',
            'min_lifetime' => '60',
            'max_lifetime' => '2592000',
            'sentinel_master' => '',
            'sentinel_servers' => '',
            'sentinel_connect_retries' => '5',
            'sentinel_verify_master' => '0'
        ]
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'id_prefix' => '40d_',
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => 'coetsmgxua6e4e8.6ghdhh.ng.0001.use1.cache.amazonaws.com',
                    'database' => '0',
                    'port' => '6379',
                    'password' => ''
                ]
            ],
            'page_cache' => [
                'id_prefix' => '40d_',
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => 'coetsmgxua6e4e8.6ghdhh.ng.0001.use1.cache.amazonaws.com',
                    'database' => '1',
                    'port' => '6379',
                    'compress_data' => '0',
                    'password' => ''
                ]
            ]
        ]
    ],
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'compiled_config' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'target_rule' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'vertex' => 1
    ],
    'install' => [
        'date' => 'Sat, 20 Jul 2019 02:18:49 +0000'
    ]
];
