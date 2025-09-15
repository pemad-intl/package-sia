<?php

return [
	'navbar-brand' => 'Ruang Guru',

    'name' => 'Ruang Guru '.config('app.name'),

    'home' => [
    	'name' => 'Ruang Guru | '.config('app.name'),
    ],

    'report' => [
        'grade' => [
            ['min' => 90, 'max' => 100, 'predikat' => 'A', 'deskripsi' => 'Sangat Baik'],
            ['min' => 80, 'max' => 89,  'predikat' => 'B', 'deskripsi' => 'Baik'],
            ['min' => 70, 'max' => 79,  'predikat' => 'C', 'deskripsi' => 'Cukup'],
            ['min' => 0,  'max' => 69,  'predikat' => 'D', 'deskripsi' => 'Perlu Bimbingan'],
        ]
    ]
];
