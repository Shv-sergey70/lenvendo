<?php

namespace App\Service\PageLoader;

class Favicon
{
    /** @var string  */
    private $faviconContent;

    /** @var string */
    private $url;

    /**
     * @param string $faviconContent
     * @param string $url
     */
    public function __construct(string $faviconContent, string $url)
    {
        $this->faviconContent = $faviconContent;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return str_replace('.', '_', parse_url($this->url, PHP_URL_HOST)) . '.ico';
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->faviconContent;
    }
}
