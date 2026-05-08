<?php

return [
    'host'     => getenv('MYSQL_HOSTNAME') ?: 'db',
    'username' => getenv('MYSQL_USER') ?: 'db_user',
    'password' => getenv('MYSQL_PASSWORD') ?: '',
    'database' => getenv('MYSQL_DATABASE') ?: 'basketball_store',
    'charset'  => 'utf8mb4',
    'options'  => [
        'persistent' => true,
        'retries'    => 10,
        'retry_wait' => 2,
    ],
];
