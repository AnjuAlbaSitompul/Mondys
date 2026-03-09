<?php

return [

    [
        'title' => 'Dashboard',
        'icon' => 'fa-solid fa-house',
        'route' => 'dashboard',
    ],
    [
        'title' => 'Picker',
        'icon' => 'fa-solid fa-person-biking',
        'id' => 'picker',
        'children' => [
            [
                'title' => 'Jemput',
                'route' => 'picker.pick',
            ],
        ]
    ],

];
