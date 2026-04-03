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
                'title' => 'Picker',
                'route' => 'barang',
            ],
            [
                'title' => 'End Pick',
                'route' => 'barang.pick',
            ],
        ]
    ],
    [
        'title' => 'Boarding',
        'icon' => 'fa-solid fa-truck',
        'id' => 'boarding',
        'children' => [
            [
                'title' => 'Boarding List',
                'route' => 'boarding',
            ],
            [
                'title' => 'Titip',
                'route' => 'titip',
            ]
        ]
    ],
    [
        'title' => 'Loading',
        'icon' => 'fa-solid fa-truck-loading',
        'route' => 'loading',
    ],

];
