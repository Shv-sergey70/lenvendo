<?php

namespace App\Service\PageLoader;

use App\Service\PageLoader\Exception\LoaderException;

interface LoaderInterface
{
    /**
     * @param string $url
     *
     * @return Response
     * @throws LoaderException
     */
    public function download(string $url): Response;
}
