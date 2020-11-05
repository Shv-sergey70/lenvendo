<?php

namespace App\Service\PageLoader;

interface LoaderInterface
{
    public function download(string $url): Response;
}
