<?php

return $settings = [
  "db" => [
    'driver' => Cake\Database\Driver\Mysql::class,
    // 'driver' => Cake\Database\Driver\Mysql::class,
    "host" => $_ENV["DB_HOST"],
    // "port" => $_ENV["DB_PORT"],
    "database" => $_ENV["DB_NAME"],
    "username" => $_ENV["DB_USER"],
    "password" => $_ENV["DB_PASS"],
  ],
];

