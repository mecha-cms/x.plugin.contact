<?php

return [
    'email' => 'email@domain.com',
    'topic' => '%{site.title}% · New Message',
    'anchor' => [1 => 'form-contact'],
    'path' => '-contact',
    'contact' => [
        'type' => 'HTML'
    ],
    'max' => [
        'author' => 100,
        'email' => 100,
        'link' => 100,
        'content' => 1700
    ],
    'min' => [
        'author' => 1,
        'email' => 1,
        'link' => 0,
        'content' => 1
    ],
    'query_x' => ['<script ', '<iframe '], // Block by word(s)
    'user_ip_x' => [], // Block by IP address(es)
    'user_agent_x' => [], // Block by user agent word(s)
    'style' => [
        'spacing' => 2,
        'background' => ['#eee', '#cde'],
        'color' => '#000',
        'font' => [
            'size' => 13,
            'face' => 'Helmet, FreeSans, sans-serif'
        ],
        'text' => [
            'align' => 'left'
        ]
    ]
];