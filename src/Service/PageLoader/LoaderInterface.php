<?php

namespace App\Service\PageLoader;

interface LoaderInterface
{
    /**
     * @param string $url
     */
    public function downloadPage(string $url);
}
