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
    <div class="fixed top-0 z-50 w-full bg-white text-sm border-b border-b-solid border-slate-200 px-3 lg:px-6 py-1 select-none">
        <div class="flex lg:items-center justify-between gap-1">
            <div>
                <a class="block m-3" href="">{!! ic('logo', 'max-w-24 lg:max-w-36 h-auto') !!}</a>
            </div>

            @if (Route::has('login'))
                <div class="listMenu hidden lg:flex items-center justify-between gap-4 select-none duration-200 ease-in-out">
                        <a class="block m-3 {{ request()->routeIs('home') ? 'text-lime-700' : 'text-black' }} hover:text-lime-700" href="{{ route('home') }}">Beranda</a>
                    @auth
                        <a class="block m-3 {{ request()->routeIs('dashboard') ? 'text-lime-700' : 'text-black' }} hover:text-lime-700" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="block m-3 {{ request()->routeIs('login') ? 'text-lime-700' : 'text-black' }} hover:text-lime-700" href="{{ route('login') }}">Login</a>
                        <a class="block m-3 {{ request()->routeIs('register') ? 'text-lime-700' : 'text-black' }} hover:text-lime-700" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="lg:hidden">
                <div class="menuButton lg:hidden block m-3 mt-4 cursor-pointer">
                    <div class="grid gap-1 items-center cursor-pointer w-5 h-4">
                        <div class="lineMenu border-b border-b-solid border-2 rounded-xl duration-200 ease-in-out"></div>
                        <div class="lineMenu border-b border-b-solid border-2 rounded-xl duration-200 ease-in-out"></div>
                        <div class="lineMenu border-b border-b-solid border-2 rounded-xl duration-200 ease-in-out"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-9 pt-4">
        @yield('content')
    </div>

    <script>
        const menuButton = document.querySelector('.menuButton');
        const listMenu = document.querySelector('.listMenu');

        menuButton?.addEventListener('click', () => {
            const toggleClasses = [
                ['transform', '-rotate-45', 'mt-2'],
                ['transform', 'rotate-45', '-mt-2'],
                ['opacity-0']
            ];

            menuButton.querySelectorAll('.lineMenu').forEach((item, i) => {
                toggleClasses[i].forEach(cls => item.classList.toggle(cls));
            });

            listMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>