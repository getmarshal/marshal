<?php

declare(strict_types= 1);

namespace Marshal\Platform\Web;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use loophp\collection\Collection;
use Marshal\Database\Schema\Content;
use Marshal\Platform\PlatformInterface;
use Marshal\Platform\Web\TemplateRenderer\TemplateRendererResolverInterface;
use Marshal\Server\Response\SseResponse;
use Marshal\Utils\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class WebPlatform implements PlatformInterface
{
    public function __construct(private TemplateRendererResolverInterface $templateRendererResolver)
    {
    }

    public function badRequestResponse(
        ServerRequestInterface $request,
        array $messages = [],
        array $headers = [],
        ?string $template = null,
        array $options = []
    ): ResponseInterface {
        if ($this->isJsonRequest($request)) {
            return new JsonResponse(
                $messages,
                StatusCodeInterface::STATUS_BAD_REQUEST,
                $headers
            );
        }

        $template = $template ?? "marshal::error-400";
        return $this->htmlResponse(
            $template,
            $messages,
            StatusCodeInterface::STATUS_BAD_REQUEST,
            $headers,
            $options
        );
    }

    public function formatResponse(
        ServerRequestInterface $request,
        iterable $data = [],
        ?string $template = null,
        int $status = StatusCodeInterface::STATUS_OK,
        array $headers = [],
        array $options = []
    ): ResponseInterface {
        // normalize the data
        $normalized = $this->normalizeData($data);

        // for when a template is given
        if (null !== $template) {
            $renderer = $this->templateRendererResolver->resolve($template);
            $html = $renderer->render($template, $normalized, $options);
            
            return $this->isJsonRequest($request)
                ? new JsonResponse(['contents' => $html], $status, $headers)
                : new HtmlResponse($html, $status, $headers);
        }

        $encodingOptions = isset($options['encodingOptions']) && \is_int($options['encodingOptions'])
            ? $options['encodingOptions']
            : JsonResponse::DEFAULT_JSON_FLAGS;

        return new JsonResponse(
            $normalized,
            $status,
            $headers,
            $encodingOptions
        );
    }

    public function errorResponse(
        ServerRequestInterface $request,
        array $messages = [],
        int $status = StatusCodeInterface::STATUS_BAD_REQUEST,
        array $headers = [],
        array $options = [],
        ?string $template = null
    ): ResponseInterface {
        if ($this->isJsonRequest($request)) {
            return $this->jsonResponse($messages, $status, $headers);
        }

        $template = $template ?? "marshal::error-unknown";
        return $this->htmlResponse($template, $messages, $status, $headers, $options);
    }

    public function jsonResponse(
        array $data = [],
        int $status = StatusCodeInterface::STATUS_OK,
        array $headers = [],
        int $encodingOptions = JsonResponse::DEFAULT_JSON_FLAGS
    ): ResponseInterface {
        return new JsonResponse($data, $status, $headers, $encodingOptions);
    }
    
    public function notFoundResponse(
        ServerRequestInterface $request,
        array $messages = [],
        array $headers = [],
        array $options = []
    ): ResponseInterface {
        if ($this->isJsonRequest($request)) {
            return $this->jsonResponse($messages, StatusCodeInterface::STATUS_NOT_FOUND, $headers);
        }

        $template = Config::get('templates')['notFound'] ?? "marshal::error-404";
        return $this->htmlResponse($template, $messages, StatusCodeInterface::STATUS_BAD_REQUEST, $headers, $options);
    }

    public function redirectResponse(
        string $uri,
        int $status = StatusCodeInterface::STATUS_FOUND,
        array $headers = []
    ): ResponseInterface {
        return new RedirectResponse($uri, $status, $headers);
    }

    public function sseResponse(
        int $status = 200,
        array $headers = [],
        ?StreamInterface $body = null,
        string $protocol = '1.1'
    ): SseResponse {
        return new SseResponse($status, $headers, $body, $protocol);
    }

    private function htmlResponse(
        string $template,
        iterable $data = [],
        int $status = StatusCodeInterface::STATUS_OK,
        array $headers = [],
        array $options = []
    ): ResponseInterface {
        $renderer = $this->templateRendererResolver->resolve($template);
        $html = $renderer->render($template, $this->normalizeData($data), $options);
        return new HtmlResponse($html, $status, $headers);
    }

    private function isJsonRequest(ServerRequestInterface $request): bool
    {
        return $request->hasHeader('Content-Type')
            && false !== \strpos($request->getHeaderLine('Content-Type'), 'application/json');
    }

    private function normalizeData(iterable $data): array
    {
        $res = [];
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $res[$key] = $this->normalizeData($value);
                continue;
            }

            if ($value instanceof Content) {
                $res[$key] = $value->toArray();
                continue;
            }

            if ($value instanceof Collection) {
                $collection = [];
                foreach ($value as $row) {
                    if (\is_array($row)) {
                        $collection[] = $this->normalizeData($row);
                    }

                    if ($row instanceof Content) {
                        $collection[] = $row->toArray();
                    }
                }
                $res[$key] = $collection;
                continue;
            }

            $res[$key] = $value;
        }

        return $res;
    }
}
