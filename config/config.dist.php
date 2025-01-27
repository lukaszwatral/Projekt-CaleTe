<?php
$config = [];
$config['db_dsn'] = 'sqlite:' . __DIR__ . '/../data.db';
$config['db_user'] = '';
$config['db_pass'] = '';
$config['semesters'] = [
    '2024-zima' => [
        'start' => '2024-10-01',
        'end' => '2025-02-28',
    ],
    '2025-lato' => [
        'start' => '2025-03-01',
        'end' => '2025-09-30',
    ]
];
$config['current_semester'] = '2024-zima';