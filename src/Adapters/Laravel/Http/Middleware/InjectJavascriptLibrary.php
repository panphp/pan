<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Pan\PanConfiguration;
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

            $this->inject($response);
        }

        return $response;
    }

    /**
     * Inject the JavaScript library into the response.
     */
    private function inject(Response $response): void
    {
        $original = $response->original ?? null;

        ['prefix_url' => $prefixUrl] = app(PanConfiguration::class)->toArray();

        $response->setContent(
            str_replace(
                '</body>',
                sprintf(<<<'HTML'
                            <script>
                                %s
                            </script>
                        </body>
                        HTML,
                    str_replace(
                        ['%_PAN_CSRF_TOKEN_%', '%_PAN_PREFIX_URL_%'],
                        [(string) csrf_token(), $prefixUrl],
                        File::get(__DIR__.'/../../../../../resources/js/dist/pan.iife.js')
                    ),
                ),
                (string) $response->getContent(),
            )
        );

        if ($original !== null) {
            $response->original = $original; // @phpstan-ignore-line
        }
    }
}
