<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ url('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ url('favicon.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/style.css') }}">
    <title>
        {{ $__env->yieldContent('title', config('app.name')) === config('app.name')
        ? config('app.name')
        : $__env->yieldContent('title').' - '.config('app.name') }}
    </title>
</head>

<body class="font-google-sans-flex">
@php
$menu = [
    'admin' => [
        [
            'label' => 'Dashboard',
            'icon' => 'house',
            'route' => 'dashboard',
        ],
        [
            'type' => 'divider',
        ],
        [
            'label' => 'Daftar User',
            'icon' => 'users-round',
            'route' => 'user.index',
        ],
        [
            'label' => 'Cabang',
            'icon' => 'building-complex',
            'route' => 'branches.index',
        ],
        [
            'type' => 'divider',
        ],
        [
            'label' => 'Log Aktivitas',
            'icon' => 'logs',
            'route' => 'activity.log.index',
        ],
        [
            'type' => 'divider',
        ],
        [
            'label' => 'Profil',
            'icon' => 'user-round',
            'route' => 'user.profile',
        ],
        [
            'label' => 'Kata Sandi',
            'icon' => 'key-round',
            'route' => 'user.password',
        ],
        [
            'label' => 'Keluar',
            'icon' => 'log-out',
            'route' => 'logout',
        ],
    ],

    'user' => [
        [
            'label' => 'Dashboard',
            'icon' => 'house',
            'route' => 'dashboard',
        ],
        [
            'type' => 'divider',
        ],
        [
            'label' => 'Profil',
            'icon' => 'user-round',
            'route' => 'user.profile',
        ],
        [
            'label' => 'Kata Sandi',
            'icon' => 'key-round',
            'route' => 'user.password',
        ],
        [
            'label' => 'Keluar',
            'icon' => 'log-out',
            'route' => 'logout',
        ],
    ],

    'karyawan' => [
        [
            'label' => 'Dashboard',
            'icon' => 'house',
            'route' => 'dashboard',
        ],
        [
            'type' => 'divider',
        ],
        [
            'label' => 'Profil',
            'icon' => 'user-round',
            'route' => 'user.profile',
        ],
        [
            'label' => 'Kata Sandi',
            'icon' => 'key-round',
            'route' => 'user.password',
        ],
        [
            'label' => 'Keluar',
            'icon' => 'log-out',
            'route' => 'logout',
        ],
    ],

    'default' => [
        [
            'label' => 'Dashboard',
            'icon' => 'house',
            'route' => 'dashboard',
        ],
        [
            'label' => 'Keluar',
            'icon' => 'log-out',
            'route' => 'logout',
        ],
    ],
];

$auth = auth()->user();
$role = $auth?->role ?? 'default';
$name = $auth?->name ?? '';

$menus = $menu[$role] ?? $menu['default'];
@endphp

<div class="min-h-screen bg-slate-50">
    <div class="sidebar hidden lg:block fixed inset-y-0 left-0 z-50 w-64 lg:w-60 bg-white border-r border-r-solid border-gray-200 overflow-y-auto select-none">
        <div class="p-4">
            <div>
                <a class="inline-block text-xl font-bold" href="{{ route('home') }}">{!! ic('logo', 'max-w-36 h-auto') !!}</a>
                <div class="text-xs italic">{{ $name }}</div>
            </div>

            <div class="mt-6">
                @foreach ($menus as $item)
                    @if (($item['type'] ?? null) === 'divider')
                        <div class="my-3 border-b border-b-solid border-gray-200"></div>
                    @else
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 my-1 px-3 py-2 rounded-xl {{ request()->routeIs($item['route']) ? 'bg-slate-100' : '' }} hover:bg-slate-100 text-sm">
                            {!! ic($item['icon'], 'w-5 h-5 shrink-0') !!}

                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>


    <div class="lg:ml-60 min-w-0 min-h-screen">
        <div class="sticky top-0 z-40 h-12 bg-white border-b border-b-solid border-gray-200 select-none">
            <div class="flex gap-3 items-center justify-between h-full px-6">
                <div class="text-base font-semibold truncate">{{ $title }}</div>

                <div class="lg:hidden">
                    <div class="menuButton lg:hidden block cursor-pointer">
                        <div class="grid gap-1 items-center cursor-pointer w-5 h-4">
                            <div class="lineMenu border-b border-b-solid border-2 rounded-xl duration-200 ease-in-out"></div>
                            <div class="lineMenu border-b border-b-solid border-2 rounded-xl duration-200 ease-in-out"></div>
                            <div class="lineMenu border-b border-b-solid border-2 rounded-xl duration-200 ease-in-out"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="content lg:block max-w-320 m-auto p-3 lg:p-6">
            @yield('content')
        </div>
    </div>
</div>

<script>
    const menuButton = document.querySelector('.menuButton');
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');

    menuButton?.addEventListener('click', () => {
        const toggleClasses = [
            ['transform', '-rotate-45', 'mt-2'],
            ['transform', 'rotate-45', '-mt-2'],
            ['opacity-0']
        ];

        menuButton.querySelectorAll('.lineMenu').forEach((item, i) => {
            toggleClasses[i].forEach(cls => item.classList.toggle(cls));
        });

        sidebar.classList.toggle('hidden');
        content.classList.toggle('hidden');
    });
</script>
</body>
</html>