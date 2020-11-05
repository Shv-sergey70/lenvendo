<?php

namespace App\Entity;

use App\Repository\BookmarkRepository;
use App\Validator\UniqueUrl;
use App\Validator\Url;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity(repositoryClass=BookmarkRepository::class)
 */
class Bookmark
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     * @NotBlank(message="Url-адрес не должен быть пустым")
     * @Url()
     * @UniqueUrl()
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $page_title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $favicon;

    /**
     * @ORM\ManyToMany(targetEntity=Keyword::class, inversedBy="bookmarks")
     */
    private $keywords;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPageTitle(): ?string
    {
        return $this->page_title;
    }

    public function setPageTitle(string $page_title): self
    {
        $this->page_title = $page_title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFavicon(): ?string
    {
        return $this->favicon;
    }

    public function getFaviconPath() {
        return "images/favicons/{$this->getFavicon()}";
    }

    public function setFavicon(string $favicon): self
    {
        $this->favicon = $favicon;

        return $this;
    }

    /**
     * @return Collection|Keyword[]
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): self
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords[] = $keyword;
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): self
    {
        $this->keywords->removeElement($keyword);

        return $this;
    }
}
