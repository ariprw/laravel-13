<?php

$dashboard = [
    'label' => 'Dashboard',
    'icon' => 'house',
    'route' => 'dashboard',
];

$user = [
    'label' => 'User',
    'icon' => 'users-round',
    'route' => 'user.index',
];

$branches = [
    'label' => 'Cabang',
    'icon' => 'building-complex',
    'route' => 'branches.index',
];

$activityLog = [
    'label' => 'Log Aktivitas',
    'icon' => 'logs',
    'route' => 'activity.log.index',
];

$profile = [
    'label' => 'Profil',
    'icon' => 'user-round',
    'route' => 'user.profile',
];

$password = [
    'label' => 'Kata Sandi',
    'icon' => 'key-round',
    'route' => 'user.password',
];

$logout = [
    'label' => 'Keluar',
    'icon' => 'log-out',
    'route' => 'logout',
];

$divider = [
    'type' => 'divider',
];


return [
    'admin' => [
        $dashboard,
        $divider,
        $user,
        $branches,
        $divider,
        $activityLog,
        $divider,
        $profile,
        $password,
        $logout,
    ],

    'user' => [
        $dashboard,
        $divider,
        $user,
        $branches,
        $divider,
        $activityLog,
        $divider,
        $profile,
        $password,
        $logout,
    ],

    'employee' => [
        $dashboard,
        $divider,
        $profile,
        $password,
        $logout,
    ],

    'default' => [
        $dashboard,
        $logout,
    ],
];
