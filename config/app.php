<?php

return [
    'env' => getenv('APP_ENV'),

    'db' => [
        'host' => (string) getenv('DB_HOST'), 
        'port' => (int) getenv('DB_PORT'), 
        'name' => (string) getenv('DB_NAME'), 
        'user' => (string) getenv('DB_USER'), 
        'password' => (string) getenv('DB_PASSWORD'), 
    ]
];
