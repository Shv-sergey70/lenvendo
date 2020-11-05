<?php

namespace App\Service\PageLoader;

use Symfony\Component\DomCrawler\Crawler;

class Response
{
    /** @var Crawler */
    private $parser;

    /** @var string  */
    private $favicon;

    /**
     * @param string  $pageContent
     * @param Favicon $favicon
     */
    public function __construct(string $pageContent, Favicon $favicon)
    {
        $this->parser = new Crawler($pageContent);
        $this->favicon = $favicon;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->parser->filterXPath('//title')->text();
    }

    /**
     * @return array
     */
    public function getKeywords(): array
    {
        $keywordsElement = $this->parser->filterXPath("//meta[@name='keywords']")->first();

        return $keywordsElement->count() === 0
            ? []
            : $this->getFormattedKeywords($keywordsElement->attr('content'));
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        $descriptionElement = $this->parser->filterXPath("//meta[@name='description']")->first();

        return $descriptionElement->count() === 0 ? '' : $descriptionElement->attr('content');
    }

    /**
     * @return Favicon
     */
    public function getFavicon(): Favicon {
        return $this->favicon;
    }

    /**
     * @param string $keywords
     *
     * @return array
     */
    private function getFormattedKeywords(string $keywords): array
    {
        $explodedKeywords = explode(',', $keywords);

        return array_map('trim', $explodedKeywords);
    }
}
