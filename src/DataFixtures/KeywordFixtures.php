<?php

namespace App\DataFixtures;

use App\Entity\Keyword;
use Doctrine\Persistence\ObjectManager;

class KeywordFixtures extends BaseFixtures
{
    private const KEYWORDS_NUMBER = 30;

    /**
     * @inheritDoc
     */
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Keyword::class, self::KEYWORDS_NUMBER, function (Keyword $keyword) {
            $keyword->setName($this->faker->realText(25));
        });
    }
}
