<?php

return [

    [
        'title' => 'Dashboard',
        'icon' => 'fa-solid fa-house',
        'route' => 'dashboard',
        'role' => ['admin', 'DRIVER', 'PICKER', 'PIC', 'SPV']
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
    [
        'title' => 'Master',
        'icon' => 'fa-solid fa-cog',
        'id' => 'master',
        'children' => [
            [
                'title' => 'User',
                'route' => 'master.users',
            ],
            [
                'title' => 'Department',
                'route' => 'master.department',
            ],
            [
                'title' => 'Outlet',
                'route' => 'master.outlet',
            ]
        ]
    ],

];
