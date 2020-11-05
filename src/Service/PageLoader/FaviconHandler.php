<?php

namespace App\Service\PageLoader;

use Symfony\Component\Filesystem\Filesystem;

class FaviconHandler implements FaviconHandlerInterface
{
    /** @var Filesystem */
    private $filesystem;

    /** @var Favicon */
    private $favicon;

    /**
     * @param Filesystem $filesystem
     * @param Favicon    $favicon
     */
    public function __construct(Filesystem $filesystem, Favicon $favicon)
    {
        $this->filesystem = $filesystem;
        $this->favicon = $favicon;
    }

    /**
     * @return void
     */
    public function save(): void {
        $this->filesystem->appendToFile($this->getPathForSaving(), $this->favicon->getContent());
    }

    /**
     * @return string
     */
    private function getPathForSaving(): string {
        return "images/favicons/{$this->favicon->getName()}";
    }
}
