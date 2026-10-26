<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$response->isSuccessful() || !str_contains(strtolower($response->headers->get('Content-Type', '')), 'text/html')) {
            return $response;
        }

        $content = $response->getContent();

        if ($content === false || $content === '') {
            return $response;
        }

        $content = preg_replace(
            [
                '/\>[^\S ]+/s',
                '/[^\S ]+\</s',
                '/(\s)+/s',
            ],
            [
                '>',
                '<',
                '\\1',
            ],
            $content
        );

        $content = str_replace("\t", '', $content);

        $response->setContent($content);

        return $response;
    }
}
