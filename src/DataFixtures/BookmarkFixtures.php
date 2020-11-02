<?php

namespace App\DataFixtures;

use App\Entity\Bookmark;
use Doctrine\Persistence\ObjectManager;

class BookmarkFixtures extends BaseFixtures
{
    private const BOOKMARKS_NUMBER = 100;

    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Bookmark::class, self::BOOKMARKS_NUMBER, function (Bookmark $bookmark) {
            $bookmark
                ->setUrl($this->faker->url)
                ->setFavicon($this->faker->url)
                ->setPageTitle($this->faker->title)
                ->setDescription($this->faker->text)
                ->setCreatedAt($this->faker->dateTimeThisMonth)
            ;
        });
    }
}
