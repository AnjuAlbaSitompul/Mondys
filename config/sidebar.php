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
        'role' => ['ADMIN', 'SPV'], // hanya ini yang boleh
        'children' => [
            [
                'title' => 'Picking',
                'route' => 'barang',
                'roles' => ['ADMIN', 'SPV']
            ],
            [
                'title' => 'Boarding',
                'route' => 'boarding',
                'roles' => ['ADMIN', 'SPV']
            ],
            [
                'title' => 'Loading',
                'route' => 'loading',
                'roles' => ['ADMIN', 'SPV']
            ],
        ]
    ],

    [
        'title' => 'Master',
        'icon' => 'fa-solid fa-cog',
        'id' => 'master',
        'role' => ['ADMIN', 'SPV'], // parent boleh SPV
        'children' => [
            [
                'title' => 'User',
                'route' => 'master.users',
                'roles' => ['ADMIN'] // ❗ hanya ADMIN
            ],
            [
                'title' => 'Department',
                'route' => 'master.department',
                'roles' => ['ADMIN', 'SPV']
            ],
            [
                'title' => 'Outlet',
                'route' => 'master.outlet',
                'roles' => ['ADMIN', 'SPV']
            ],
            [
                'title' => 'Jenis Barang',
                'route' => 'master.jenis',
                'roles' => ['ADMIN', 'SPV']
            ]
        ]
    ],

];
