<?php
if (!function_exists('ic')) {
    function ic($name='', $class='')
    {
        $icons = [
            'logo' => '',
        ];

        if (!isset($icons[$name])) {
            return '';
        }

        $svg = $icons[$name];

        if ($class !== '') {
            $svg = preg_replace(
                '/<svg\b/',
                '<svg class="'.htmlspecialchars($class, ENT_QUOTES, 'UTF-8').'"',
                $svg,
                1
            );
        }

        return $svg;
    }

    function formatNumber($value='')
    {
        return preg_replace('/[^0-9]/', '', (string) $value);
    }

    function formatThousands($value='')
    {
        $number = preg_replace('/[^0-9-]/', '', (string) $value);

        if ($number === '' || $number === '-') {
            return '0';
        }

        return number_format($number, 0, ',', '.');
    }

    function idToken($token='')
    {
        $pepper = config('app.token_pepper');
        $splits = explode(',', config('app.token_pepper_split'));

        foreach ($splits as $percent) {
            $pos = (int) ($percent * strlen($pepper) / 100);
            $pepper = substr_replace($pepper, $token, $pos, 0);
        }

        $hash = hash('sha256', $pepper);
        $splits = array_map('intval', $splits);

        $start = (int) (min($splits) * strlen($hash) / 100);
        $end   = (int) (max($splits) * strlen($hash) / 100);

        return substr($hash, $start, $end - $start);
    }
}