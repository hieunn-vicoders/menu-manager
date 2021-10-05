<?php

return [
    'cache' => [
        'enabled' => true,
        'minutes' => 30,
    ],
    'page'            => [
        'home'    => [
            'label'    => 'Trang chủ',
            'position' => [
                'position-1' => 'Vị trí bên trên tin mới',
                'position-2' => 'Vị trí bên trên hỗ trợ trực tuyến',
                'position-3' => 'Vị trí bên trên sản phẩm bán chạy',
            ],
        ],
        'contact' => [
            'label'    => 'Liên hệ',
            'position' => [
                'position-1' => 'Vị trí bên trái',
                'position-2' => 'Vị trí thứ 2 từ trái sang',
                'position-3' => 'Vị trí thứ 2 từ phải sang',
                'position-4' => 'Vị trí bên phải',
            ],
        ],
        'footer' => [
            'label'    => 'footer',
            'position' => [
                'position-1' => 'Vị trí chính giữa',
            ],
        ],
    ],
    'auth_middleware' => [
        'admin'    => [
            [
                'middleware' => '',
                'except'     => [],
            ],
        ],
    ],
];
