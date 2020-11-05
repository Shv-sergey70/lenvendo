<?php

namespace App\DataFixtures;

use App\Entity\Bookmark;
use App\Entity\Keyword;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookmarkFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private const BOOKMARKS_NUMBER = 100;
    private const MIN_FAVICON_NUMBER = 1;
    private const MAX_FAVICON_NUMBER = 7;

    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Bookmark::class, self::BOOKMARKS_NUMBER, function (Bookmark $bookmark) {
            $bookmark
                ->setUrl($this->faker->url)
                ->setFavicon($this->getRandomFavicon())
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
    public function getDependencies(): array
    {
        return [
            KeywordFixtures::class
        ];
    }

    /**
     * @return string
     */
    private function getRandomFavicon(): string {
        return "favicon_{$this->faker->numberBetween(self::MIN_FAVICON_NUMBER, self::MAX_FAVICON_NUMBER)}.ico";
    }
}
