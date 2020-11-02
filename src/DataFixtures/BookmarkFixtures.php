<?php

namespace App\DataFixtures;

use App\Entity\Bookmark;
use App\Entity\Keyword;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookmarkFixtures extends BaseFixtures implements DependentFixtureInterface
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
                ->setPageTitle($this->faker->text(25))
                ->setDescription($this->faker->text)
                ->setCreatedAt($this->faker->dateTimeThisMonth)
            ;

            for ($i = 0; $i < $this->faker->numberBetween(0, 5); $i++) {
                /** @var Keyword $keyword */
                $keyword = $this->getRandomReference(Keyword::class);

                $bookmark->addKeyword($keyword);
            }
        });
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            KeywordFixtures::class
        ];
    }

}
