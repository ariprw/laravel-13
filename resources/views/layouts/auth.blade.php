<!DOCTYPE html>
<html lang="id">

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

<body class="min-h-screen bg-slate-100 font-google-sans-flex">
    <div class="min-h-screen max-w-96 flex items-center justify-center m-auto px-4 py-8">
        <div class="w-full">

            <div class="mb-8">
                <a href="{{ url('/') }}">{!! ic('logo', 'w-full') !!}</a>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                @yield('content')
            </div>

            <div class="text-center mt-6">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
            </div>

        </div>
    </div>
</body>
</html>