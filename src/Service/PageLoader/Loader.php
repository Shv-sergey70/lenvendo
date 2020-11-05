<?php

namespace App\Service\PageLoader;

use App\Service\PageLoader\Exception\LoaderException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Loader implements LoaderInterface
{
    private const SUCCESS_STATUS_CODE = 200;
    private const METHOD_GET = 'GET';
    private const FAVICON_URL = 'http://www.google.com/s2/favicons?domain=';

    /** @var HttpClient */
    private $client;

    /**
     * Loader constructor
     */
    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    /**
     * @param string $url
     *
     * @return Response
     *
     * @throws LoaderException
     */
    public function download(string $url): Response
    {
        try {
            $pageResponse = $this->downloadPage($url);
            $faviconResponse = $this->downloadFavicon($url);

            if ($pageResponse->getStatusCode() !== self::SUCCESS_STATUS_CODE) {
                throw new LoaderException("Bad status code from page response {$pageResponse->getStatusCode()}");
            }

            if ($faviconResponse->getStatusCode() !== self::SUCCESS_STATUS_CODE) {
                throw new LoaderException("Bad status code from favicon response {$faviconResponse->getStatusCode()}");
            }

            return new Response(
                $pageResponse->getContent(),
                new Favicon($faviconResponse->getContent(), $url)
            );
        } catch (ExceptionInterface $e) {
            throw new LoaderException();
        }
    }

    /**
     * @param string $url
     *
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    private function downloadPage(string $url): ResponseInterface
    {
        return $this->client->request(self::METHOD_GET, $url);
    }

    /**
     * @param string $url
     *
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    private function downloadFavicon(string $url): ResponseInterface
    {
        return $this->client->request(self::METHOD_GET, $this->getFaviconUrl($url));
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getFaviconUrl(string $url): string
    {
        return self::FAVICON_URL . $url;
    }
}
