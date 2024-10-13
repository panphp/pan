<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
final readonly class InjectJavascriptLibrary
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {

            $content = (string) $response->getContent();

            if (! str_contains($content, '</html>') || ! str_contains($content, '</body>')) {
                return $response;
            }

            $response->setContent(
                str_replace(
                    '</body>',
                    sprintf(<<<'HTML'
                            <script>
                                %s
                            </script>
                        </body>
                        HTML,
                        str_replace('%_PAN_CSRF_TOKEN_%', csrf_token(), File::get(__DIR__.'/../../../../../resources/js/dist/pan.iife.js')),
                    ),
                    (string) $response->getContent()
                )
            );
        }

        return $response;
    }
}
