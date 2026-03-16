<?php

return [

    [
        'title' => 'Dashboard',
        'icon' => 'fa-solid fa-house',
        'route' => 'dashboard',
    ],
    [
        'title' => 'Barang',
        'icon' => 'fa-solid fa-box',
        'id' => 'barang',
        'children' => [
            [
                'title' => 'List Barang',
                'route' => 'barang',
            ],
        ]
    ],
    [
        'title' => 'Boarding',
        'icon' => 'fa-solid fa-house',
        'route' => 'boarding',
    ],

];
