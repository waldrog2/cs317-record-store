<?php

    $DB_HOST = '127.0.0.1';
    $DB_USER = 'records';
    $DB_PASS = 'records';
    $DB_NAME = 'records';
    $DB_CHARSET = 'utf8mb4';
    $DSN = "mysql:host=".$DB_HOST.";dbname=".$DB_NAME .";charset=".$DB_CHARSET;
    $DB_OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];


    return [
        'LOG_PATH' => __DIR__ . '/logs',
        'DB_HOST' => $DB_HOST,
        'DB_USER' => $DB_USER,
        'DB_PASSWD' => $DB_PASS,
        'DB_DATABASE' => $DB_NAME,
        'DB_CHARSET' => $DB_CHARSET,
        'DB_OPTIONS' => $DB_OPTIONS
    ];