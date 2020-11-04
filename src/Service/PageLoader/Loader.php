<?php

namespace App\Service\PageLoader;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;

class Loader implements LoaderInterface
{
    private const METHOD_GET = 'GET';
    private const FAVICON_URL = 'http://www.google.com/s2/favicons?domain=';
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /** @var HttpClient */
    private $client;

    /**
     * Loader constructor
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->client = HttpClient::create();
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $url
     */
    public function downloadPage(string $url)
    {
        $response = $this->client->request(self::METHOD_GET, $url);

        if ($response->getStatusCode() === 200) {
            $crawler = new Crawler($response->getContent());

            $title = $crawler->filterXPath('//title')->text();
            $keywordsRaw = $crawler->filterXPath("//meta[@name='keywords']")->first()->attr('content');
            $description = $crawler->filterXPath("//meta[@name='description']")->first()->attr('content');

            $keywords = $this->getFormattedKeywords($keywordsRaw);
            $faviconName = $this->getNameForFavicon($url);
            $favicon = $this->getFavicon($this->getFaviconUrl($url));

            dd($title, $keywords, $description, $faviconName);

            $this->saveFavicon($faviconName, $favicon);

            return new Response($title, $keywords, $description, $faviconName);
        } else {
            dd("Bad response {$response->getStatusCode()}");
        }
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getFaviconUrl(string $url) {
        return self::FAVICON_URL . $url;
    }

    private function getFavicon(string $faviconUrl) {
        return $this->client->request(self::METHOD_GET, $faviconUrl)->getContent();
    }

    private function getPathForSavingFavicon(string $name) {
        return "images/favicons/{$name}";
    }

    private function getHost(string $url) {
        return parse_url($url, PHP_URL_HOST);
    }

    private function getNameForFavicon(string $url) {
        return str_replace('.', '_', parse_url($url, PHP_URL_HOST)) . '.ico';
    }

    private function saveFavicon(string $faviconName, string $favicon) {
        $this->filesystem->appendToFile(
            $this->getPathForSavingFavicon($faviconName),
            $favicon
        );
    }

    /**
     * @param string $keywords
     *
     * @return array
     */
    private function getFormattedKeywords(string $keywords) {
        $explodedKeywords = explode(',', $keywords);

        return array_map('trim', $explodedKeywords);
    }
}
