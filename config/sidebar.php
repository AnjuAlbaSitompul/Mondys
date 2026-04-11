<?php

return [

    [
        'title' => 'Dashboard',
        'icon' => 'fa-solid fa-house',
        'route' => 'dashboard',
        'role' => ['ADMIN', 'DRIVER', 'PICKER', 'PIC', 'SPV']
    ],
    [
        'title' => 'Task',
        'icon' => 'fa-solid fa-box',
        'id' => 'barang',
        'children' => [
            [
                'title' => 'Picking',
                'route' => 'barang',
            ],
            [
                'title' => 'Boarding',
                'route' => 'boarding',
            ],
            [
                'title' => 'Loading',
                'route' => 'loading',
            ],
        ]
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
            ],
            [
                'title' => 'Jenis Barang',
                'route' => 'master.jenis',
            ]
        ]
    ],

];
